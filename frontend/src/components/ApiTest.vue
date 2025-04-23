<script>
import CurrentWeatherCell from './CurrentWeatherCell.vue';

export default {
  data: () => ({
    apiResponse: null
  }),

  components: { CurrentWeatherCell },

  mounted() {
    this.fetchData()
  },

  methods: {
    async fetchData(page=1) {
      const url = `http://localhost/api/users?page=${page}`;
      this.apiResponse = await (await fetch(url)).json()
    },
    prevPage() {
      if (this.hasPrev) {
        this.fetchData(this.currentPage - 1);
      }
    },
    nextPage() {
      if (this.hasNext) {
        this.fetchData(this.currentPage + 1);
      }
    },
    kelvinToF(kelvin) {
      return kelvin ? Math.round((kelvin - 273.15) * 9/5 + 32) : '—';
    }
  },
  computed: {
    from() {
      return this.apiResponse?.meta?.from || 0
    },
    to() {
      return this.apiResponse?.meta?.to || 0
    },
    total() {
      return this.apiResponse?.meta?.total || 0
    },
    currentPage() {
      return this.apiResponse?.meta?.current_page || 1
    },
    lastPage() {
      return this.apiResponse?.meta?.last_page || 1
    },
    hasPrev() {
      return this.currentPage > 1
    },
    hasNext() {
      return this.currentPage < this.lastPage
    },
  },
}
</script>

<template>

  <div v-if="!apiResponse">
    <i class="fa fa-spinner fa-fw margin-right-md fa-spin"></i>
    Loading data...
  </div>
  <div v-else>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border border-gray-200 shadow">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Latitude, Longitude
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Current Weather
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                  v-for="(row) in apiResponse.data" :key="row.id"
                  class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200"
                >
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top">
                        <a href="" class="hover:underline">{{row.name}} <i class="fa-solid fa-fw fa-arrow-up-right-from-square"></i></a>
                    </th>
                    <td class="px-6 py-4 align-top">
                        {{ row.email }}
                    </td>
                    <td class="px-6 py-4 align-top">
                         {{ row.latitude }}, {{ row.longitude }}
                    </td>
                    <CurrentWeatherCell :weather="row.current_weather" :last_updated="row.current_weather_last_updated_at" />
                </tr>
            </tbody>
        </table>

        <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">
              Showing
              <span class="font-semibold text-gray-900 dark:text-white">{{ from }}-{{ to }}</span>
              of
              <span class="font-semibold text-gray-900 dark:text-white">{{ total }}</span>
            </span>
            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
              <li>
                  <a
                    href="#"
                    @click="prevPage"
                    :disabled="!hasPrev"
                    :class="{
                      'hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white text-gray-800' : hasPrev,
                      'cursor-not-allowed text-gray-500' : !hasPrev
                    }"
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight font-medium bg-white border border-gray-300 rounded-s-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                  >Previous</a>
              </li>
              <li>
                <a
                  href="#"
                  @click="nextPage"
                  :disabled="!hasNext"
                  :class="{
                      'hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white text-gray-800' : hasNext,
                      'cursor-not-allowed text-gray-500' : !hasNext
                    }"
                  class="flex items-center justify-center px-3 h-8 leading-tight font-medium bg-white border border-gray-300 rounded-e-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                >Next</a>
              </li>
          </ul>
        </nav>
    </div>

  </div>
</template>
