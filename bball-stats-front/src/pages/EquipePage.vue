<template>
    <div>
      <q-select
        v-model="periode"
        :options="optionPeriode"
        label="Période"
        outlined
        class="q-mt-md q-mb-md q-ml-xl"
        style="max-width: 200px;"
      />

      <moy-equipe-chart :periode="periode" :data="donnee" />
      <lineaire-chart :data="total" />

      <q-separator class="q-mt-md q-mb-md"/>

      <div class="text-h5 q-ml-xl q-mb-md text-primary">Statistiques Individuelles de la Saison</div>

      <div class="q-pa-md row q-gutter-md justify-center">
        <classement title="Points" :data="classPTSActuelle" />
        <classement title="Passes Décisives" :data="classPDActuelle" />
        <classement title="Rebonds" :data="classRebActuelle" />
        <classement title="Interceptions" :data="classIntActuelle" />
        <classement title="Contres" :data="classContreActuelle" />
        <classement title="Ballons Perdus" :data="classBPActuelle" />
        <classement title="% Shoot 2pts" :data="classShootActuelle" />
        <classement title="% Shoot 3pts" :data="class3PtsActuelle" />
        <classement title="% Lancers Francs" :data="classLFActuelle" />
      </div>

      <q-separator class="q-mt-md q-mb-md"/>

      <div class="text-h5 q-ml-xl q-mb-md text-primary">Statistiques Individuelles Globales</div>

      <div class="q-pa-md row q-gutter-md justify-center">
        <classement title="Points" :data="classPTS" />
        <classement title="Passes Décisives" :data="classPD" />
        <classement title="Rebonds" :data="classReb" />
        <classement title="Interceptions" :data="classInt" />
        <classement title="Contres" :data="classContre" />
        <classement title="Ballons Perdus" :data="classBP" />
        <classement title="% Shoot 2pts" :data="classShoot" />
        <classement title="% Shoot 3pts" :data="class3Pts" />
        <classement title="% Lancers Francs" :data="classLF" />
      </div>
    </div>
  </template>
  
  <script lang="ts" setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import MoyEquipeChart from '../components/ChartComponent/MoyEquipeChart.vue'
  import LineaireChart from 'src/components/ChartComponent/LineaireChart.vue'
  import Classement from 'src/components/ClassementComponent/ClassementJoueur.vue'
  
  // Données de période
  const periode = ref('Match Complet')
  const optionPeriode = ref(["Match Complet", "1QT", "2QT", "3QT", "4QT"])
  
  
  // Appel de l'API au montage du composant
  const donnee = ref([])
  const total = ref([])

  const classPTSActuelle = ref([])
  const classPDActuelle = ref([])
  const classRebActuelle = ref([])
  const classIntActuelle = ref([])
  const classContreActuelle = ref([])
  const classBPActuelle = ref([])

  const classShootActuelle = ref([])
  const class3PtsActuelle = ref([])
  const classLFActuelle = ref([])

  const classPTS = ref([])
  const classPD = ref([])
  const classReb = ref([])
  const classInt = ref([])
  const classContre = ref([])
  const classBP = ref([])

  const classShoot = ref([])
  const class3Pts = ref([])
  const classLF = ref([])
  const fetchGames = async () => {
    try {
      const response = await axios.get('http://localhost:8000/stats')
      donnee.value = response.data.stats
      total.value = response.data.total

      classPTSActuelle.value = response.data.classPTSActuelle
      classPDActuelle.value = response.data.classPDActuelle
      classRebActuelle.value = response.data.classRebActuelle
      classIntActuelle.value = response.data.classIntActuelle
      classContreActuelle.value = response.data.classContreActuelle
      classBPActuelle.value = response.data.classBPActuelle

      classShootActuelle.value = response.data.classShootActuelle
      class3PtsActuelle.value = response.data.class3PtsActuelle
      classLFActuelle.value = response.data.classLFActuelle

      classPTS.value = response.data.classPTS
      classPD.value = response.data.classPD
      classReb.value = response.data.classReb
      classInt.value = response.data.classInt
      classContre.value = response.data.classContre
      classBP.value = response.data.classBP

      classShoot.value = response.data.classShoot
      class3Pts.value = response.data.class3Pts
      classLF.value = response.data.classLF
      console.log('Données des matchs:', classPD.value)
  
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
  