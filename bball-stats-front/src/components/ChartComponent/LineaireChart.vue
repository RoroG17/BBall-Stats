<template>
    <q-card class="q-pa-md shadow-2 rounded-borders bg-grey-1">
      <!-- En-tête avec icône et titre -->
      <q-card-section class="row items-center q-gutter-sm q-pb-none">
        <q-icon name="bar_chart" size="md" color="primary" />
        <div class="text-h6 text-primary">Stats Globales de la saison</div>
      </q-card-section>
  
      <q-separator color="primary" spaced />
  
      <!-- Section du graphique -->
      <q-card-section class="q-pt-none">
        <div class="q-pa-sm" style="height: 500px;">
          <canvas ref="chartCanvas" style="width: 100%; height: 100%;" />
        </div>
      </q-card-section>
    </q-card>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import { Chart, registerables } from 'chart.js'
  
  // Enregistre tous les composants nécessaires de Chart.js
  Chart.register(...registerables)
  
  // Définition du type des données
  type GlobalStats = {
    numero: number
    Points: number
    Passe_decisives: number
    Rebonds_offensifs: number
    Rebonds_defensifs: number
    Interceptions: number
    Contres: number
    Ballons_perdus: number
    Fautes: number
  }
  
  // Typage des props
  const props = defineProps<{
    data: GlobalStats[]
  }>()
  
  // Référence vers le canvas
  const chartCanvas = ref<HTMLCanvasElement | null>(null)
  
  onMounted(() => {
    //console.log(props.data)
    if (!chartCanvas.value) return
  
    // Préparation des labels et datasets
    const labels = props.data.map(item => `Match ${item.numero}`)
    const datasets = [
      {
        label: 'Points',
        data: props.data.map(item => item.Points),
        borderColor: '#ff0000',
        backgroundColor: 'transparent',
      },
      {
        label: 'Passes décisives',
        data: props.data.map(item => item.Passe_decisives),
        borderColor: '#0000ff',
        backgroundColor: 'transparent',
      },
      {
        label: 'Rebonds Offensifs',
        data: props.data.map(item => item.Rebonds_offensifs),
        borderColor: '#00ff00',
        backgroundColor: 'transparent',
      },
      {
        label: 'Rebonds Défensifs',
        data: props.data.map(item => item.Rebonds_defensifs),
        borderColor: '#006b00',
        backgroundColor: 'transparent',
      },
      {
        label: 'Interceptions',
        data: props.data.map(item => item.Interceptions),
        borderColor: '#00ffff',
        backgroundColor: 'transparent',
      },
      {
        label: 'Contres',
        data: props.data.map(item => item.Contres),
        borderColor: '#ff00ff',
        backgroundColor: 'transparent',
      },
      {
        label: 'Ballons Perdus',
        data: props.data.map(item => item.Ballons_perdus),
        borderColor: '#ffff00',
        backgroundColor: 'transparent',
      },
      {
        label: 'Fautes',
        data: props.data.map(item => item.Fautes),
        borderColor: '#000000',
        backgroundColor: 'transparent',
      }
    ]
  
    // Création du graphique
    new Chart(chartCanvas.value, {
      type: 'line',
      data: {
        labels,
        datasets
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            min: 0,
            max: 80,
            ticks: {
              stepSize: 5
            }
          }
        }
      }
    })
  })
  </script>
  