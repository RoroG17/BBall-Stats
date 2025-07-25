<template>
    <q-card class="cursor-pointer matchCard" @click="goToMatchStats(props.Id_Match)">
      <q-card-section class="bg-primary text-white text-center text-h6">
        Journée {{ props.numero }} - {{ formattedDate }}
      </q-card-section>

      <q-card-section class="row items-center justify-around">
      <div class="column items-center">
        <q-avatar size="80px">
          <img :src="`/storage/${props.logoDom}`" :alt="`Logo ${props.equipeDom}`" />
        </q-avatar>
        <div class="text-h6 text-center">{{ props.equipeDom }}</div>
      </div>

      <div class="text-h4 text-weight-bold text-center">
        <p>{{ props.scoreDom }} - {{ props.scoreExt }}</p>
      </div>

      <div class="column items-center">
        <q-avatar size="80px">
          <img :src="`/storage/${props.logoExt}`" :alt="`Logo ${props.equipeExt}`" />
        </q-avatar>
        <div class="text-h6 text-center">{{ props.equipeExt }}</div>
      </div>
    </q-card-section>
    </q-card>
  </template>
  
  <script setup lang="ts">
  import type { MatchType } from './types/MatchType';
  
  const props = defineProps<MatchType>();
  
  // Formatage de la date (tu peux adapter selon ton format préféré)
  const formattedDate = props.date_match.toLocaleDateString("fr-FR", {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  const goToMatchStats = (id: number) => {
    //router.push(`/espace-matchs/${id}`);
    alert(id);
  };
  </script>
  
  <style scoped>
  .matchCard {
    width: 80%;
  }
  </style>
  