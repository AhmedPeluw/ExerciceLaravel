<template>
  
  <div class="grid grid-cols-2 m-8">
    <div class="font-sans leading-normal tracking-normal mt-20">
      <h1>Les paiements par Mois</h1>
      <canvas ref="chartCanvas"></canvas>
    </div>

    <div class="font-sans leading-normal tracking-normal mt-20">
      <h1>Les paiements par Statut</h1>
      <canvas ref="chartCanvasStatus"></canvas>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  stats: Object // ✅ Correctly define props
});

const chartCanvas = ref(null);
const chartCanvasStatus = ref(null);

const renderChart = () => {
  if (!props.stats || !props.stats.monthly_transactions) return; // ✅ Ensure data exists

  new Chart(chartCanvas.value, {
    type: 'bar',
    data: {
      labels: props.stats.monthly_transactions.map(item => item.month),
      datasets: [
        {
          label: 'Transactions par Mois',
          data: props.stats.monthly_transactions.map(item => item.count),
          backgroundColor: 'rgba(75, 192, 192, 0.6)',
        }
      ]
    },
    options: { responsive: true }
  });
};

const renderChartStatus = () => {
  if (!props.stats || !props.stats.status_distribution) return; // ✅ Ensure data exists

  const allStatuses = ["pending", "paid", "expired"];
  const statusMap = {};

  props.stats.status_distribution.forEach(item => {
    statusMap[item.status] = item.count;
  });

  const dataValues = allStatuses.map(status => statusMap[status] || 0);

  new Chart(chartCanvasStatus.value, {
    type: 'bar',
    data: {
      labels: allStatuses,
      datasets: [
        {
          label: 'Transactions par Statut',
          data: dataValues,
          backgroundColor: ['#F59E0B', '#10B981', '#EF4444'],
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true, position: 'top' }
      },
      scales: { y: { beginAtZero: true } }
    }
  });
};

onMounted(() => {
  renderChart();
  renderChartStatus();
});
</script>
