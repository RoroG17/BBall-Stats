<template>
  <q-form @submit.prevent="submitForm" class="q-gutter-md">
    
    <!-- Nom de l'équipe -->
    <q-input
      v-model="equipe.nom"
      label="Nom de l'équipe"
      outlined
      dense
    />

    <!-- Upload du logo -->
    <q-file
      v-model="equipe.logo"
      accept="image/*"
      label="Déposer ou sélectionner un logo"
      outlined
      use-chips
      dense
    >
      <template v-slot:append>
        <q-icon name="image" />
      </template>
    </q-file>

    <!-- Aperçu -->
    <div v-if="logoPreview" class="q-mt-sm">
      <img :src="logoPreview" alt="Aperçu du logo" style="max-height: 150px;" />
    </div>

    <!-- Boutons -->
    <div class="row q-gutter-sm q-mt-md justify-end">
      <q-btn label="Annuler" class="bg-grey text-white" @click="resetForm" />
      <q-btn type="submit" label="Enregistrer" class="bg-green text-white"/>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import axios from 'axios'
import { useQuasar } from 'quasar'

const $q = useQuasar()

// Form data
const equipe = ref({
  nom: '',
  logo: null as File | null
})

// Aperçu du logo
const logoPreview = computed(() => {
  return equipe.value.logo ? URL.createObjectURL(equipe.value.logo) : ''
})

// Envoi du formulaire avec image
async function submitForm () {

  try {
    const formData = new FormData()
    formData.append('nom', equipe.value.nom)
    if (equipe.value.logo) {
      formData.append('logo', 'logo_' + equipe.value.nom.toLocaleLowerCase())
    }

    const response = await axios.post('http://localhost:8000/api/equipes', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    console.log(response)
    
    showNotify("Équipe créée avec succès", 'positive')
    resetForm()

  } catch (error) {
    console.error('Erreur lors de la création de l\'équipe :', error)
    showNotify("Échec de la création de l'équipe", 'negative')
  }
}

// Notification
const showNotify = (message: string, color: string) => {
  $q.notify({
    message,
    color,
    position: 'top',
    timeout: 3000
  })
}

// Reset formulaire
function resetForm () {
  equipe.value = {
    nom: '',
    logo: null
  }
}
</script>
