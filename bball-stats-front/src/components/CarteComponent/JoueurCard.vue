<template>
    <q-card class="q-ma-md q-pa-md shadow-2 rounded-borders cursor-pointer" style="width: 250px;" @click="handleClick()">
      <q-img :src="props.joueur.photo" alt="Photo du joueur" class="rounded-borders" />
  
      <q-card-section>
        <div class="text-h6">{{ props.joueur.prenom }} {{ props.joueur.nom }}</div>
        <div class="text-subtitle2 text-grey">{{ props.joueur.civilite === 'M' ? 'Homme' : 'Femme' }}</div>
        <div class="text-caption text-grey-7">Licence : {{ props.joueur.licence }}</div>
        <div class="text-caption text-grey-7">Né(e) le : {{ formatDate(props.joueur.date_naissance) }}</div>
      </q-card-section>
  
      <q-separator />
  
      <q-card-section class="row items-center q-gutter-sm">
        <q-avatar size="32px">
          <img :src="props.joueur.logo" alt="Logo équipe" />
        </q-avatar>
        <div>{{ props.joueur.equipe }}</div>
      </q-card-section>
    </q-card>
  </template>
  
  <script setup lang="ts">
  import type { JoueurType } from '../types/JoueurType'; // modifie ce chemin selon ton projet
  
  const props = defineProps<{
    joueur: JoueurType
  }>()
  
  function formatDate(date: Date): string {
    return new Date(date).toLocaleDateString('fr-FR')
  }

  function handleClick() {
    alert(props.joueur.licence)
  //router.push({ name: 'joueur-details', params: { licence: props.joueur.licence } })
}
  </script>
  
  <style scoped>
  .rounded-borders {
    border-radius: 12px;
  }


  </style>
  