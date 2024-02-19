<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\Expenditure;
use App\Models\Retirement;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetirementRepository extends BaseRepository
{
    protected Retirement $retirement;

    public function __construct(Retirement $retirement)
    {
        $this->retirement = $retirement;
    }

    public function setRetirement(Retirement $retirement):void
    {
        $this->retirement = $retirement;
    }



    //Create the model from either a HTPT Request or from an array
    public function create(mixed $input): Retirement
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }
        return $this->retirement->create($input);
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
        $this->retirement->update($input);
    }



}
