<?php

namespace App\Console\Commands;

use App\Concerns\InterractsWithDataHub;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

        $response = Http::withHeader("Authorization", $token)->get(config('datahub.url') . "api/advisers");
        dd($response->json());
    }
}
