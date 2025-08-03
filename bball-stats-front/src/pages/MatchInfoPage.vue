<template>
    <div class="flex flex-column items-center justify-center">
        <MatchCard 
            v-if="match"
            :idMatch="match.idMatch"
            :numero="match.numero"
            :dateMatch="new Date(match.dateMatch)"
            :equipeDom="match.equipeDom"
            :equipeExt="match.equipeExt"
            :logoDom="match.logoDom"
            :logoExt="match.logoExt"
            :scoreDom="match.scoreDom"
            :scoreExt="match.scoreExt"
            class="q-mt-md q-ml-xl"
        />
    </div>
    <q-separator class="q-mt-md q-mb-md" />
    <StatsJoueursTable :stats="stats" :idMatch="match?.idMatch" class="q-mt-md"/>
</template>

<script setup lang="ts">
import axios from 'axios';
import MatchCard from 'src/components/CarteComponent/MatchCard.vue';
import StatsJoueursTable from 'src/components/TableComponent/StatsJoueursTable.vue';
import type { MatchType } from 'src/components/types/MatchType';
import type { StatJoueur } from 'src/components/types/StatJoueurType';
import { defineProps, ref } from 'vue';


const props = defineProps<{ id: string }>();

const match = ref<MatchType>()
const stats = ref<StatJoueur[]>([])

const fetchGames = async () => {
  try {
    const response = await axios.get(`http://localhost:8000/api/matchs/${ props.id }`);
    match.value = response.data.match
    stats.value = response.data.stats

  } catch (error) {
    console.error('Error fetching games:', error);
  }
};

void fetchGames();
</script>