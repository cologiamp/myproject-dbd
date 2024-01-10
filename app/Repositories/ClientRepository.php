<?php
namespace App\Repositories;



use App\Http\Requests\BaseClientRequest;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientRepository extends BaseRepository
{
    protected Client $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    //Create the model
    public function create(CreateClientRequest $request): Client
    {
        return $this->client->create(
            array_merge($request->safe()->all(),
                []
            ));
    }

    //Update the given details
    public function update(BaseClientRequest $request): void
    {
        //merge "other values" - eg array_merge($request->safe()->only([]),[])
        $this->client->update($request->safe()->only(["first_name", "last_name", "title"]));
        //relations here - e.g  $this->model->relation()->sync();
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->client->delete();
    }

    //Construct the query based on the request parameters to retrieve the right data for the correct domain context.
    //This is where further index scoping/loading can be added based on the request or context.
    public function constructIndexQuery(Request $request): Builder
    {
        return Client::
//          with()-> //Eager Load here
        select('clients.*');//Limit query here
    }

    public function filterIndexQuery(Request $request): Builder
    {
        return $this->constructIndexQuery($request)
            ->where("adviser_id", auth()->user()->id)
            ->when($request->has("search"), function($query) use ($request) {
                $query->where("first_name", "like", "%" . $request->input("search") . "%")
                    ->orWhere("last_name", "like", "%" . $request->input("search") . "%");
            })
            ->when($request->has("select"), function($query) use ($request) {
                switch($request->input("select")) {
                    case 1:
                        $query->orderBy("updated_at", "desc");
                    case 2:
                        $query->orderBy("updated_at", "asc");
                    default:
                        $query->orderBy("updated_at", "desc");
                }
            });
    }

    //get the options for example form. This is designed as an example of how these requests should be processed. (single client)
    public function getExampleFormOptions():array
    {
        return [
            'enums' => [
                'titles' => config('enums.client.title')
            ],
            'model' => $this->client->presenter()->formatForExampleForm(),
            'submit_method' => 'put',
            'submit_url' => '/client/' . $this->client->io_id . '/example'
        ];
    }
    public function getIndexOptions(): array
    {
        return [
            'models' => Auth::user()->clients->map(fn($c) => $c->presenter()->index())
        ];
    }

    public function syncIoForAdviser(int $adviser_id,Collection $ioClientCollection)
    {
        //Note: This will make N database queries. Refactor when extra time.
        $ioClientCollection->each(function ($item) use ($adviser_id){
            $client = Client::where('io_id',$item['id'])->first();
            if($client)
            {
                if($client->adviser_id != $adviser_id)
                {
                    $client->update(['adviser_id' => $adviser_id]);
                    $client->save();
                }
            }
            else{
                ray($item)->orange();
                $data = [
                    'io_id' => $item['id'],
                    'adviser_id' => $adviser_id,
                ];
                if(array_key_exists('title',$item['person']) && $item['person']['title'] != null)
                {
                    $data['title'] = array_flip(config('enums.client.title'))[$item['person']['title']];
                }
                if(array_key_exists('firstName',$item['person']) && $item['person']['firstName'] != null)
                {
                    $data['first_name'] = $item['person']['firstName'];
                }
                if(array_key_exists('lastName',$item['person']) && $item['person']['lastName'] != null)
                {
                    $data['last_name'] = $item['person']['lastName'];
                }
                if(array_key_exists('dateOfBirth',$item['person']) && $item['person']['dateOfBirth'] != null)
                {
                    $data['date_of_birth'] = $item['person']['dateOfBirth'];
                }
                if(array_key_exists('gender',$item['person']) && $item['person']['gender'] != null)
                {
                    $data['gender'] =  array_flip(config('enums.client.gender'))[$item['person']['gender']] ;
                }
                if(array_key_exists('maritalStatus',$item['person']) && $item['person']['maritalStatus'] != null)
                {
                    $data['marital_status'] = array_flip(config('enums.client.marital_status'))[$item['person']['maritalStatus']];
                }
                if(array_key_exists('NationalityCountry',$item['person']) && $item['person']['NationalityCountry']['name'] != null)
                {
                    $data['nationality'] = array_flip(config('enums.client.nationality'))[$item['person']['NationalityCountry']['name']];
                }
                if(array_key_exists('salutation',$item['person']) && $item['person']['salutation'] != null)
                {
                    $data['salutation'] = $item['person']['salutation'];
                }
                ray($data)->purple();
                $this->client->create($data);
            }
        });
    }

    /**
     * Load in factfind sidebar items dynamically for the tabs
     * @param int - the step that we want to load the sidebar for
     */
    public function loadFactFindSidebarItems($sections, $currentSection)
    {
        return collect($sections)->map(function ($value,$key) use ($currentSection){
           return  [
               'name' => $value,
               'current' => $key === $currentSection
           ];
        });
    }


    /**
     * Load in the correct data structure for the sidebar tabs of the page we're on
     * @return array
     */
    //Chore:: Refactor this to avoid needing to hardcode "factfind" in 2 places and make SOLID
    public function loadFactFindTabs(int $currentStep = 1,int $currentSection = 1):array
    {
        return collect(config('navigation_structures.factfind'))->map(function ($value,$key) use ($currentSection,$currentStep){

            return [
                'name' => $value['name'],
                'current' =>  $key === $currentStep,
                'progress' => $this->calculateFactFindElementProgress($key),
                'sidebaritems' => $this->loadFactFindSidebarItems($value['sections'], $currentSection)->toArray()
            ];
        })->toArray();
    }

    /**
     * Function to work out the progress % for each section.
     * @param $key
     * @return int
     */
    public function calculateFactFindElementProgress(int $section):int
    {
        $progress = match ($section) {
            1 => collect(Client::query()
                ->where("io_id", $this->client->io_id)
                ->select([
                    ...config('section_step_mappings.basic-details')
                ])
                ->first()),
            2 => collect(Client::query()
                ->where("io_id", $this->client->io_id)
                ->select([
                    ...config('section_step_mappings.income-and-expenditure')
                ])
                ->first()),
            3 => collect(Client::query()
                ->where("io_id", $this->client->io_id)
                ->select([
                    ...config('section_step_mappings.assets')
                ])
                ->first()),
            4 => collect(Client::query()
                ->where("io_id", $this->client->io_id)
                ->select([
                    ...config('section_step_mappings.liabilities')
                ])
                ->first()),
            5 => collect(Client::query()
                ->where("io_id", $this->client->io_id)
                ->select([
                    ...config('section_step_mappings.risk')
                ])
                ->first()),
            6 => collect(Client::query()
                ->where("io_id", $this->client->io_id)
                ->select([
                    ...config('section_step_mappings.objectives')
                ])
                ->first()),
            default => null
        };

        if ($progress->count() === 0) return 0;
        $filledFields = $progress->filter(fn($element) => $element !== null);
        $progression = $filledFields->count() / $progress->count();
        return $progression * 100;
    }
}
