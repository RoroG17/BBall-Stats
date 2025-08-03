<template>
    <q-form @submit.prevent="submitForm" class="q-pa-md">
      <q-card>
        <q-card-section>
          <div class="text-h6">Infos joueur</div>
          <q-select
            v-model="form.licence"
            :options="joueurs"
            label="Joueur"
            emit-value
            map-options
            dense
          />
          <q-input
            filled
            type="time"
            format="mm:ss"
            v-model.number="form.minutes"
            label="Minutes jouées"
          />
        </q-card-section>
  
        <q-separator />
  
        <q-card-section v-for="quart in [1, 2, 3, 4]" :key="quart">
          <div class="text-subtitle1 q-mb-sm">Quart {{ quart }}</div>
          <div class="row q-col-gutter-sm">
            <q-input
              v-for="field in statsFields"
              :key="`q${quart}_${field.key}`"
              class="col-4"
              filled
              type="number"
              :label="field.label"
              v-model.number="getFieldModel(quart, field.key).value"
            />
          </div>
        </q-card-section>
  
        <q-separator />
  
        <q-card-actions align="right">
          <q-btn label="Annuler" color="grey" flat @click="resetForm"/>
          <q-btn label="Enregistrer" type="submit" color="primary" />
        </q-card-actions>
      </q-card>
    </q-form>
  </template>
  
  <script setup lang="ts">
  import { ref, computed, defineProps } from 'vue'
  import axios from 'axios'
  import { useQuasar } from 'quasar'
  import type { JoueurType } from 'src/components/types/JoueurType'
  
  const $q = useQuasar()
  
  // Props
  const props = defineProps<{ id: string }>()
  
  // Form interface
  interface FormStats {
    licence: string
    id_match: string
    minutes: number
    [key: string]: string | number
  }
  
  // Reactive form
  const form = ref<FormStats>({
    licence: '',
    id_match: props.id,
    minutes: 0,
  })
  
  // Génération dynamique des champs par quart et type de stat
  const statKeys = [
    'passes_decisives',
    'rebonds_offensifs',
    'rebonds_defensifs',
    'interceptions',
    'contres',
    'ballons_perdus',
    'fautes',
    'tirs_2pts_reussis',
    'tirs_2pts_manques',
    'tirs_3pts_reussis',
    'tirs_3pts_manques',
    'lancers_francs_reussis',
    'lancers_francs_rates',
    'passes_reussies',
    'passes_rates',
  ]
  
  // Générer les champs dans `form`
  for (const quart of [1, 2, 3, 4]) {
    for (const key of statKeys) {
      form.value[`q${quart}_${key}`] = 0
    }
  }
  
  // Liste des champs à afficher
  const statsFields = statKeys.map((key) => ({
    key,
    label: key
      .replace(/_/g, ' ')
      .replace(/\b(\w)/g, (match) => match.toUpperCase()),
  }))
  
  // Fonction pour accéder dynamiquement aux champs
  const getFieldModel = (quart: number, key: string) =>
    computed<number>({
      get: () => form.value[`q${quart}_${key}`] as number,
      set: (val: number) => {
        form.value[`q${quart}_${key}`] = val
      },
    })
  
  // Récupération des joueurs
  const joueurs = ref<{ label: string; value: number }[]>([])
  
  const fetchJoueurs = async () => {
    try {
      const response = await axios.get(`http://localhost:8000/api/joueurs`)
      joueurs.value = response.data.joueurs.map((joueur: JoueurType) => ({
        label: `${joueur.nom} ${joueur.prenom}`,
        value: joueur.licence,
      }))
    } catch (error) {
      console.error('Erreur lors de la récupération des joueurs :', error)
    }
  }
  void fetchJoueurs()
  
  // Soumission du formulaire
  const submitForm = async () => {
    const data = form.value
    data.Id_Match = props.id
    try {
      const response = await axios.post(
        `http://localhost:8000/add/stats`,
        data
      )
      console.log(response.data)
      resetForm()
      $q.notify({
        message: 'Stats enregistrées avec succès',
        color: 'green',
      })
    } catch (error) {
      console.error('Erreur lors de l\'enregistrement des stats :', error)
      $q.notify({
        message: 'Erreur lors de l\'enregistrement',
        color: 'red',
      })
    }
  }

  const resetForm = () => {
    form.value = {
      licence: '',
      id_match: props.id,
      minutes: 0,
    }
    for (const quart of [1, 2, 3, 4]) {
      for (const key of statKeys) {
        form.value[`q${quart}_${key}`] = 0
      }
    }
  }
  </script>
  