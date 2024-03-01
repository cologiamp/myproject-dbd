<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use App\Actions\Fortify;
use App\Concerns\InterractsWithDataHub;
use App\Notifications\AdviserAccountCreated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Notification;

class GenerateAdvisersFromAPI extends Command
{
    use InterractsWithDataHub;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advisers:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = Cache::get("datahub_access_token", function () {
           return $this->setAccessToken();
        });

        $response = Http::withHeader("Authorization", $token)->get(config('datahub.url') . "api/advisers?is_enabled=true&adviser_hub_access=true");

        // todo: collect and then each - don't use foreach

//        $advisers = collect($response->json()['data']);

        foreach($response->json()['data'] as $adviser){
            $inc = User::latest()->first()->id + 1;
            if(count(User::where('email', $inc . $adviser['email'])->get()) === 0){
                // grabbing first IO ID for now
                foreach($adviser['io_ids'] as $i){
                    $io_id = [
                        $i['label'] => $i['value']
                    ];
                    break;
                }

                $temporary_pw = Str::password(10, true, true, false, false);
                $encrypted_pw = bcrypt($temporary_pw);

                $input = [
                    'name' => $adviser['name'],
                    'email' => $inc . $adviser['email'],
                    'password' => $encrypted_pw,
                    'password_confirmation' => $encrypted_pw,
                ];

                $user = new CreateNewUser;
                $response = $user->create($input);

                if(isset($response->id)){
                    $update_user = User::find($response->id);
                    $update_user->io_id = $io_id[$i['label']];
                    $update_user->profile_photo_path = $adviser['profile_pic_url'];
                    $update_user->has_temporary_password = true;
                    $update_user->save();

                    // send email
                    $update_user->notify(new AdviserAccountCreated($temporary_pw));
                }
            }
        }


//        dd($response->json());
    }
}
