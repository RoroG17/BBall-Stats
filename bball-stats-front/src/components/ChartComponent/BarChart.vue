<template>
    <q-card class="q-pa-md shadow-2 rounded-borders bg-grey-1">
      <!-- En-tête avec icône et titre -->
      <q-card-section class="row items-center q-gutter-sm q-pb-none">
        <q-icon name="bar_chart" size="md" color="primary" />
        <div class="text-h6 text-primary">{{ props.titre }}</div>
      </q-card-section>
  
      <!-- Ligne séparatrice -->
      <q-separator color="primary" spaced />
  
      <!-- Section du graphique -->
      <q-card-section class="q-pt-none">
        <div class="q-pa-sm" style="height: 350px;">
          <canvas ref="barChartCanvas" style="width: 100%; height: 100%;" />
        </div>
      </q-card-section>
    </q-card>
  </template>
  

<script setup lang="ts">
import {
  Chart,
  registerables,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { ref, watch, onMounted } from 'vue'

Chart.register(...registerables, BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend)

const props = defineProps<{
  titre: string,
  labels: string[],
  data: number[]
}>()


const barChartCanvas = ref()
  let chartInstance : Chart 
  
  const createChart = () => {
  if (chartInstance) {
    chartInstance.destroy()
  }

    chartInstance = new Chart(barChartCanvas.value, {
      type: 'bar',
      data: {
        labels : props.labels,
        datasets: [
          {
            label: 'Stats',
            data: props.data,
            backgroundColor: 'rgba(255, 0, 0, 0.6)',
            borderColor: 'rgba(255, 0, 0, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            min: 0,
            max: 100,
            ticks: {
              stepSize: 10
            },
            title: {
              display: true,
              text: 'Stats'
            }
          }
        }
      }
    })
}

onMounted(() => {
  createChart()
})

// Recrée le graphique si les données changent
watch(() => [props.labels, props.data], () => {
  createChart()
})
</script>