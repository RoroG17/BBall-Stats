<template>
    <q-form @submit.prevent="submitForm" class="q-gutter-md">
  
      <!-- Date du saison -->
      <q-input
        v-model="saison.annee_debut"
        type="number"
        label="Année de début"
        outlined
        clearable
        dense
      />
  
      <!-- Numéro du saison -->
      <q-input
        v-model="saison.annee_fin"
        type="number"
        label="Année de fin"
        outlined
        clearable
        dense
      />
  
      <q-input
        v-model="saison.championnat"
        label="Championnat"
        outlined
        dense
        emit-value
        map-options
      />

      <q-input
        v-model="saison.categorie"
        label="Catégorie"
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
  
  const saison = ref({
    annee_debut: null,         // format YYYY-MM-DD
    annee_fin: null,
    championnat: '',
    categorie: ''
  })

async function submitForm () {
  try {
    const response = await axios.post('http://localhost:8000/api/saisons', { data: saison.value })

    console.log(response)
    showNotify("Saison créé avec succès", 'blue')

    // Optionnel : réinitialiser le formulaire
    resetForm()

  } catch (error) {
    console.error('Erreur lors de la création du saison :', error)
    showNotify("Echec de la création du Saison", 'orange')
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
    saison.value = {
      annee_debut: null,         // format YYYY-MM-DD
      annee_fin: null,
      championnat: '',
      categorie: ''
    }
  }
  </script>
  