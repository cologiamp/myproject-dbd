<?php
namespace App\Repositories;



use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Request;

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
    public function update(UpdateClientRequest $request): void
    {
        //merge "other values" - eg array_merge($request->safe()->only([]),[])
        $this->client->update($request->safe()->all());
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
                    $data['title'] = $item['person']['title'];
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
                    $data['gender'] = $item['person']['gender'];
                }
                if(array_key_exists('maritalStatus',$item['person']) && $item['person']['maritalStatus'] != null)
                {
                    $data['marital_status'] = $item['person']['maritalStatus'];
                }
                if(array_key_exists('NationalityCountry',$item['person']) && $item['person']['NationalityCountry']['name'] != null)
                {
                    $data['nationality'] = $item['person']['NationalityCountry']['name'];
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
}
