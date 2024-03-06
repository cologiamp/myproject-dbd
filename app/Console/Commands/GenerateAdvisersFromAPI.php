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
    public function handle(): void
    {
        $token = Cache::get("datahub_access_token", function () {
           return $this->setAccessToken();
        });

        $response = Http::withHeader("Authorization", $token)->get(config('datahub.url') . "api/advisers?is_enabled=true&adviser_hub_access=true");
        $advisers = collect($response->json()['data'])->filter(function ($info, $key) {
            return count(User::where('email', $info['email'])->get()) === 0;
        })->map(function ($adviser, $key) {
            foreach($adviser['io_ids'] as $i){
                $io_id = [$i['label'] => $i['value']];
                break;
            }

            $adviser_email = $adviser['email'];
            $temporary_pw = Str::password(10, true, true, false, false);
            $user = new User();
            $user->name = $adviser['name'];
            $user->email = $adviser_email;
            $user->password = bcrypt($temporary_pw);
            $user->io_id = $io_id[$i['label']];
            $user->profile_photo_path = $adviser['profile_pic_url'];
            $user->has_temporary_password = true;
            $user->save();

            $user->notify(new AdviserAccountCreated([
                "temp_password" => $temporary_pw
            ]));
        });

        ray($advisers)->red();

    }
}
