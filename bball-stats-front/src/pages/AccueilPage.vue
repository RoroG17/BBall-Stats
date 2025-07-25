<template>
  <div class="q-pa-md accueil">
    <h3>Match Précédent</h3>
    <Match
      v-if="previousGame"
      :id="previousGame.Id_Match"
      :date="new Date(previousGame.date_match)"
      :numero="previousGame.numero"
      :equipe-dom="previousGame.equipeDom"
      :equipe-ext="previousGame.equipeExt"
      :logo-dom="previousGame.logoDom"
      :logo-ext="previousGame.logoExt"
      :score-dom="previousGame.scoreDom"
      :score-ext="previousGame.scoreExt"
    />

    <h3>Match Suivant</h3>
    <Match
      v-if="nextGame"
      :id="nextGame.Id_Match"
      :date="new Date(nextGame.date_match)"
      :numero="nextGame.numero"
      :equipe-dom="nextGame.equipeDom"
      :equipe-ext="nextGame.equipeExt"
      :logo-dom="nextGame.logoDom"
      :logo-ext="nextGame.logoExt"
    />

    <CalendrierSaison />
  </div>
</template>

<script setup lang="ts">
import Match from '../components/MatchCard.vue';
import CalendrierSaison from '../components/CalendrierSaison.vue';
import type { MatchType } from 'src/components/types/MatchType';
import axios from 'axios';
import { ref, onMounted } from 'vue';


const nextGame = ref<MatchType | null>(null);
const previousGame = ref<MatchType | null>(null);

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/accueil');
    nextGame.value = response.data.nextGame;
    previousGame.value = response.data.previousGame;
  } catch (error) {
    console.error('Erreur lors de la récupération des matchs', error);
  }
});
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
