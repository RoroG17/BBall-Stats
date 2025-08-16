<template>
    <div>
      <h1>Equipes</h1>
  
      <q-select
        v-model="periode"
        :options="optionPeriode"
        label="Période"
        outlined
        class="q-mt-md q-mb-md q-ml-xl"
        style="max-width: 200px;"
      />

      <moy-equipe-chart :periode="periode" :data="donnee" />
    </div>
  </template>
  
  <script lang="ts" setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import MoyEquipeChart from '../components/ChartComponent/MoyEquipeChart.vue'
  import {
    Chart,
    registerables
  } from 'chart.js'
  
  // Enregistrement de Chart.js
  Chart.register(...registerables)
  
  // Données de période
  const periode = ref('Match Complet')
  const optionPeriode = ref(["Match Complet", "1QT", "2QT", "3QT", "4QT"])
  
  
  // Appel de l'API au montage du composant
  const donnee = ref([])
  const fetchGames = async () => {
    try {
      const response = await axios.get('http://localhost:8000/stats/1')
      donnee.value = response.data
      console.log('Données des matchs:', response.data)
  
      // ➕ Tu peux adapter ici pour remplir le graphique dynamiquement
  
    } catch (error) {
      console.error('Erreur lors du fetch des matchs :', error)
    }
  }
  
  // Lifecycle
 onMounted(async () => {
    await fetchGames()
  })
  </script>
  