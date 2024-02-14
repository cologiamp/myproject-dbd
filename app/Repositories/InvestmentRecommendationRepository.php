<?php
namespace App\Repositories;



use App\Concerns\ParsesIoClientData;
use App\Exceptions\ClientNotFoundException;
use App\Http\Requests\BaseClientRequest;
use App\Http\Requests\CreateClientRequest;
use App\Models\Client;
use App\Models\InvestmentRecommendation;
use App\Models\EmploymentDetail;
use App\Services\InvestmentRecommendationSectionDataService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

class InvestmentRecommendationRepository extends BaseRepository
{
    use ParsesIoClientData;

    protected Client $client;
    protected InvestmentRecommendation $investmentRecommendation;
    public function __construct(Client $client, InvestmentRecommendation $investmentRecommendation)
    {
        $this->client = $client;
        $this->investmentRecommendation = $investmentRecommendation;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
    public function setInvestmentRecommendation(InvestmentRecommendation $investmentRecommendation): void
    {
        $this->investmentRecommendation = $investmentRecommendation;
    }

    public function getInvestmentRecommendation() : InvestmentRecommendation
    {
        if($this->investmentRecommendation){
            return $this->investmentRecommendation;
        }
        throw new ClientNotFoundException();
    }

    //Create the model
    public function create(Request $request): InvestmentRecommendation
    {
        return $this->investmentRecommendation->create(
            array_merge($request->safe()->all(),
                []
            ));
    }

    //Update the given details

    /**
     * This method uses a generecised form request to update the model.
     * Use updateFromValidated if you have already run a Validator on your data.
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        //merge "other values" - eg array_merge($request->safe()->only([]),[])
        $this->investmentRecommendation->update($request->safe());
        //relations here - e.g  $this->model->relation()->sync();
    }

    /**
     * This method uses a pre-validated updated data array rather than a formRequest to update the model.
     * Do not use unless you have validated your data
     * @param array $array
     * @return void
     */
    public function updateFromValidated(array $array):void
    {
        $this->investmentRecommendation->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->investmentRecommendation->delete();
    }

    //Construct the query based on the request parameters to retrieve the right data for the correct domain context.
    //This is where further index scoping/loading can be added based on the request or context.
    public function constructIndexQuery(Request $request): Builder
    {
        return InvestmentRecommendation::
//          with()-> //Eager Load here
        select('investment_recommendations.*');//Limit query here
    }

    public function filterIndexQuery(Request $request): LengthAwarePaginator
    {
//        return Client::query()
//            ->where("adviser_id", auth()->user()->id)
//            ->when($request->input("search"), function($query, $search)  {
//                $query->where("first_name", "like", "%{$search}%")
//                    ->orWhere("last_name", "like",  "%{$search}%");
//            })
//            ->when($request->input("select"), function($query, $select)  {
//                switch($select) {
//                    case 1: // chore: refactor this into an enum
//                        $query->orderBy("updated_at", "desc");
//                    case 2:
//                        $query->orderBy("updated_at", "asc");
//                    default:
//                        $query->orderBy("updated_at", "desc");
//                }
//            })
//            ->paginate(
//                perPage: $request->filled("perPage") ? $request->input("perPage") : 9,
//                page: $request->boolean("searching") ? 1 : $request->input("page") ?? 1
//        );
    }

    public function syncIoForAdviser(int $adviser_id,Collection $ioClientCollection): void
    {
        //Note: This will make N database queries. Refactor when extra time.
//        $ioClientCollection->each(function ($item) use ($adviser_id){
//            $client = Client::where('io_id',$item['id'])->first();
//            if($client)
//            {
//                if($client->adviser_id != $adviser_id)
//                {
//                    $client->update(['adviser_id' => $adviser_id]);
//                    $client->save();
//                }
//            }
//            else{
//                ray($item)->orange();
//                $data = [
//                    'io_id' => $item['id'],
//                    'adviser_id' => $adviser_id,
//                ];
//                $data = array_merge($data,$this->parseClientFields($item['person']));
//                ray($data)->purple();
//                $this->client->create($data);
//            }
//        });
    }

    /**
     * Load in investmentrecommendation sidebar items dynamically for the tabs
     * @param int - the step that we want to load the sidebar for
     */
    public function loadInvestmentRecommendationSidebarItems($sections, $step, $currentStep, $currentSection): Collection
    {
        return collect($sections)->map(function ($value,$key) use ($currentStep, $currentSection, $step){
           return  [
               'name' => $value,
               'renderable' => Str::studly($value),
               'current' => $key === $currentSection,
               'dynamicData' => InvestmentRecommendationSectionDataService::get($this->investmentRecommendation,$step,$key),
           ];
        });
    }

//    //get the options for example form. This is designed as an example of how these requests should be processed. (single client)
//    public function getExampleFormOptions():array
//    {
//
//    }


    /**
     * Load in the correct data structure for the sidebar tabs of the page we're on
     * @return array
     */
    public function loadInvestmentRecommendationTabs(int $currentStep = 1,int $currentSection = 1):array
    {

        return collect(config('navigation_structures.investmentrecommendation'))->map(function ($value,$key) use ($currentSection,$currentStep){
            return [
                'name' => $value['name'],
                'current' =>  $key === $currentStep,
//                'progress' => $this->calculateInvestmentRecommendationElementProgress($key),
                'progress' => null,
                'sidebaritems' => $this->loadInvestmentRecommendationSidebarItems(collect($value['sections'])->mapWithKeys(function ($value,$key){
                    return [$key => $value['name']];
                }), $key, $currentStep, $currentSection)->toArray()
            ];
        })->toArray();
    }

    //InvestmentRecommendation://to do - make sure this works for your form
    /**
     * Function to work out the progress % for each step.
     * @param $key
     * @return int
     */
    public function calculateInvestmentRecommendationElementProgress(int $step):int
    {
        $progress = collect(config('navigation_structures.investmentrecommendation.' . $step . '.sections'))->map(function ($section){
            if(array_key_exists('fields',$section) && count($section['fields']) > 0)
            {
                return collect($section['fields'])->flatten()->groupBy(fn($item) => explode('.',$item)[0])->map(function ($value, $key){
                    $nestedFieldArrays = ['dependents', 'addresses'];

                    // process field names for nested field arrays
                    if(in_array($key, $nestedFieldArrays)){
                        $value = $value->map(function ($val) {
                            $keyName = explode('.',$val)[1];
                            return $keyName;
                        });
                    }

                    return match ($key) {
                        'clients' => Client::where("io_id", $this->client->io_id)->select([...$value])->first()->toArray(),
//                        'addresses' => $this->client->addresses()->where("client_id", $this->client->id)->select([...$value])->get() ? $this->client->addresses()->where("client_id", $this->client->id)->select([...$value])->get()->toArray() : collect([]),
//                        'health' => Health::where("client_id", $this->client->id)->select([...$value])->first() ? Health::where("client_id", $this->client->id)->select([...$value])->first()->toArray() : $this->setEmptyFields($value),
//                        'dependents' => $this->client->dependents()->where("client_id", $this->client->id)->select([...$value])->get() ? $this->client->dependents()->where("client_id", $this->client->id)->select([...$value])->get()->toArray() : collect([]),
//                        'employment_details' => EmploymentDetail::where("client_id", $this->client->id)->select([...$value])->get() ? EmploymentDetail::where("client_id", $this->client->id)->select([...$value])->get()->toArray() : $this->setEmptyFields($value),
//                        '//todo write join query here for other places data ends up'.
                        default => collect([]),
                    };
                });
            }
            else return collect([]);
        })->flatten();

        if ($progress->count() === 0) return 0;
        return $progress->filter(fn($element) => $element !== null)->count() / $progress->count() * 100;
    }

//    public function setEmptyFields(Collection $value)
//    {
//        $nullFields = new Collection();
//        $value->map(function ($val) use ($nullFields) {
//            $keyName = explode('.',$val)[1];
//            return $nullFields[$keyName] = null;
//        });
//
//        return $nullFields;
//    }
}
