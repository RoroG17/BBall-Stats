<template>

    <q-card class="q-pa-md shadow-2 rounded-borders bg-grey-1">
        <!-- En-tête avec icône et titre -->
        <q-card-section class="row items-center q-gutter-sm q-pb-none">
          <q-icon name="bar_chart" size="md" color="primary" />
          <div class="text-h6 text-primary">Moyenne par match</div>
        </q-card-section>
  
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
import { onMounted, ref, watch } from 'vue'
import {
    Chart,
    registerables
  } from 'chart.js'
import type { StatMatch } from '../types/StatMatchType'
  
  // Enregistrement de Chart.js
  Chart.register(...registerables)

  const props = defineProps<{
    periode: string,
    data: StatMatch[]
    }>()
  // Référence au canvas
  const barChartCanvas = ref<HTMLCanvasElement | null>(null)
  let chartInstance: Chart | null = null
  
  // Données (à remplacer par les vraies données si besoin)
  const labels = [
    'Points', 'Passe décisives', 'Rebonds', 'Rebonds Off',
    'Rebonds Def', 'Interceptions', 'Contres', 'Ballons Perdus', 'Fautes'
  ]

function makeData() {
  const nb = 11
    let points = 0
    let passe_d = 0
    let reb_off = 0
    let reb_def = 0
    let int = 0
    let contre = 0
    let bp = 0
    let faute = 0
    
    props.data.forEach((stat: StatMatch) => {
      points += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_tirs_2pts_reussis * 2 + stat?.q1_tirs_3pts_reussis * 3 + stat?.q1_lancers_francs_reussis) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_tirs_2pts_reussis * 2 + stat?.q2_tirs_3pts_reussis * 3 + stat?.q2_lancers_francs_reussis) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_tirs_2pts_reussis * 2 + stat?.q3_tirs_3pts_reussis * 3 + stat?.q3_lancers_francs_reussis) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_tirs_2pts_reussis * 2 + stat?.q4_tirs_3pts_reussis * 3 + stat?.q4_lancers_francs_reussis) : 0)
              
      passe_d += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_passes_decisives) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_passes_decisives) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_passes_decisives) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_passes_decisives) : 0)
      
      reb_off += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_rebonds_offensifs) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_rebonds_offensifs) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_rebonds_offensifs) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_rebonds_offensifs) : 0)

      reb_def += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_rebonds_defensifs) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_rebonds_defensifs) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_rebonds_defensifs) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_rebonds_defensifs) : 0)

      int += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_interceptions) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_interceptions) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_interceptions) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_interceptions) : 0)

      contre += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_contres) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_contres) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_contres) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_contres) : 0)

      bp += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_ballons_perdus) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_ballons_perdus) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_ballons_perdus) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_ballons_perdus) : 0)

      faute += (props.periode === 'Match Complet' || props.periode === '1QT' ? (stat?.q1_fautes) : 0)
              + (props.periode === 'Match Complet' || props.periode === '2QT' ? (stat?.q2_fautes) : 0)
              + (props.periode === 'Match Complet' || props.periode === '3QT' ? (stat?.q3_fautes) : 0)
              + (props.periode === 'Match Complet' || props.periode === '4QT' ? (stat?.q4_fautes) : 0)
    
    })

    return [points / nb , passe_d / nb ,(reb_off + reb_def) / nb, reb_off / nb, reb_def / nb, int / nb, contre / nb, bp / nb, faute / nb]
}

const createChart = () => {
    if (chartInstance) {
      chartInstance.destroy()
    }
  
    if (!barChartCanvas.value) {
      console.warn('Canvas non monté')
      return
    }

    const data = makeData()
    chartInstance = new Chart(barChartCanvas.value, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Stats',
            data: data, // Remplace par les vraies données
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
    console.log(props.data)
    createChart()
  })

  watch(() => [props.periode], () => {
    createChart()
  })
</script>