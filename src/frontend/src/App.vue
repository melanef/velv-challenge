<template>
  <div class="min-h-full">
    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Servers</h1>
      </div>
    </header>
    <main>
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <clip-loader v-if="isLoading" :loading="isLoading" />
        <div v-if="!isLoading">
          <div>
            <div class="md:grid md:grid-cols-3 md:gap-6">
              <div class="mt-5 md:col-span-3 md:mt-0">
                <div class="shadow sm:overflow-hidden sm:rounded-md">
                  <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                    <h2 class="text-xl font-bold tracking-tight text-gray-900">Filters</h2>

                    <div>
                      <label for="storage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Storage</label>
                      <MultiRangeSlider
                        base-class-name="multi-range-slider"
                        :min="0"
                        :max="options.hdd.length - 1"
                        :step="1"
                        :label="true"
                        :minValue="!!filters.hdd_min ? filters.hdd_min : 0"
                        :maxValue="!!filters.hdd_max ? filters.hdd_max : (options.hdd.length - 1)"
                        :labels="options.hdd"
                        @input="updateHddSize"
                      />
                    </div>

                    <div class="grid grid-cols-3 gap-6">
                      <div class="col-span-3 sm:col-span-2">
                        <fieldset>
                          <legend class="sr-only">RAM</legend>
                          <div class="block text-sm font-medium text-gray-700" aria-hidden="true">RAM</div>
                          <div class="mt-4 space-y-4">
                            <div class="flex flex-wrap">
                              <div v-for="(option, i) of options.ram_options" :key="i" class="flex items-start mb-5 mr-10 w-40">
                                <div class="flex h-5 items-center">
                                  <input
                                    v-model="filters.ram_options"
                                    :id="`ram_options-${option}`"
                                    :value="option"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                  >
                                </div>
                                <div class="ml-3 text-sm">
                                  <label
                                    :for="`ram_options-${option}`"
                                    v-text="option"
                                    class="font-medium text-gray-700"
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                      </div>
                    </div>

                    <div class="md:grid md:grid-cols-4 md:gap-6">
                      <div class="md:col-span-2">
                        <label for="hdd_type" class="block text-sm font-medium text-gray-700">Harddisk Type</label>
                        <select
                          v-model="filters.hdd_type"
                          id="hdd_type"
                          class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        >
                          <option />
                          <option
                            v-for="(option, i) of options.hdd_type"
                            :key="i"
                            :value="option"
                            v-text="option"
                          />
                        </select>
                      </div>

                      <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <select
                          v-model="filters.location"
                          id="location"
                          class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        >
                          <option />
                          <option
                            v-for="(option, i) of options.location"
                            :key="i"
                            :value="option"
                            v-text="option"
                          />
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <button
                      @click="fetch"
                      type="submit"
                      class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                      Search
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-10 shadow sm:overflow-hidden sm:rounded-md">
            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
              <table class="w-full">
                <thead>
                  <tr class="bg-gray-200">
                    <th class="text-left">Model</th>
                    <th class="text-left">RAM</th>
                    <th class="text-left">HDD</th>
                    <th class="text-left">Location</th>
                    <th class="text-left">Price</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(server, i) of servers"
                    :key="i"
                    :class="i % 2 ? 'bg-gray-100' : ''"
                  >
                    <td class="text-left" v-html="server.model" />
                    <td class="text-left" v-html="server.ram.size.amount + server.ram.size.unit + server.ram.type" />
                    <td class="text-left" v-html="server.hdd.disks + 'x' + server.hdd.size.amount + server.hdd.size.unit + server.hdd.type" />
                    <td class="text-left" v-html="server.location" />
                    <td class="text-right" v-html="server.price.symbol + Math.floor(server.price.value / Math.pow(10, server.price.decimalPlaces)) + '.' + String(server.price.value % Math.pow(10, server.price.decimalPlaces)).padStart(2, '0')" />
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import MultiRangeSlider from "multi-range-slider-vue";

const HDD_OPTIONS = [
  '0',
  '250GB',
  '500GB',
  '1TB',
  '2TB',
  '3TB',
  '4TB',
  '8TB',
  '12TB',
  '24TB',
  '48TB',
  '72TB',
];

const RAM_OPTIONS = [
  '2GB',
  '4GB',
  '8GB',
  '12GB',
  '16GB',
  '24GB',
  '32GB',
  '48GB',
  '64GB',
  '96GB',
];

const HDD_TYPE_OPTIONS = [
  'SAS',
  'SATA2',
  'SSD',
];

const LOCATION_OPTIONS = [
  'AmsterdamAMS-01',
  'DallasDAL-10',
  'FrankfurtFRA-10',
  'Hong KongHKG-10',
  'San FranciscoSFO-12',
  'SingaporeSIN-11',
  'Washington D.C.WDC-01',
];

export default {
  components: {
    ClipLoader,
    MultiRangeSlider,
  },
  data() {
    return {
      isLoading: false,

      filters: {
        hdd_type: null,
        location: null,
        ram_options: [],
        hdd_min: null,
        hdd_max: null,
      },

      options: {
        hdd: HDD_OPTIONS,
        ram_options: RAM_OPTIONS,
        hdd_type: HDD_TYPE_OPTIONS,
        location: LOCATION_OPTIONS,
      },

      servers: [],
    }
  },
  created() {
    this.fetch();
  },
  methods: {
    updateHddSize(e) {
      this.filters.hdd_min = e.minValue;
      this.filters.hdd_max = e.maxValue;
    },
    fetch() {
      this.isLoading = true;

      const params = {}
      for (const key in this.filters) {
        if (!!this.filters[key]) {
          if (key === 'hdd_min' || key === 'hdd_max') {
            params[key] = HDD_OPTIONS[this.filters[key]]
          } else {
            params[key] = this.filters[key]
          }
        }
      }

      return axios.get('http://localhost:8000/servers', {params})
        .then(function (response) {
          return response.data
      }).then(servers => {
          this.servers = []
          this.$nextTick(() => {
            this.servers = servers;
            this.isLoading = false;
          });
        });
    }
  }
}
</script>
