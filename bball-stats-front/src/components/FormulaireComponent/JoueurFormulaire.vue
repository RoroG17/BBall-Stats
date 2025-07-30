<template>
  <q-form @submit.prevent="submitForm" class="q-gutter-md">
    
    <!-- Nom de l'équipe -->
    <q-input
      v-model="joueur.licence"
      label="N° de licence"
      outlined
      dense
    />

    <q-input
      v-model="joueur.nom"
      label="Nom"
      outlined
      dense
    />

    <q-input
      v-model="joueur.prenom"
      label="Prénom"
      outlined
      dense
    />

    <q-select
      v-model="joueur.civilite"
      label="Civilité"
      :options="optionCivilite"
      outlined
      dense
    />

    <q-input
      v-model="joueur.date_naissance"
      label="Date de naissance"
      type="date"
      outlined
      dense
    />

    <q-select
      v-model="joueur.equipe"
      label="Equipe"
      :options="optionEquipe"
      outlined
      emit-value
      map-options
      dense
    />

    <!-- Upload du logo -->
    <q-file
      v-model="joueur.photo"
      accept="image/*"
      label="Déposer ou sélectionner une photo"
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
import type { EquipeType } from '../types/EquipeType'

const $q = useQuasar()

const optionCivilite = ref(['M', 'F'])
const optionEquipe = ref<{ label: string; value: number }[]>([])
// Form data
const joueur = ref({
  licence : '',
  nom: '',
  prenom : '',
  civilite : '',
  date_naissance : '',
  equipe : '',
  photo: null as File | null
})

// Aperçu du logo
const logoPreview = computed(() => {
  return joueur.value.photo ? URL.createObjectURL(joueur.value.photo) : ''
})

const fetchTeam = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/equipes');

    optionEquipe.value = response.data.map((equipe: EquipeType) => ({
      label: equipe.nom,
      value: equipe.Id_Equipe
    }))

  } catch (error) {
    console.error('Error fetching games:', error);
  }
};

void fetchTeam()

// Envoi du formulaire avec image
async function submitForm () {

  try {
    const formData = new FormData()
    formData.append('licence', joueur.value.licence)
    formData.append('nom', joueur.value.nom)
    formData.append('prenom', joueur.value.prenom)
    formData.append('civilite', joueur.value.civilite)
    formData.append('date_naissance', joueur.value.date_naissance)
    formData.append('equipe', joueur.value.equipe)
    if (joueur.value.photo) {
      formData.append('photo', joueur.value.nom.toLocaleLowerCase() + '_' + joueur.value.prenom.toLocaleLowerCase() + '.png')
    }

    const response = await axios.post('http://localhost:8000/api/joueurs', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    console.log(response)
    
    showNotify("Joueur créé avec succès", 'positive')
    resetForm()

  } catch (error) {
    console.error('Erreur lors de la création du joueur :', error)
    showNotify("Échec de la création du joueur", 'negative')
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
  joueur.value = {
    licence : '',
    nom: '',
    prenom : '',
    civilite : '',
    date_naissance : '',
    equipe : '',
    photo: null as File | null
  }
}
</script>
