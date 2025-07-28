<template>
  <div class="q-pa-md accueil">
    <q-card class="q-pa-md q-mb-md card-section" flat bordered>
      <div class="text-h5 text-primary flex items-center q-mb-sm">
        <q-icon name="sports_basketball" class="q-mr-sm" />
        Match Précédent
      </div>
      <div class="flex justify-center items-center">
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
        <div v-else>
          <p class="text-subtitle2">Aucun match précédent.</p>
        </div>
      </div>
    </q-card>

    <q-card class="q-pa-md q-mb-md card-section" flat bordered>
      <div class="text-h5 text-primary flex items-center q-mb-sm">
        <q-icon name="sports_basketball" class="q-mr-sm" />
        Prochain Match
      </div>
      <div class="flex justify-center items-center">
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
        <div v-else>
          <p class="text-subtitle2">Aucun match n'est encore prévu.</p>
        </div>
      </div>
    </q-card>

    <q-card class="q-pa-md card-section" flat bordered>
      <div class="text-h5 text-accent flex items-center q-mb-sm">
        <q-icon name="calendar_today" class="q-mr-sm" />
        Calendrier de la Saison
      </div>
      <div class="flex justify-center items-center">
        <CalendrierSaison :events="events" />
      </div>
    </q-card>
  </div>
</template>


<script setup lang="ts">
import { ref } from 'vue';
import Match from '../components/MatchCard.vue';
import CalendrierSaison from '../components/CalendrierSaison.vue';
import type { MatchType } from '../components/types/MatchType';
import type { EventType } from '../components/types/EventType';
import axios from 'axios';

const nextGame = ref<MatchType | null>(null);
const lastGame = ref<MatchType | null>(null);

const events = ref<EventType[]>([])

type BirthdayEvent = {
  licence: string;
  nom: string;
  prenom: string;
  date_anniversaire: string;
};

type MatchEvent = {
  idMatch: string;
  equipeDom: string;
  equipeExt: string;
  dateMatch: Date;
  type: string;
};

const fetchGames = async () => {
  try {
    const response = await axios.get('http://localhost:8000/accueil');
    nextGame.value = response.data.nextGame;
    lastGame.value = response.data.previousGame;

    const birthdayEvents = (response.data.birthday || []).map((ev: BirthdayEvent) => ({
      id: ev.licence,
      title: 'Anniversaire ' + ev.nom + ' ' + ev.prenom,
      date: new Date(ev.date_anniversaire),
      type: 'birthday'
    }));

    const matchEvents = (response.data.matchday || []).map((ev: MatchEvent) => ({
      id: ev.idMatch,
      title: ev.equipeDom + ' - ' + ev.equipeExt,
      date: new Date(ev.dateMatch),
      type: 'match'
    }));

    events.value = [...birthdayEvents, ...matchEvents];
    console.log('Events fetched:', events.value);
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
  justify-content: start;
  width: 100%;
  margin: 0 auto;
}

.card-section {
  width: 100%;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  border-radius: 12px;
}

</style>