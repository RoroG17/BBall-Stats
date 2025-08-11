<template>
  <q-card class="q-pa-md shadow-2 rounded-borders bg-grey-1">
    <!-- Title bar -->
    <q-card-section class="row items-center q-pb-sm">
      <q-icon name="insights" size="md" color="primary" class="q-mr-sm" />
      <div class="text-h6 text-primary">Performance du joueur</div>
    </q-card-section>

    <!-- Chart -->
    <q-card-section class="q-pt-none">
      <canvas ref="chartCanvas" style="min-height: 500px;"></canvas>
    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import {
  Chart,
  ScatterController,
  PointElement,
  LinearScale,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

// Register only what we need
Chart.register(ScatterController, PointElement, LinearScale, Title, Tooltip, Legend)

const props = withDefaults(defineProps<{
  matches: { minutes: number; data: number }[]
  stat?: string
}>(), {
  stat: 'Points'
})

const chartCanvas = ref<HTMLCanvasElement | null>(null)
let chartInstance: Chart | null = null

const renderChart = () => {
  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(chartCanvas.value!, {
    type: 'scatter',
    data: {
      datasets: [
        {
          label: `${props.stat} par match`,
          data: props.matches.map(m => ({ x: m.minutes, y: m.data })),
          backgroundColor: 'rgba(255, 0, 0, 0.8)', // blue points
          borderColor: 'rgba(255, 0, 0, 1)',
          pointRadius: 6,
          pointHoverRadius: 8,
          pointStyle: 'circle',
          borderWidth: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 800,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: {
          labels: {
            color: 'rgba(255,0,0,1)',
            font: { size: 14, weight: 'bold' }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(255,0,0,0.8)',
          titleFont: { size: 14, weight: 'bold' },
          bodyFont: { size: 13 },
          callbacks: {
            label: (context) =>
              `Minutes: ${context.parsed.x} — ${props.stat}: ${context.parsed.y}`
          }
        },
        title: {
          display: false
        }
      },
      scales: {
        x: {
          grid: { color: 'rgba(200, 200, 200, 0.3)' },
          ticks: { color: '#555' },
          title: { display: true, text: 'Minutes jouées', color: '#333', font: { weight: 'bold' } },
          min: 0,
          max: 30
        },
        y: {
          grid: { color: 'rgba(200, 200, 200, 0.3)' },
          title: { display: true, text: props.stat, color: '#333', font: { weight: 'bold' } },
          beginAtZero: true,
          min: 0,
          max: 20
        }
      }
    }
  })
}

onMounted(renderChart)
onBeforeUnmount(() => {
  if (chartInstance) chartInstance.destroy()
})

watch(() => props.matches, renderChart, { deep: true })
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
</style>
