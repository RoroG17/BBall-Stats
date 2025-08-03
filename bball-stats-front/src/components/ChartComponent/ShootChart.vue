<template>
    <q-card class="q-pa-md q-mb-md shadow-3 bg-grey-1" style="border-radius: 16px; max-width: 500px; margin: auto;">
      <q-card-section class="row items-center justify-between">
        <div class="text-h6 text-primary text-weight-bold">
          {{ props.titre }}
        </div>
        <q-icon name="pie_chart" size="md" class="text-primary" />
      </q-card-section>
  
      <q-separator color="primary" />
  
      <q-card-section class="q-pa-sm">
        <div class="q-pa-md">
          <canvas ref="shootChart" style="max-height: 400px; width: 100%;" />
        </div>
      </q-card-section>
    </q-card>
  </template>
  

<script setup lang="ts">
import { Chart } from 'chart.js';
import { onMounted, ref, watch } from 'vue';

const props = defineProps<{
  titre: string,
  labels: string[],
  data: number[]
}>()

const shootChart = ref()
let shootInstance : Chart 

const renderChart = () => {
    console.log(props)
  if (shootInstance) {
    shootInstance.destroy()
  }

    shootInstance = new Chart(shootChart.value, {
      type: 'pie',
      data: {
        labels: props.labels,
        datasets: [{
          label: 'Shoot à 2pts',
          data: props.data,
          backgroundColor: [
            'rgba(0, 255, 0, 0.7)',
            'rgba(255, 0, 0, 0.7)',
          ],
          borderColor: [
            'rgba(0, 255, 0, 1)',
            'rgba(255, 0, 0, 1)',
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right' // ou 'top', 'bottom', etc.
          }
        }
      }
    })
}

onMounted(() => {
    renderChart()
})

watch(() => [props.labels, props.data], () => {
  renderChart()
})

</script>