<template>
    <q-select
        v-model="periode"
        :options="optionPeriode"
        label="Période"
        outlined
        class="q-mt-md q-mb-md q-ml-xl" 
        style="max-width: 200px;"
        @update:model-value="ChangePeriode"
    />
    <div class="flex flex-column items-center justify-center" >
        <q-table
            :rows="rows"
            :columns="columns"
            row-key="licence"
            flat
            bordered
            title="Statistiques Joueurs"
            :rows-per-page-options="[0]"
            :footer-props="{ 'show-first-last-page': false, 'rows-per-page-options': [] }"
            :bottom-slots="true"
            style="max-width: 95%;"
            >

            <template #header="props">
                <q-tr :props="props">
                <q-th
                    v-for="col in props.cols"
                    :key="col.name"
                    :props="props"
                    :class="col.name === 'match' ? 'sticky-col-head' : ''"
                >
                    {{ col.label }}
                </q-th>
                </q-tr>
            </template>
            <!-- Ligne colorée personnalisée -->
            <template #body="props">
                <q-tr :props="props" :class="props.rowIndex % 2 === 0 ? 'ligne-paire' : 'ligne-impaire'">
                <q-td
                    v-for="col in props.cols"
                    :key="col.name"
                    :props="props"
                    :class="col.name === 'match' ? 'sticky-col' : ''"
                >
                    {{ col.value }}
                </q-td>
                </q-tr>
            </template>

            <template #bottom-row>
                <q-tr class="ligne-total">
                    <q-td key="nom" colspan="2" class="text-weight-bold">Total</q-td>
                    <q-td v-for="col in columns.slice(2)" :key="col.name" class="text-weight-bold">
                    {{
                        typeof col.field === 'string' && col.field in totalsRow
                        ? totalsRow[col.field as keyof typeof totalsRow]
                        : ''
                    }}
                    </q-td>
                </q-tr>
            </template>
        </q-table>
    </div>

    <q-card class="q-pa-md shadow-2 rounded-borders bg-grey-1">
      <!-- En-tête avec icône et titre -->
      <q-card-section class="row items-center q-gutter-sm q-pb-none">
        <q-icon name="bar_chart" size="md" color="primary" />
        <div class="text-h6 text-primary">Statistique Moyenne</div>
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

    <div class="row q-col-gutter-md q-mt-md">
      <div class="col-12 col-md-4">
        <shoot-chart titre="Shoot à 2pts" :labels="labelsShoot" :data="dataValuesShoot" />
      </div>
      <div class="col-12 col-md-4">
        <shoot-chart titre="Shoot à 3pts" :labels="labelsShoot3pts" :data="dataValuesShoot3pts" />
      </div>
      <div class="col-12 col-md-4">
        <shoot-chart titre="Lancers Francs" :labels="labelsLF" :data="dataValuesLF" />
      </div>
    </div>

    <q-select v-model="attribut" style="max-width: 200px;" :options="listeAttribut" label="Statistique" outlined dense fill-input input-debounce="0" emit-value map-options :dropdown-icon="'arrow_drop_down'" color="primary" bg-color="white" class="q-ma-sm">
      <template v-slot:prepend>
        <q-icon name="insights" color="primary" />
      </template>
    </q-select>
    <ScatterChart :matches="matches" :stat="attribut" />
  </template>
  
  <script setup lang="ts">
  import { computed, onMounted, ref, watch } from 'vue';
  import type { QTableColumn } from 'quasar';
  import type { StatMatch } from '../types/StatMatchType';
  import ShootChart from '../ChartComponent/ShootChart.vue';
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
import ScatterChart from '../ChartComponent/ScatterChart.vue';
  
  Chart.register(...registerables, BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend)
  const props = defineProps<{ stats: StatMatch[] }>();
  
  const periode = ref('Match Complet')
  const statPeriode = ref([1,2,3,4])
  const optionPeriode = ref(["Match Complet", "1QT", "2QT", "3QT", "4QT"]);

  
  const listeAttribut : string[] = ['Points', 'Passes Déc', 'Rebonds', 'Interceptions', 'Contres', 'Ballons perdus']
  const attribut = ref('Points')

  function getPeriode(periode : string) {
    switch (periode) {
        case "1QT" :
            return [1]

        case "2QT" :
            return [2]
        
        case "3QT" :
            return [3]

        case "4QT" :
            return [4]
        
        default :
            return [1,2,3,4]
    }
  }

  const ChangePeriode = () => {
    statPeriode.value = getPeriode(periode.value)
  }

  const columns: QTableColumn[] = [
    { name: 'match', label: 'Match', field: 'match_libelle', align: 'left' },
    { name: 'minutes', label: 'Minutes', field: 'minutes', align: 'center', sortable: true },
    { name: 'points', label: 'Pts', field: 'points', align: 'center', sortable: true },
    { name: 'passes_decisives', label: 'Passes D.', field: 'passes_decisives', align: 'center', sortable: true },
    { name: 'rebonds', label: 'Rebonds', field: 'rebonds', align: 'center', sortable: true },
    { name: 'rebonds_offensifs', label: 'Reb. off.', field: 'rebonds_offensifs', align: 'center', sortable: true },
    { name: 'rebonds_defensifs', label: 'Reb. def.', field: 'rebonds_defensifs', align: 'center', sortable: true },
    { name: 'interceptions', label: 'INT', field: 'interceptions', align: 'center', sortable: true },
    { name: 'contres', label: 'Contres', field: 'contres', align: 'center', sortable: true },
    { name: 'ballons_perdus', label: 'BP', field: 'ballons_perdus', align: 'center', sortable: true },
    { name: 'fautes', label: 'Fautes', field: 'fautes', align: 'center', sortable: true },
    { name: 'points_2pts', label: '2 Pts', field: 'points_2pts', align: 'center', sortable: true },
    { name: 'points_2pts_rate', label: '2 Pts ratés', field: 'points_2pts_rate', align: 'center', sortable: true },
    { name: 'points_3pts', label: '3 Pts', field: 'points_3pts', align: 'center', sortable: true },
    { name: 'points_3pts_rate', label: '3 Pts ratés', field: 'points_3pts_rate', align: 'center', sortable: true },
    { name: 'lancers', label: 'LF', field: 'lancers', align: 'center', sortable: true },
    { name: 'lancers_rate', label: 'LF ratés', field: 'lancers_rate', align: 'center', sortable: true },
    { name: 'taux_shoot', label: '% Réussite', field: 'taux_shoot', align: 'center', sortable: true },
  ];
  
  const rows = computed(() => {
    return props.stats.map(match => {
      const sumFields = (base: string) =>
      statPeriode.value.reduce((sum, q) => sum + (match[`q${q}_${base}` as keyof StatMatch] as number || 0), 0);
  
      const tirs_reussis = sumFields('tirs_2pts_reussis') + sumFields('tirs_3pts_reussis');
      const tirs_total = tirs_reussis + sumFields('tirs_2pts_manques') + sumFields('tirs_3pts_manques');
  
      return {
        match_libelle: match.match_libelle,
        minutes: match.minutes,
  
        points: sumFields('tirs_2pts_reussis') * 2 + sumFields('tirs_3pts_reussis') * 3 + sumFields('lancers_francs_reussis'),
        points_2pts: sumFields('tirs_2pts_reussis'),
        points_2pts_rate: sumFields('tirs_2pts_manques'),
        points_3pts: sumFields('tirs_3pts_reussis'),
        points_3pts_rate: sumFields('tirs_3pts_manques'),
        lancers: sumFields('lancers_francs_reussis'),
        lancers_rate: sumFields('lancers_francs_manques'),
  
        taux_shoot: tirs_total > 0 ? Number((tirs_reussis / tirs_total * 100).toFixed(1)) : '-',
  
        passes_decisives: sumFields('passes_decisives'),
        rebonds_offensifs: sumFields('rebonds_offensifs'),
        rebonds_defensifs: sumFields('rebonds_defensifs'),
        rebonds: sumFields('rebonds_offensifs') + sumFields('rebonds_defensifs'),
        interceptions: sumFields('interceptions'),
        contres: sumFields('contres'),
        ballons_perdus: sumFields('ballons_perdus'),
        fautes: sumFields('fautes'),
      };
    });
  });

  const matches = computed(() =>
    rows.value.map(row => {
      const [h, m, s] = String(row.minutes).split(':').map(Number)
      const hours = h || 0
      const minutes = m || 0
      const seconds = s || 0

      const totalMinutes = hours * 60 + minutes + seconds / 60

      switch (attribut.value) {
        case 'Points' :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: row.points
          }

        case 'Passes Déc' :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: row.passes_decisives
          }
        
        case 'Rebonds' :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: row.rebonds
          }

        case 'Interceptions' :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: row.interceptions
          }

        case 'Contres' :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: row.contres
          }
        
        case 'Ballons perdus' :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: row.ballons_perdus
          }

        default :
          return {
            minutes: parseFloat(totalMinutes.toFixed(2)),
            data: 0
          }
      }
    })
  )



  
  const totalsRow = computed(() => {
    const total = {
      nom: 'Total',
      prenom: '',
      minutes: 0,
      points: 0,
      passes_decisives: 0,
      rebonds: 0,
      rebonds_offensifs: 0,
      rebonds_defensifs: 0,
      interceptions: 0,
      contres: 0,
      ballons_perdus: 0,
      fautes: 0,
      points_2pts: 0,
      points_2pts_rate: 0,
      points_3pts: 0,
      points_3pts_rate: 0,
      lancers: 0,
      lancers_rate: 0,
      taux_shoot: 0,
    };
  
    let tirs_reussis = 0;
    let tirs_total = 0;
  
    rows.value.forEach(row => {
      total.minutes += Number(row.minutes);
      total.points += Number(row.points);
      total.passes_decisives += Number(row.passes_decisives);
      total.rebonds += Number(row.rebonds);
      total.rebonds_offensifs += Number(row.rebonds_offensifs);
      total.rebonds_defensifs += Number(row.rebonds_defensifs);
      total.interceptions += Number(row.interceptions);
      total.contres += Number(row.contres);
      total.ballons_perdus += Number(row.ballons_perdus);
      total.fautes += Number(row.fautes);
      total.points_2pts += Number(row.points_2pts);
      total.points_2pts_rate += Number(row.points_2pts_rate);
      total.points_3pts += Number(row.points_3pts);
      total.points_3pts_rate += Number(row.points_3pts_rate);
      total.lancers += Number(row.lancers);
      total.lancers_rate += Number(row.lancers_rate);
  
      tirs_reussis += Number(row.points_2pts) + Number(row.points_3pts);
      tirs_total += Number(row.points_2pts) + Number(row.points_2pts_rate) + Number(row.points_3pts) + Number(row.points_3pts_rate);
    });
  
     total.taux_shoot= tirs_total > 0 ? Number(((tirs_reussis / tirs_total) * 100).toFixed(1)) : 0;
  
    return total;
  });

  const excludedKeys = ['minutes', 'nom', 'prenom', 'points_2pts', 'points_2pts_rate', 'points_3pts', 'points_3pts_rate', 'lancers', 'lancers_rate', 'taux_shoot']

  const labels = computed(() => {
    return Object.keys(totalsRow.value).filter((key) => {
      return (
        !excludedKeys.includes(key) &&
        typeof totalsRow.value[key as keyof typeof totalsRow.value] === 'number'
      )
    })
  })

  const dataValues = computed(() => {
    return labels.value.map((key) =>
      totalsRow.value[key as keyof typeof totalsRow.value] as number / 8 //TODO
    )
  })

  const labelsShoot = computed(() => {
    return Object.keys(totalsRow.value).filter((key) => {
      return (
        ['points_2pts', 'points_2pts_rate'].includes(key) &&
        typeof totalsRow.value[key as keyof typeof totalsRow.value] === 'number'
      )
    })
  })

  const dataValuesShoot = computed(() => {
    return labelsShoot.value.map((key) =>
      totalsRow.value[key as keyof typeof totalsRow.value] as number
    )
  })

  const labelsShoot3pts = computed(() => {
    return Object.keys(totalsRow.value).filter((key) => {
      return (
        ['points_3pts', 'points_3pts_rate'].includes(key) &&
        typeof totalsRow.value[key as keyof typeof totalsRow.value] === 'number'
      )
    })
  })

  const dataValuesShoot3pts = computed(() => {
    return labelsShoot3pts.value.map((key) =>
      totalsRow.value[key as keyof typeof totalsRow.value] as number
    )
  })

  const labelsLF = computed(() => {
    return Object.keys(totalsRow.value).filter((key) => {
      return (
        ['lancers', 'lancers_rate'].includes(key) &&
        typeof totalsRow.value[key as keyof typeof totalsRow.value] === 'number'
      )
    })
  })

  const dataValuesLF = computed(() => {
    return labelsLF.value.map((key) =>
      totalsRow.value[key as keyof typeof totalsRow.value] as number
    )
  })

  const barChartCanvas = ref()
  let chartInstance : Chart 
  
  const createChart = () => {
  if (chartInstance) {
    chartInstance.destroy()
  }

  chartInstance = new Chart(barChartCanvas.value, {
      type: 'bar',
      data: {
        labels : labels.value,
        datasets: [
          {
            label: 'Stats',
            data: dataValues.value,
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
            max: 20,
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
  //console.log(dataValues)
  createChart()
})

// Recrée le graphique si les données changent
watch(() => [labels, totalsRow], () => {
  createChart()
})
</script>
  
<style scoped>
.ligne-paire {
  background-color: #ffe5e5;
}

.ligne-impaire {
  background-color: #ffcccc;
}

.ligne-total {
  background-color: #FF0000;
  color: white;
  font-weight: 700;
  font-size: 1.1em;
  border-top: 2px solid #FF0000;
}

.ligne-total q-td {
  border-top: 2px solid #FF0000;
}

.sticky-col {
  position: sticky;
  left: 0;
  z-index: 1;
  box-shadow: 2px 0 5px -2px rgba(0, 0, 0, 0.2);
}

.sticky-col-head {
  position: sticky;
  left: 0;
  z-index: 2; /* Plus haut que les lignes normales */
  background: white;
  box-shadow: 2px 0 5px -2px rgba(0, 0, 0, 0.2);
}

.q-td,
.q-th {
  min-width: 120px;
}

</style>
