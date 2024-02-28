<?php
namespace App\Repositories;

use App\Models\StrategyReportRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategyReportRecomRepository extends BaseRepository
{
    protected StrategyReportRecommendation $strategyReportRecommendation;

    public function __construct(StrategyReportRecommendation $strategyReportRecommendation)
    {
        $this->strategyReportRecommendation = $strategyReportRecommendation;
    }

    public function setStrategyReportRecom(StrategyReportRecommendation $strategyReportRecommendation):void
    {
        $this->strategyReportRecommendation = $strategyReportRecommendation;
    }



    //Create the model from either a HTPT Request or from an array
    public function create(mixed $input): StrategyReportRecommendation
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }
        return $this->strategyReportRecommendation->create($input);
    }

    //Update the given details
    /**
     * //Create the model from either a HTPT Request or from a pre-validated array
     * @param Request $request
     * @return void
     */
    public function update(mixed $input): void
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe();
        }
        $this->strategyReportRecommendation->update($input);
    }


    public function createStrategyReportRecom(): StrategyReportRecommendation
    {
        DB::beginTransaction();
        try {
            $newRecord = StrategyReportRecommendation::create();
            DB::commit();

            return $newRecord;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }
}
