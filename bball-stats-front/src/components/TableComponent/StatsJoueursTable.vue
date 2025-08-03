<template>
    <div class="row items-center q-mt-md q-mb-md q-ml-xl">
      <q-select
        v-model="periode"
        :options="optionPeriode"
        label="Période"
        outlined
        style="max-width: 200px;"
        @update:model-value="ChangePeriode"
      />

      <q-btn
        icon="add"
        class="q-ml-md bg-primary text-white"
        round
        @click="addStats"
      >
        <q-tooltip>Ajouter les stats du match</q-tooltip>
      </q-btn>
    </div>


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
                    :class="col.name === 'nom' || col.name === 'prenom' ? 'sticky-col-head' : ''"
                    :style="col.name === 'prenom' ? 'left: 120px' : (col.name === 'nom' ? 'left: 0' : '')"
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
                    :class="col.name === 'nom' || col.name === 'prenom' ? 'sticky-col' : ''"
                    :style="col.name === 'prenom' ? 'left: 120px' : (col.name === 'nom' ? 'left: 0' : '')"
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

    <bar-chart titre="Stats Globales" :labels="labels" :data="dataValues" class="q-ml-md q-mr-md q-mt-xl"/>

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

  </template>
  
  <script setup lang="ts">
  import { computed, ref } from 'vue';
  import type { StatJoueur } from 'src/components/types/StatJoueurType';
  import ShootChart from 'src/components/ChartComponent/ShootChart.vue'
  import BarChart from 'src/components/ChartComponent/BarChart.vue'
  import type { QTableColumn } from 'quasar';
  import { useRouter } from 'vue-router';
  
  const props = defineProps<{ stats: StatJoueur[], idMatch: number | undefined }>();
  
  const periode = ref('Match Complet')
  const statPeriode = ref([1,2,3,4])
  const optionPeriode = ref(["Match Complet", "1QT", "2QT", "3QT", "4QT"]);

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
    { name: 'nom', label: 'Nom', field: 'nom', align: 'left', sortable: true },
    { name: 'prenom', label: 'Prénom', field: 'prenom', align: 'left', sortable: true },
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
    return props.stats.map(player => {
      const sumFields = (base: string) =>
      statPeriode.value.reduce((sum, q) => sum + (player[`q${q}_${base}` as keyof StatJoueur] as number || 0), 0);
  
      const tirs_reussis = sumFields('tirs_2pts_reussis') + sumFields('tirs_3pts_reussis');
      const tirs_total = tirs_reussis + sumFields('tirs_2pts_manques') + sumFields('tirs_3pts_manques');
  
      return {
        nom: player.nom,
        prenom: player.prenom,
        minutes: player.minutes,
  
        points: sumFields('tirs_2pts_reussis') * 2 + sumFields('tirs_3pts_reussis') * 3 + sumFields('lancers_francs_reussis'),
        points_2pts: sumFields('tirs_2pts_reussis'),
        points_2pts_rate: sumFields('tirs_2pts_manques'),
        points_3pts: sumFields('tirs_3pts_reussis'),
        points_3pts_rate: sumFields('tirs_3pts_manques'),
        lancers: sumFields('lancers_francs_reussis'),
        lancers_rate: sumFields('lancers_francs_rates'),
  
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

  const router = useRouter()
  const addStats = async () => {
    try {
      await router.push(`/stats/${props.idMatch}`)
    } catch (error) {
      console.error('Erreur lors de la navigation vers la page d\'ajout de stats :', error)
    }
  }

  const excludedKeys = ['minutes', 'nom', 'prenom', 'points_2pts', 'points_2pts_rate', 'points_3pts', 'points_3pts_rate', 'lancers', 'lancers_rate']

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
      totalsRow.value[key as keyof typeof totalsRow.value] as number
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

  </script>
  
  <style scoped>
.ligne-paire {
  background-color: #ffe5e5; /* Gris clair */
}

.ligne-impaire {
  background-color: #ffcccc; /* Blanc */
}

.ligne-total {
  background-color: #FF0000; /* Rouge foncé */
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
  z-index: 10;
  box-shadow: 2px 0 5px -2px rgba(0,0,0,0.2);
  /* Pour bien séparer visuellement les colonnes figées */
}

.sticky-col-head {
  position: sticky;
  background: white;
  z-index: 10;
  box-shadow: 2px 0 5px -2px rgba(0,0,0,0.2);
  /* Pour bien séparer visuellement les colonnes figées */
}

/* Ajuste la largeur des colonnes nom/prenom si besoin */
.q-td,
.q-th {
  min-width: 120px;
}

</style>
