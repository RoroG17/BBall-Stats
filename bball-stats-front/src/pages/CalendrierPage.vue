<template>
    <div class="q-pa-md" style="width: 100%;">
      <p class="text-h4 q-mt-xl q-ml-xl">Calendrier de la saison</p>

      <q-separator />

      <div class="row q-gutter-md q-mb-xs q-mt-xs q-ml-md" style="display: flex; align-items: center;">
        <div style="display: flex; gap: 16px;">
          <q-select
            v-model="selectedSaison.annees"
            clearable
            :options="optionAnnee"
            label="Années"
            class="q-mb-md"
            style="width: 200px;"
            @update:model-value="updateMatches"
          />
          <q-select
            v-model="selectedSaison.championnat"
            clearable
            :options="optionChamp"
            label="Championnat"
            class="q-mb-md"
            style="width: 200px;"
            @update:model-value="updateMatches"
          />
          <q-select
            v-model="selectedSaison.categorie"
            clearable
            :options="optionCat"
            label="Catégorie"
            class="q-mb-md"
            style="width: 200px;"
            @update:model-value="updateMatches"
          />
        </div>

        <!-- Spacer flexible qui pousse le bouton à droite -->
        <div style="flex-grow: 1;"></div>

        <q-btn
          icon="add"
          color="primary"
          round
          style="width: 4em; height: 4em;"
          class="q-mr-xl"
          to="/match"
        >
          <q-tooltip anchor="bottom middle" self="bottom middle">
            Créer un match
          </q-tooltip>
        </q-btn>
      </div>

      <q-separator />

      <div class="row justify-center">
          <MatchCard 
                v-for="match in matches"
                :key="match.idMatch"
                :idMatch="match.idMatch"
                :numero="match.numero"
                :dateMatch="new Date(match.dateMatch)"
                :equipeDom="match.equipeDom"
                :equipeExt="match.equipeExt"
                :logoDom="match.logoDom"
                :logoExt="match.logoExt"
                :scoreDom="match.scoreDom"
                :scoreExt="match.scoreExt"
                class="q-mt-md"
              />
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import MatchCard from '../components/CarteComponent/MatchCard.vue';
  import{ ref } from 'vue'
  import type { MatchType } from '../components/types/MatchType';
  import type { SaisonType } from 'src/components/types/SaisonType';
  import axios from 'axios';

  const matches = ref<MatchType[]>([])

    const selectedSaison = ref({
      annees : "2024-2025",
      championnat : "",
      categorie : "U15G"
    })

    const saisons = ref<SaisonType[]>([])

    const updateMatches = async () => {

      const response = await axios.post(
        'http://localhost:8000/recherche/matchs',
        { filtre: selectedSaison.value },
        {
          withCredentials: true,
          headers: {
            'Content-Type': 'application/json',
            // Si tu as un token CSRF, tu peux l’ajouter ici, sinon laisse comme ça
            // 'X-CSRF-TOKEN': csrfToken,
          },
        }
      );



      matches.value = (response.data || []).map((m: MatchType) => ({
        idMatch: m.idMatch,
        dateMatch: new Date(m.dateMatch),
        numero: m.numero,
        equipeDom: m.equipeDom,
        equipeExt: m.equipeExt,
        logoDom: m.logoDom,
        logoExt: m.logoExt,
        scoreDom: m.scoreDom || undefined,
        scoreExt: m.scoreExt || undefined
    }));
    }

    const optionAnnee = ref<string[]>([])
    const optionChamp = ref<string[]>([])
    const optionCat = ref<string[]>([])
  const fetchGames = async () => {
  try {
    const responseSaison = await axios.get('http://localhost:8000/api/saisons')

    saisons.value = (responseSaison.data || []).map((s : SaisonType) => ({
      id_Saison : s.Id_Saison,
      annee_debut : s.annee_debut,
      annee_fin : s.annee_fin,
      championnat : s.championnat,
      categorie : s.categorie
    }))

    saisons.value.forEach(s => {
      const annees = s.annee_debut + ' - ' + s.annee_fin
      if (!optionAnnee.value.includes(annees)) {
        optionAnnee.value.push(annees);
      }

      if (!optionChamp.value.includes(s.championnat)) {
        optionChamp.value.push(s.championnat);
      }

      if (!optionCat.value.includes(s.categorie)){
        optionCat.value.push(s.categorie)
      }
    });
    optionAnnee.value.sort((a, b) => a.localeCompare(b));
    optionChamp.value.sort((a, b) => a.localeCompare(b));
    optionCat.value.sort((a, b) => a.localeCompare(b));
    } catch (error) {
        console.error('Error fetching games:', error);
    }
};

void fetchGames();
void updateMatches();  
  </script>
  
  <style scoped>

  </style>
  