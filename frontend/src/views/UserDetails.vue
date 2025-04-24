<template>
  <div class="max-w-3xl mx-auto px-4 py-8">


    <h1 class="text-2xl font-bold mb-4">
      Weather Forecast for {{ user?.name || 'User ' + userId }}
    </h1>

    <!-- Loading Spinner -->
    <div v-if="loading" class="flex items-center text-gray-600 dark:text-gray-400">
      <i class="fa fa-spinner fa-fw fa-spin mr-2"></i>
      Loading data...
    </div>

    <div v-else>
      <h2 class="text-lg font-semibold mb-1">Current Weather</h2>
      <div class="text-gray-500 dark:text-gray-400 text-xs mt-0 mb-3">
        <em>Last Updated: {{ formatDate(user.current_weather_last_updated_at) }}</em>
      </div>

      <div v-if="weather">
        <div class="flex items-start gap-3">
          <!-- Icon: Don't love these, but we're going by what they're giving us. -->
          <img
            :src="`https://openweathermap.org/img/wn/${weather?.weather?.[0]?.icon}@2x.png`"
            :alt="weather?.weather?.[0]?.description || 'weather icon'"
            class="w-10 h-10"
          />

          <div>
            <!-- Main Weather -->
            <div class="font-semibold">
              {{ weather?.weather?.[0]?.main || '—' }} &mdash; {{ weather?.weather?.[0]?.description || '—' }}
            </div>

            <!-- Temp + Feels Like -->
            <div class="text-gray-500 dark:text-gray-400">
              Temp: {{ kelvinToF(weather?.main?.temp) }}°F, Feels like: {{ kelvinToF(weather?.main?.feels_like) }}°F
            </div>

            <!-- Wind -->
            <div class="text-gray-500 dark:text-gray-400">
              Wind: {{ weather?.wind?.speed ?? '—' }} m/s, Gusts: {{ weather?.wind?.gust ?? '—' }} m/s
            </div>

            <!-- Rain -->
            <div class="text-gray-500 dark:text-gray-400">
              Rain (1h): {{ weather?.rain?.['1h'] ?? 0 }} mm
            </div>
          </div>
        </div>
      </div>
      <!-- No Forecast -->
      <div v-else class="text-gray-500 dark:text-gray-400">
        Current weather not available for this user.
      </div>


      <hr class="my-5" />

      <h2 class="text-lg font-semibold mb-1">5-Day Forecast (Every 3 Hours)</h2>

      <div class="text-gray-500 dark:text-gray-400 text-xs mt-0 mb-3">
          <em>Last Updated: {{ formatDate(user.forecast_last_updated_at) }}</em>
        </div>

      <!-- Forecast List -->
      <div v-if="forecast?.list?.length">
        <ul class="space-y-2">
          <li
            v-for="entry in forecast.list"
            :key="entry.dt"
            class="bg-white dark:bg-gray-800 shadow rounded p-4 border border-gray-100"
          >
            <div class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ formatDate(entry.dt_txt) }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                  {{ entry.weather[0].main }} — {{ entry.weather[0].description }}
                </div>
              </div>
              <div class="text-right text-sm text-gray-700 dark:text-gray-300">
                <div>Temp: {{ kelvinToF(entry.main.temp) }}°F</div>
                <div>Feels like: {{ kelvinToF(entry.main.feels_like) }}°F</div>
                <div>Wind: {{ entry.wind.speed }} m/s</div>
                <div v-if="entry.rain?.['3h']">Rain: {{ entry.rain['3h'] }} mm</div>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- No Forecast -->
      <div v-else class="text-gray-500 dark:text-gray-400">
        Forecast data not available for this user.
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['id'],
  data() {
    return {
      userId: this.id,
      user: null,
      forecast: null,
      weather: null,
      loading: true,
    }
  },
  async mounted() {
    const res = await fetch(`http://localhost/api/users/${this.userId}`);
    const data = await res.json();
    this.user = data;
    this.forecast = this.user.forecast;
    this.weather = this.user.current_weather;
    this.loading = false
  },
  methods: {
    kelvinToF(k) {
      return k ? Math.round((k - 273.15) * 9 / 5 + 32) : '—'
    },
    formatDate(datetime) {
      if (!datetime) {
        return '—';
      }

      const options = {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      }
      return new Date(datetime).toLocaleString(undefined, options)
    }
  }
}
</script>
