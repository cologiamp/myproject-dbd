<?php
namespace App\Repositories;

use App\Models\StrategyRecomObjectives;
use App\Models\StrategyReportRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ClientNotFoundException;

class StrategyRecomObjectivesRepository extends BaseRepository
{
    protected StrategyRecomObjectives $strategyRecomObjectives;

    public function __construct(StrategyRecomObjectives $strategyRecomObjectives)
    {
        $this->strategyRecomObjectives = $strategyRecomObjectives;
    }

    public function setStrategyReportRecom(StrategyReportRecommendation $strategyReportRecom):void
    {
        $this->strategyReportRecom = $strategyReportRecom;
    }

    public function getStrategyReportRecom() : StrategyReportRecommendation
    {
        if($this->strategyReportRecom){
            return $this->strategyReportRecom;
        }
        throw new ClientNotFoundException();
    }

    //Create the model from either a HTPT Request or from an array
    public function create(mixed $input): StrategyRecomObjectives
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }
        return $this->strategyRecomObjectives->create($input);
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
        $this->strategyRecomObjectives->update($input);
    }
}
