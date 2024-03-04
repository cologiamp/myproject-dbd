<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\AssetRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DependentRepository;
use App\Repositories\HealthRepository;
use App\Repositories\EmploymentDetailRepository;
use App\Repositories\InvestmentRepository;
use App\Repositories\LumpSumCapitalRepository;
use App\Repositories\PensionRepository;
use App\Repositories\LiabilityRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\ExpenditureRepository;
use App\Repositories\ShareSaveRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Throwable;

class FactFindSectionDataService
{
    use FormatsCurrency;
    protected ClientRepository $cr;
    protected DependentRepository $dependentRepository;
    protected HealthRepository $healthRepository;
    protected EmploymentDetailRepository $employmentDetailRepository;
    protected LiabilityRepository $liabilityRepository;
    protected AssetRepository $assetRepository;
    protected IncomeRepository $incomeRepository;
    protected ExpenditureRepository $expenditureRepository;

    public function __construct(
        ClientRepository $clientRepository,
        DependentRepository $dependentRepository,
        HealthRepository $healthRepository,
        EmploymentDetailRepository $employmentDetailRepository,
        AssetRepository $assetRepository,
        LiabilityRepository $liabilityRepository,
        IncomeRepository $incomeRepository,
        ExpenditureRepository $expenditureRepository
) {
        $this->cr = $clientRepository;
        $this->dependentRepository = $dependentRepository;
        $this->healthRepository = $healthRepository;
        $this->employmentDetailRepository = $employmentDetailRepository;
        $this->assetRepository = $assetRepository;
        $this->liabilityRepository = $liabilityRepository;
        $this->incomeRepository = $incomeRepository;
        $this->expenditureRepository = $expenditureRepository;
    }
    //get the data for a single section of a factfind from a single client
    public static function get($client, $step, $section): array
    {
        return [
            'enums' => $client->loadEnumsForStep($step, $section),
            'model' => $client->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $client->io_id . '/fact-find/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, Request $request, Client $client)
    {
        $messages = config('navigation_structures.factfind.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.factfind.' . $step . '.sections.' . $section . '.rules');

        if($step == 1 && $section == 3)
        {
            $rules['email_address'] = $rules['email_address'] . ',email_address,'.$client->id;
        }
        return Validator::make($request->all(), $rules, $messages)->validate();
    }

    public function validated(int $step, int $section, Request $request,  Client $client)
    {
        $messages = config('navigation_structures.factfind.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.factfind.' . $step . '.sections.' . $section . '.rules');
        if($step == 1 && $section == 3)
        {
            $rules['email_address'] = $rules['email_address'] . ',email_address,'.$client->id;
        }
        return Validator::make($request->all(), $rules, $messages)->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store(Client $client, int $step, int $section, array $validatedData): true
    {

        $this->cr->setClient($client);
        $this->{"_" . $step . $section}($validatedData);
        return true;
    }


    //FactFind:// Need to write a function named _{section}{step} for every form
    /**
     * Section: 1
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _11(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if (array_key_exists('date_of_birth', $validatedData)) {
            $validatedData['date_of_birth'] = Carbon::parse($validatedData['date_of_birth']);
        }
        if (array_key_exists('country_of_domicile', $validatedData)  && $validatedData['country_of_domicile'] != null) {
            $validatedData['country_of_domicile'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_domicile']];
        }
        if (array_key_exists('country_of_residence', $validatedData) && $validatedData['country_of_residence'] != null) {
            $validatedData['country_of_residence'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_residence']];
        }
        if (array_key_exists('ni_number', $validatedData)) {
            $validatedData['ni_number'] = strtoupper($validatedData['ni_number']);
        }
        if (array_key_exists('first_name', $validatedData)) {
            $validatedData['first_name'] = ucfirst($validatedData['first_name']);
        }
        if (array_key_exists('middle_name', $validatedData)) {
            $validatedData['middle_name'] = ucfirst($validatedData['middle_name']);
        }
        if (array_key_exists('last_name', $validatedData)) {
            $validatedData['last_name'] = ucfirst($validatedData['last_name']);
        }
        if (array_key_exists('poa_name', $validatedData)) {
            $validatedData['poa_name'] = ucfirst($validatedData['poa_name']);
        }
        //This example only has data from one table. This would be different if not the case. May need multiple repositories.
        $this->cr->updateFromValidated($validatedData);
    }

    /**
     * Section: 1
     * Step: 2
     * @param array $validatedData
     * @return void
     */
    private function _12(array $validatedData): void
    {
        if (array_key_exists('is_in_good_health', $validatedData)) {
            if ($validatedData['is_in_good_health'] == true) {
                $validatedData['health_details'] = '';
            }
        }

        if (array_key_exists('has_life_expectancy_concerns', $validatedData)) {
            if ($validatedData['has_life_expectancy_concerns'] == false) {
                $validatedData['life_expectancy_details'] = '';
            }
        }

        try {
            $this->healthRepository->setClient($this->cr->getClient());
            $this->healthRepository->createOrUpdateHealthDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 1
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _13(array $validatedData): void
    {
        try {
            if (array_key_exists('addresses', $validatedData)) {

                $client = $this->cr->getClient();
                $passedAddressIds =  collect($validatedData['addresses'])->pluck('address_id')->filter();
                //addresses can belong to multiple clients, so we don't destroy them, just detach
                $client->addresses()->detach($client->addresses->whereNotIn('id', $passedAddressIds)->pluck('id'));

                collect($validatedData['addresses'])->each(function ($item) {
                    if (array_key_exists('date_from',$item) && $item['date_from'] && $item['date_from'] != null) {
                        $item['date_from'] = Carbon::parse($item['date_from']);
                    }
                    if($item['country'] && $item['country'] != null)
                    {
                        $item['country'] = (int)$item['country'];
                    }

                    $this->cr->createOrUpdateAddress($item);
                });
            }

            $contactDetails = array(
                'phone_number' => $validatedData['phone_number'],
                'email_address' => $validatedData['email_address'],
                'mobile_number' => $validatedData['mobile_number']
            );

            $this->cr->updateFromValidated($contactDetails);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 1
     * Step: 4
     * @param array $validatedData
     * @return void
     */
    private function _14(array $validatedData): void
    {
        try {
            if (array_key_exists('dependents', $validatedData)) {
                $dependents = collect($validatedData['dependents'])->map(function ($dependent) {
                    if ($dependent['born_at']) {
                        $dependent['born_at'] = Carbon::parse($dependent['born_at']);
                    }
                    if ($dependent['financially_dependent_until']) {
                        $dependent['financially_dependent_until'] = Carbon::parse($dependent['financially_dependent_until']);
                    }
                    if($dependent['is_living_with_clients'] === null)
                    {
                        $dependent['is_living_with_clients'] = true;
                    }
                    if ($dependent['name']) {
                        $dependent['name'] = ucfirst($dependent['name']);
                    }

                    return $dependent;
                });

                $validatedData['dependents'] = $dependents->toArray();
            }
            $this->dependentRepository->setClient($this->cr->getClient());
            $this->dependentRepository->createOrUpdateDependentDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 1
     * Step: 5
     * @param array $validatedData
     * @return void
     */
    private function _15(array $validatedData): void
    {
        try {
            if (array_key_exists('employment_details', $validatedData)) {
                $employment_details = collect($validatedData['employment_details'])->map(function ($employment) {
                    if ($employment['start_at']) {
                        $employment['start_at'] = Carbon::parse($employment['start_at']);
                    }
                    if ($employment['end_at']) {
                        $employment['end_at'] = Carbon::parse($employment['end_at']);
                    }
                    if ($employment['employer']) {
                        $employment['employer'] = ucfirst($employment['employer']);
                    }
                    if ($employment['occupation']) {
                        $employment['occupation'] = ucfirst($employment['occupation']);
                    }
                    return $employment;
                });


                $validatedData['employment_details'] = $employment_details->toArray();
            }

            $this->employmentDetailRepository->setClient($this->cr->getClient());
            $this->employmentDetailRepository->createOrUpdateEmploymentDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 2
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _21(array $validatedData): void
    {
        try {
            if (array_key_exists('incomes', $validatedData)) {
                $incomes = collect($validatedData['incomes'])->map(function ($income) {
                    if ($income['ends_at'] && $income['ends_at'] != null) {
                        $income['ends_at'] = Carbon::parse($income['ends_at']);
                    }
                    if ($income['starts_at'] && $income['starts_at'] != null) {
                        $income['starts_at'] = Carbon::parse($income['starts_at']);
                    }
                    if ($income['gross_amount'] && $income['gross_amount'] != null) {
                        $income['gross_amount'] = $this->currencyStringToInt($income['gross_amount']);
                    }
                    if ($income['net_amount'] && $income['net_amount'] != null) {
                        $income['net_amount'] = $this->currencyStringToInt($income['net_amount']);
                    }
                    if ($income['expenses'] && $income['expenses'] != null) {
                        $income['expenses'] = $this->currencyStringToInt($income['expenses']);
                    }

                    return $income;
                });

                $validatedData['incomes'] = $incomes->toArray();
            }


            $this->incomeRepository->setClient($this->cr->getClient());
            $this->incomeRepository->createOrUpdateIncomeDetails($validatedData);

            if(count($validatedData['incomes']) == 0 && array_key_exists('total',$validatedData))
            {
                $this->cr->updateFromValidated(['total_income_basic' => $this->currencyStringToInt($validatedData['total'])]);
            }
            else{
                $this->cr->updateFromValidated(['total_income_basic' => 0]);
            }
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 2
     * Step: 2
     * @param array $validatedData
     * @return void
     */
    private function _22(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Section: 2
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _23(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Section: 2
     * Step: 4
     * @param array $validatedData
     * @return void
     */
    private function _24(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Section: 2
     * Step: 5
     * @param array $validatedData
     * @return void
     */
    private function _25(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

     private function _31(array $validatedData): void
    {
        $client= $this->cr->getClient();
        try{
            if (array_key_exists('fixed_assets', $validatedData)) {
                $fixed_assets = collect($validatedData['fixed_assets'])->map(function ($asset) use ($client) {
                    if (array_key_exists('purchased_at',$asset)){
                        $asset['start_at'] = Carbon::parse($asset['purchased_at']);
                        unset($asset['purchased_at']);
                    }
                    $asset['category'] = array_flip(config('enums.assets.categories'))['fixed_assets'];
                    $asset['type'] = $asset['asset_type'];
                    if (array_key_exists('original_value',$asset) && $asset['original_value'] != null){
                        $asset['original_value'] = $this->currencyStringToInt($asset['original_value']);
                    }
                    if (array_key_exists('retained_value',$asset) && $asset['retained_value'] != null){
                        $asset['retained_value'] = $this->currencyStringToInt($asset['retained_value']);
                    }
                    if (array_key_exists('current_value',$asset) && $asset['current_value'] != null){
                        $asset['current_value'] = $this->currencyStringToInt($asset['current_value']);
                    }
                    unset($asset['asset_type']);//remove
                    return $asset;
                });
                $validatedData['assets'] = $fixed_assets->toArray();

            }
            $this->assetRepository->setClient($client);
            $this->assetRepository->createOrUpdateAssets($validatedData);

        }
        catch(Throwable $e){
            Log::warning($e);
        }
    }
    private function _32(array $validatedData): void
    {
        $client = $this->cr->getClient();
        try{
            if (array_key_exists('saving_assets', $validatedData)) {
                $saving_assets = collect($validatedData['saving_assets'])->map(function ($asset) use ($client) {
                    if (array_key_exists('start_date',$asset)){
                        if( $asset['start_date'] != null)
                        {
                            $asset['start_at'] = Carbon::parse($asset['start_date']);
                        }
                        else
                        {
                            $asset['start_at'] = null;
                        }
                        unset($asset['start_date']);
                    }
                    if (array_key_exists('end_date',$asset)){
                        if( $asset['end_date'] != null)
                        {
                            $asset['end_at'] = Carbon::parse($asset['end_date']);
                        }
                        else
                        {
                            $asset['end_at'] = null;
                        }
                        unset($asset['end_date']);
                    }
                    if (array_key_exists('name',$asset)){
                        $asset['product_name'] = $asset['name'];
                        unset($asset['name']);
                    }
                    if(array_key_exists('provider',$asset) && $asset['provider'] != null && is_array($asset['provider'])){
                        $asset['provider'] = $asset['provider']['value'];
                    }

                    $asset['category'] = array_flip(config('enums.assets.categories'))['savings'];
                    $asset['type'] = array_flip(config('enums.assets.types'))['Cash'];
                    unset($asset['asset_type']);//remove
                    if (array_key_exists('current_balance',$asset)){
                        if( $asset['current_balance'] != null)
                        {
                            $asset['current_value'] = $this->currencyStringToInt($asset['current_balance']);
                        }
                        unset($asset['current_balance']);
                    }
                    if (array_key_exists('retained_value',$asset) && $asset['retained_value'] != null){
                        $asset['retained_value'] = $this->currencyStringToInt($asset['retained_value']);
                    }
                    if (array_key_exists('contribution_amount',$asset) && $asset['contribution_amount'] != null){
                        $asset['contribution_amount'] = $this->currencyStringToInt($asset['contribution_amount']);
                    }

                    return $asset;
                });
                $validatedData['assets'] = $saving_assets->toArray();
            }
            $this->assetRepository->setClient($client);
            $this->assetRepository->createOrUpdateAssets($validatedData);

        }
        catch(Throwable $e){
            Log::warning($e);
        }
    }

    private function _33(array $validatedData): void
    {


        $validatedData = collect($validatedData['investments'])->map(function ($item){

            if(array_key_exists('owner',$item) && $item['owner'] != null)
            {
                $item['client_id'] = Client::where('io_id',$item['owner'])->first()->id;
            }
            else{
                $item['client_id'] = $this->cr->getClient()->id;
            }

            unset($item['owner']);
            if(array_key_exists('account_type',$item)){
                $item['contract_type'] = $item['account_type'];
                unset($item['account_type']);
            }
            if (array_key_exists('start_date',$item)  && $item['start_date'] != null){
                $item['start_date'] = Carbon::parse($item['start_date']);
            }
            else{
                unset($item['start_date']);
            }
            if (array_key_exists('maturity_date',$item) && $item['maturity_date'] != null){
                $item['maturity_date'] = Carbon::parse($item['maturity_date']);
            }
            else{
                unset($item['maturity_date']);
            }
            if (array_key_exists('valuation_at',$item) && $item['valuation_at'] != null){
                $item['valuation_at'] = Carbon::parse($item['valuation_at']);
            }
            else{
                unset($item['valuation_at']);
            }

            if (array_key_exists('current_value',$item) && $item['current_value'] != null){
                $item['current_value'] = $this->currencyStringToInt($item['current_value']);
            }
            if (array_key_exists('regular_contribution',$item) && $item['regular_contribution'] != null){
                $item['regular_contribution'] = $this->currencyStringToInt($item['regular_contribution']);
            }
            if (array_key_exists('lump_sum_contribution',$item) && $item['lump_sum_contribution'] != null){
                $item['lump_sum_contribution'] = $this->currencyStringToInt($item['lump_sum_contribution']);
            }
            if (array_key_exists('retained_value',$item) && $item['retained_value'] != null){
                $item['retained_value'] = $this->currencyStringToInt($item['retained_value']);
            }
            if(array_key_exists('provider',$item) && $item['provider'] != null && is_array($item['provider'])){
                $item['provider'] = $item['provider']['value'];
            }


            return $item;
        });

        $ir = App::make(InvestmentRepository::class);
        try{
            $ir->createOrUpdateInvestments($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }
    }

    private function _34(array $validatedData): void
    {
        $dc = collect($validatedData['dc_pensions'])->map(function ($item){

            if (array_key_exists('employer', $item) && $item['employer'] != '') {
                $item['employer'] = ucfirst($item['employer']);
            }

            if (array_key_exists('fund_name', $item) && $item['fund_name'] != '') {
                $item['fund_name'] = ucfirst($item['fund_name']);
            }

            if(array_key_exists('owner',$item) && $item['owner'] != null)
            {
                $item['client_id'] = Client::where('io_id',$item['owner'])->first()->id;
            }
            else{
                $item['client_id'] = $this->cr->getClient()->id;
            }
            unset($item['owner']);

            if (array_key_exists('policy_starts_at',$item)  && $item['policy_starts_at'] != null){
                $item['policy_start_at'] = Carbon::parse($item['policy_starts_at']);
            }
            unset($item['policy_starts_at']);

            if (array_key_exists('valuation_at',$item) && $item['valuation_at'] != null){
                $item['valuation_at'] = Carbon::parse($item['valuation_at']);
            }
            else{
                unset($item['valuation_at']);
            }

            if(array_key_exists('administrator',$item) && $item['administrator'] != null && is_array($item['administrator'])){
                $item['administrator'] = $item['administrator']['value'];
            }

            if (array_key_exists('gross_contribution_absolute',$item) && $item['gross_contribution_absolute'] != null){
                $item['gross_contribution_absolute'] = $this->currencyStringToInt($item['gross_contribution_absolute']);
            }
            if (array_key_exists('employer_contribution_absolute',$item) && $item['employer_contribution_absolute'] != null){
                $item['employer_contribution_absolute'] = $this->currencyStringToInt($item['employer_contribution_absolute']);
            }
            if (array_key_exists('value',$item) && $item['value'] != null){
                $item['value'] = $this->currencyStringToInt($item['value']);
            }
            if (array_key_exists('retained_value',$item) && $item['retained_value'] != null) {
                $item['retained_value'] = $this->currencyStringToInt($item['retained_value']);
            }
            if (array_key_exists('current_fund_value',$item) && $item['current_fund_value'] != null){
                $item['current_fund_value'] = $this->currencyStringToInt($item['current_fund_value']);
            }
            if (array_key_exists('current_transfer_value',$item) && $item['current_transfer_value'] != null){
                $item['current_transfer_value'] = $this->currencyStringToInt($item['current_transfer_value']);
            }
            return $item;
        });

        $db = collect($validatedData['db_pensions'])->map(function ($item){

            if (array_key_exists('employer', $item) && $item['employer'] != '') {
                $item['employer'] = ucfirst($item['employer']);
            }

            if(array_key_exists('owner',$item) && $item['owner'] != null)
            {
                $item['client_id'] = Client::where('io_id',$item['owner'])->first()->id;
            }
            else{
                $item['client_id'] = $this->cr->getClient()->id;
            }
            unset($item['owner']);

            if (array_key_exists('cetv_ends_at',$item) && $item['cetv_ends_at'] != null){
                $item['cetv_ends_at'] = Carbon::parse($item['cetv_ends_at']);
            }
            else{
                unset($item['cetv_ends_at']);
            }

            if (array_key_exists('prospective_pension_standard',$item) && $item['prospective_pension_standard'] != null){
                $item['prospective_pension_standard'] = $this->currencyStringToInt($item['prospective_pension_standard']);
            }
            if (array_key_exists('prospective_pension_max',$item) && $item['prospective_pension_max'] != null){
                $item['prospective_pension_max'] = $this->currencyStringToInt($item['prospective_pension_max']);
            }
            if (array_key_exists('prospective_pcls_standard',$item) && $item['prospective_pcls_standard'] != null){
                $item['prospective_pcls_standard'] = $this->currencyStringToInt($item['prospective_pcls_standard']);
            }
            if (array_key_exists('prospective_pcls_max',$item) && $item['prospective_pcls_max'] != null){
                $item['prospective_pcls_max'] = $this->currencyStringToInt($item['prospective_pcls_max']);
            }
            if (array_key_exists('cetv',$item) && $item['cetv'] != null){
                $item['cetv'] = $this->currencyStringToInt($item['cetv']);
            }

            return $item;
        });

        $pr = App::make(PensionRepository::class);
        try{
            $pr->createOrUpdateDCPensions($dc);
            $pr->createOrUpdateDBPensions($db);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }
}

    /**
     *  Step: 3
     *  Section: 5
     * @param array $validatedData
     * @return void
     */
    private function _35(array $validatedData):void
    {
        $schemes = collect($validatedData['schemes'])->map(function ($scheme){
            if(array_key_exists('owner',$scheme) && $scheme['owner'] != null)
            {
                $scheme['client_id'] = Client::where('io_id',$scheme['owner'])->first()->id;
            }
            else{
                $scheme['client_id'] = $this->cr->getClient()->id;
            }
            unset($scheme['owner']);

            if (array_key_exists('matures_at',$scheme) && $scheme['matures_at'] != null){
                $scheme['matures_at'] = Carbon::parse($scheme['matures_at']);
            }
            else{
                unset($scheme['matures_at']);
            }
            if (array_key_exists('option_price',$scheme) && $scheme['option_price'] != null){
                $scheme['option_price'] = $this->currencyStringToInt($scheme['option_price']);
            }
            if (array_key_exists('monthly_saving',$scheme) && $scheme['monthly_saving'] != null){
                $scheme['monthly_saving'] = $this->currencyStringToInt($scheme['monthly_saving']);
            }
            if(array_key_exists('name', $scheme) && $scheme['name'] != '')
            {
                $scheme['name'] = ucfirst($scheme['name']);
            }
            return $scheme;
        });

        $sr = App::make(ShareSaveRepository::class);
        try{
            $sr->createOrUpdateSchemes($schemes);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }
    }
    /**
     *  Step: 3
     *  Section: 6
     * @param array $validatedData
     * @return void
     */
    private function _36(array $validatedData):void
    {
        $capitals = collect($validatedData['capitals'])->map(function ($capital){
            if (array_key_exists('due_at',$capital) && $capital['due_at'] != null){
                $capital['due_at'] = Carbon::parse($capital['due_at']);
            }
            else{
                unset($capital['due_at']);
            }
            if (array_key_exists('amount',$capital) && $capital['amount'] != null){
                $capital['amount'] = $this->currencyStringToInt($capital['amount']);
            }
            if (array_key_exists('retained_value',$capital) && $capital['retained_value'] != null){
                $capital['retained_value'] = $this->currencyStringToInt($capital['retained_value']);
            }

            if (array_key_exists('description', $capital) && $capital['description'] != '') {
                $capital['description'] = ucfirst($capital['description']);
            }

            return $capital;
        });

        $lscr = App::make(LumpSumCapitalRepository::class);
        try{
            $lscr->createOrUpdateCapitals($capitals);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }
    }



    /**
     * Step: 4
     * Section: 1
     * @param array $validatedData
     * @return void
     */
    private function _41(array $validatedData): void
    {
        try {
            if (array_key_exists('liabilities', $validatedData)) {
                $liabilities = collect($validatedData['liabilities'])->map(function ($liability) {
                    if ($liability['ends_at'] && $liability['ends_at'] != null) {
                        $liability['ends_at'] = Carbon::parse($liability['ends_at']);
                    }
                    if (array_key_exists('amount_outstanding', $liability) && $liability['amount_outstanding'] != null) {
                        $liability['amount_outstanding'] = $this->currencyStringToInt($liability['amount_outstanding']);
                    }
                    if (array_key_exists('monthly_repayment', $liability) && $liability['monthly_repayment'] != null) {
                        $liability['monthly_repayment'] = $this->currencyStringToInt($liability['monthly_repayment']);
                    }

                    return $liability;
                });

                $validatedData['liabilities'] = $liabilities->toArray();
            }

            $this->liabilityRepository->setClient($this->cr->getClient());
            $this->liabilityRepository->createOrUpdateLiabilityDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Parse and update expenditure data
     * @param array $validatedData
     * @return void
     */
    private function parseAndUpdateExpenditure(array $validatedData): void
    {
        try {
            if (array_key_exists('expenditures', $validatedData)) {
                $expenditures = collect($validatedData['expenditures'])->map(function ($expenditure) {
                    if ($expenditure['amount'] && $expenditure['amount'] != null) {
                        $expenditure['amount'] = $this->currencyStringToInt($expenditure['amount']);
                    }
                    if ($expenditure['starts_at'] && $expenditure['starts_at'] != null) {
                        $expenditure['starts_at'] = Carbon::parse($expenditure['starts_at']);
                    }
                    if ($expenditure['ends_at'] && $expenditure['ends_at'] != null) {
                        $expenditure['ends_at'] = Carbon::parse($expenditure['ends_at']);
                    }

                    return $expenditure;
                });

                $validatedData['expenditures'] = $expenditures->toArray();
            }

            $this->expenditureRepository->setClient($this->cr->getClient());
            $this->expenditureRepository->createOrUpdateExpenditureDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }
}
