<template>
    <div class="q-pa-md" style="width: 100%;">
        <p class="text-h4 q-mt-xl q-ml-xl">Effectifs</p>

        <q-separator />

        <div class="row q-gutter-md q-mb-xs q-mt-xs q-ml-md" style="display: flex; align-items: center;">
            <div style="display: flex; gap: 16px;">
            <q-input
                v-model="selectedJoueur.licence"
                clearable
                label="Licence"
                class="q-mb-md"
                style="width: 150px;"
                @update:model-value="updateJoueurs"
            />
            <q-input
                v-model="selectedJoueur.nom"
                clearable
                label="Nom"
                class="q-mb-md"
                style="width: 150px;"
                @update:model-value="updateJoueurs"
            />
            <q-input
                v-model="selectedJoueur.prenom"
                clearable
                label="Prénom"
                class="q-mb-md"
                style="width: 150px;"
                @update:model-value="updateJoueurs"
            />

            <q-input
                v-model="selectedJoueur.generation"
                clearable
                label="Catégorie"
                class="q-mb-md"
                style="width: 150px;"
                @update:model-value="updateJoueurs"
            />
            

            <q-input
                v-model="selectedJoueur.sexe"
                clearable
                label="Sexe"
                class="q-mb-md"
                style="width: 150px;"
                @update:model-value="updateJoueurs"
            />

            <q-input
                v-model="selectedJoueur.equipe"
                clearable
                label="Equipe"
                class="q-mb-md"
                style="width: 150px;"
                @update:model-value="updateJoueurs"
            />
            </div>

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

        <div class="q-pa-md row q-gutter-md justify-center">
            <JoueurCard v-for="j in joueurs" :key="j.licence" :joueur="j" />
        </div>
    </div>
</template>

<script setup lang="ts">
import type { JoueurType } from 'src/components/types/JoueurType';
import JoueurCard from 'src/components/CarteComponent/JoueurCard.vue';
import { ref } from 'vue';
import axios from 'axios';

const joueurs = ref<JoueurType[]>([])

const selectedJoueur = ref({
    licence : "",
    nom : "",
    prenom : "",
    generation : "",
    sexe : "",
    equipe : ""
})

const fetchPlayer = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/joueurs');
    joueurs.value = response.data.joueurs
  } catch (error) {
    console.error('Error fetching games:', error);
  }
};

void fetchPlayer()

const updateJoueurs = async () => {
    const response = await axios.post(
        'http://localhost:8000/recherche/joueurs',
        { filtre: selectedJoueur.value },
      );

    joueurs.value = response.data
}
</script>