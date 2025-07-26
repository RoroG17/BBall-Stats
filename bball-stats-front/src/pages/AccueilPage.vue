<template>
  <div class="q-pa-md accueil">
    <h3>Match Précédent</h3>
    <Match
      v-if="lastGame"
      :idMatch="lastGame.idMatch"
      :numero="lastGame.numero"
      :dateMatch="new Date(lastGame.dateMatch)"
      :equipeDom="lastGame.equipeDom"
      :equipeExt="lastGame.equipeExt"
      :logoDom="lastGame.logoDom"
      :logoExt="lastGame.logoExt"
      :scoreDom="lastGame.scoreDom"
      :scoreExt="lastGame.scoreExt"
    />

    <h3>Prochain Match</h3>
    <Match
      v-if="nextGame"
      :idMatch="nextGame.idMatch"
      :numero="nextGame.numero"
      :dateMatch="new Date(nextGame.dateMatch)"
      :equipeDom="nextGame.equipeDom"
      :equipeExt="nextGame.equipeExt"
      :logoDom="nextGame.logoDom"
      :logoExt="nextGame.logoExt"
      :scoreDom="nextGame.scoreDom"
      :scoreExt="nextGame.scoreExt"
    />
    <q-card class="q-mt-md" style="width: 80%;">
      <div class="row justify-center items-center full-height q-mb-md">
        <CalendrierSaison />
      </div>
    </q-card>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Match from '../components/MatchCard.vue';
import CalendrierSaison from '../components/CalendrierSaison.vue';
import type { MatchType } from '../components/types/MatchType';
import axios from 'axios';

const nextGame = ref<MatchType | null>(null);
const lastGame = ref<MatchType | null>(null);

const fetchGames = async () => {
  try {
    const response = await axios.get('http://localhost:8000/accueil');
    nextGame.value = response.data.nextGame;
    lastGame.value = response.data.previousGame;
    console.log('Next Game:', nextGame.value);
    console.log('Last Game:', lastGame.value);
  } catch (error) {
    console.error('Error fetching games:', error);
  }
};

void fetchGames();
</script>

<style scoped>
.accueil {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;  
  width: 100%;
}
</style>