<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchWeatherForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Constructor!
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the Job
     */
    public function handle(): void
    {
        $this->apiKey = config('services.openweather_api.key');

        if (! $this->apiKey) {
            return;
        }

        $this->fetchCurrentWeather();
        $this->fetchForecast();
    }

    /**
     * Fetch the Current Weather for the User
     *
     * @link https://openweathermap.org/current#one
     * @example https://api.openweathermap.org/data/2.5/weather?lat={latitude}&lon={longitude}&appid={apiKey}
     *
     * @return void
     */
    public function fetchCurrentWeather(): void
    {
        if ($this->user->current_weather_last_checked_at &&
            $this->user->current_weather_last_checked_at->gt(now()->subMinutes(15))) {
            return;
        }

        Log::info(sprintf('Fetching current weather for %s', $this->user->email));

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $this->user->latitude,
            'lon' => $this->user->longitude,
            'appid' => $this->apiKey,
        ]);

        $this->user->current_weather_last_checked_at = now();

        if ($response->ok() && $response->json('cod') == 200) {
            $this->user->current_weather = $response->body();
            $this->user->current_weather_last_updated_at = now();
        } else {
            Log::error('Weather fetch failed', [
                'user_id' => $this->user->id,
                'lat' => $this->user->latitude,
                'lon' => $this->user->longitude,
                'response' => $response->json(),
            ]);
        }

        $this->user->save();
    }

    /**
     * Fetch Five Day Forecast for the User
     *
     * @link https://openweathermap.org/forecast5#geo5
     * @example https://api.openweathermap.org/data/2.5/forecast?lat={latitude}&lon={longitude}&appid={apiKey}
     *
     * @return void
     */
    public function fetchForecast(): void
    {
        Log::info(sprintf('Fetching forecast for %s', $this->user->email));

        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'lat' => $this->user->latitude,
            'lon' => $this->user->longitude,
            'appid' => $this->apiKey,
        ]);

        $this->user->forecast_last_checked_at = now();

        if ($response->ok() && $response->json('cod') == 200) {
            $this->user->forecast = $response->body();
            $this->user->forecast_last_updated_at = now();
        } else {
            Log::error('Forecast fetch failed', [
                'user_id' => $this->user->id,
                'lat' => $this->user->latitude,
                'lon' => $this->user->longitude,
                'response' => $response->json(),
            ]);
        }

        $this->user->save();
    }
}
