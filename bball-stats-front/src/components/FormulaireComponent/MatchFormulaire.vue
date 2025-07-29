<template>
    <q-form @submit.prevent="submitForm" class="q-gutter-md">
  
      <!-- Date du match -->
      <q-input
        v-model="match.date_match"
        type="date"
        label="Date du match"
        outlined
        clearable
        dense
      />
  
      <!-- Numéro du match -->
      <q-input
        v-model="match.numero"
        type="number"
        label="Numéro de journée"
        outlined
        dense
      />
  
      <q-select
        v-model="match.Id_Saison"
        :options="optionSaisons"
        label="Saison"
        outlined
        dense
        emit-value
        map-options
      />

      <q-select
        v-model="match.equipe_domicile"
        :options="optionEquipes"
        label="Équipe domicile"
        outlined
        dense
        emit-value
        map-options
      />

      <q-select
        v-model="match.equipe_exterieur"
        :options="optionEquipes"
        label="Équipe extérieur"
        outlined
        dense
        emit-value
        map-options
      />

  
      <div class="row q-gutter-sm q-mt-md justify-end">
        <q-btn label="Annuler" class="bg-grey text-white" @click="resetForm" />
        <q-btn type="submit" label="Enregistrer" class="bg-green text-white"/>
    </div>

      
    </q-form>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios';
  import { useQuasar } from 'quasar';
  import type { SaisonType } from '../types/SaisonType';
  import type { EquipeType } from '../types/EquipeType';
  
  const match = ref({
    date_match: '',         // format YYYY-MM-DD
    numero: null,
    equipe_domicile: '',
    equipe_exterieur: '',
    Id_Saison: null
  })

  const optionSaisons = ref<string[]>([])

  const optionEquipes = ref<string[]>([])
  const fetchData = async () => {
  try {
    const dataSaison = await axios.get('http://localhost:8000/api/saisons');
    const dataEquipe = await axios.get('http://localhost:8000/api/equipes')

    optionSaisons.value = (dataSaison.data || []).map((s: SaisonType) => ({
      label: `${s.annee_debut} / ${s.annee_fin} - ${s.championnat} (${s.categorie})`,
      value: s.Id_Saison // ← c'est l'ID qui sera renvoyé dans v-model
    }))
    
    optionEquipes.value = (dataEquipe.data || []).map((e: EquipeType) => ({
      label: e.nom,
      value: e.Id_Equipe
    }))
  } catch (error) {
        console.error('Error fetching games:', error);
    }
}
void fetchData();  

async function submitForm () {
  try {
    const response = await axios.post('http://localhost:8000/api/matchs', { data: match.value })

    console.log(response)
    showNotify("Match créé avec succès", 'blue')

    // Optionnel : réinitialiser le formulaire
    resetForm()

  } catch (error) {
    console.error('Erreur lors de la création du match :', error)
    showNotify("Echec de la création du match", 'orange')
  }
}

const $q = useQuasar()
const showNotify = (message:string, color:string) => {
  $q.notify({
    progress: true,
    message : message,
    color : color
  })
}
  
  function resetForm () {
    match.value = {
      date_match: '',
      numero: null,
      equipe_domicile: '',
      equipe_exterieur: '',
      Id_Saison: null
    }
  }
  </script>
  