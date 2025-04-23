<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FetchWeatherForUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'weather:users';

    /**
     * The console command description.
     */
    protected $description = 'Fetch weather forecast for users using OpenWeather API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $apiKey = config('services.openweather_api.key');

        if (! $apiKey) {
            $this->error('Openweather API Key not set.');

            return;
        }

        User::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->each(fn ($user) => \App\Jobs\FetchWeatherForUser::dispatch($user));
    }
}
