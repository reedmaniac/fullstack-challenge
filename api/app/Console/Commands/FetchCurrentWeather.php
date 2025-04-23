<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchCurrentWeather extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'weather:current';

    /**
     * The console command description.
     */
    protected $description = 'Fetch current weather for users using OpenWeather API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $apiKey = config('services.openweather_api.key');

        if (!$apiKey) {
            $this->error('Openweather API Key not set.');
            return;
        }

        $users = User::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        foreach ($users as $user) {
            // Should never check more than every 15 minutes
            if ($user->current_weather_last_checked_at &&
                $user->current_weather_last_checked_at->gt(now()->subMinutes(15))) {
                continue;
            }

            $this->info(sprintf('Fetching current weather for %s', $user->email));

            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $user->latitude,
                'lon' => $user->longitude,
                'appid' => $apiKey,
            ]);

            $user->current_weather_last_checked_at = now();

            if ($response->ok() && $response->json('cod') == 200) {
                $user->current_weather = $response->body();
                $user->current_weather_last_updated_at = now();
            } else {
                Log::error('Weather fetch failed', [
                    'user_id' => $user->id,
                    'lat' => $user->latitude,
                    'lon' => $user->longitude,
                    'response' => $response->json(),
                ]);
            }

            $user->save();
        }
    }
}
