<template>
    <div class="flex flex-column items-center justify-center">
        Statistique de {{ joueur?.nom }} {{ joueur?.prenom }}
    </div>
    <q-separator class="q-mt-md q-mb-md" />
    <StatsMatchsTable :stats="stats" class="q-mt-md"/>
</template>

<script setup lang="ts">
import axios from 'axios';
import StatsMatchsTable from 'src/components/TableComponent/StatsMatchsTable.vue';
import type { JoueurType } from 'src/components/types/JoueurType';
import type { StatMatch } from 'src/components/types/StatMatchType';
import { defineProps, ref } from 'vue';

const props = defineProps<{ id: string }>();

const joueur = ref<JoueurType>()
const stats = ref<StatMatch[]>([])

const fetchGames = async () => {
try {
const response = await axios.get(`http://localhost:8000/api/joueurs/${ props.id }`);
joueur.value = response.data.joueur
stats.value = response.data.stats

} catch (error) {
console.error('Error fetching games:', error);
}
};

void fetchGames();
</script>