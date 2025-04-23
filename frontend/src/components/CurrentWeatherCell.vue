<template>
  <td class="text-sm text-gray-700 dark:text-gray-200 px-6 py-4 align-top">
    <div class="flex items-start gap-3" v-if="!weather">
      <em>Unable to load weather at this time.</em>
    </div>
    <div v-else>
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
      <div class="text-gray-500 dark:text-gray-400 text-xs mt-2">
        <em>Last Updated: {{ formatLocalDateTime(last_updated) }}</em>
      </div>
    </div>
  </td>
</template>

<script>
export default {
  props: {
    weather: {
      type: Object,
      required: true
    },
    last_updated: {
      type: String,
      required: true,
    }
  },
  methods: {
    kelvinToF(k) {
      return k ? Math.round((k - 273.15) * 9 / 5 + 32) : '—';
    },
    formatLocalDateTime (isoString) {
      if (!isoString) {
        return '—';
      }

      const date = new Date(isoString);

      // Quick localization date function
      return date.toLocaleString(undefined, {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      });
    }
  }
}
</script>
