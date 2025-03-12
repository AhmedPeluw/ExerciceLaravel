<template>

  <div class="bg-gray-200 h-screen w-fullbg-gray-200 h-screen w-full">


    <div class="  flex justify-center items-center">
    

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:py-24 lg:px-8">
          <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl ">Statistiques des Paiements</h2>
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mt-4">
              <div class="bg-white overflow-hidden shadow sm:rounded-lg ">
                  <div class="px-4 py-5 sm:p-6">
                      <dl>
                          <dt class="text-sm leading-5 font-medium text-gray-500 truncate ">Total Transactions</dt>
                          <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600">{{ stats.total_transactions }}</dd>
                      </dl>
                  </div>
              </div>
              <div class="bg-white overflow-hidden shadow sm:rounded-lg ">
                  <div class="px-4 py-5 sm:p-6">
                      <dl>
                          <dt class="text-sm leading-5 font-medium text-gray-500 truncate ">Montant Total Encaissé</dt>
                          <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600">{{ stats.total_amount != null ? stats.total_amount : 0 }} EUR
                          </dd>
                      </dl>
                  </div>
              </div>
              <div class="bg-white overflow-hidden shadow sm:rounded-lg ">
                  <div class="px-4 py-5 sm:p-6">
                      <dl>
                          <dt class="text-sm leading-5 font-medium text-gray-500 truncate">Moyenne des Paiements</dt>
                          <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 ">{{ stats.average_payment != null ? stats.average_payment : 0 }} EUR</dd>
                      </dl>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Include TransactionReport Component -->
  <div class="bg-gray-200 h-screen w-full">
    <h2 class="text-2xl font-bold tracking-tight p-8">Graphiques des transactions</h2>
    <div v-if="stats.status_distribution && stats.status_distribution.length > 0">
      <TransactionReport :stats="stats" />
    </div>
    <div v-if="!stats.status_distribution" class="text-center text-gray-500 p-6"> <!-- Show a loader -->
      <p>Chargement des statistiques...</p>
    </div>
    <div v-if="stats.status_distribution && stats.status_distribution.length == 0" class="text-center text-gray-500 p-6"> <!-- Show a loader -->
      <p>Il n'y a pas de données</p>
    </div>
  </div>

</div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';
import apiClient from '@/services/apiClient';
import echo from '@/services/echo';
import TransactionReport from '@/components/TransactionReport.vue';


Chart.register(...registerables);

const stats = ref({});
const notifications = ref([]);

const fetchStats = async () => {
  const response = await apiClient.get('/transactions/report');
  stats.value = response.data;
};

onMounted(() => {
  fetchStats()
  console.log("Listening ...");

  // Listen to all events
  echo.channel('payments')
      .subscribed(() => {
          console.log("Successfully subscribed");
      })
      .listen('.NewPaymentNotification', (event) => {
          console.log("Received event:", event);
          notifications.value.push(event);
      })
      .error((error) => {
          console.error("Pusher subscription error:", error);
      });
});
</script>

<style scoped>
.stats p {
  font-size: 18px;
  margin: 10px 0;
}
</style>
