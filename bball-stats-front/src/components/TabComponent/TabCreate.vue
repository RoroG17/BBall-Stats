<template>
    <q-tabs
        v-model="tab"
        dense
        class="text-white bg-primary"
        active-color="white"
        indicator-color="white"
        align="justify"
        narrow-indicator
    >
        <q-tab name="match" label="Match"></q-tab>
        <q-tab name="equipe" label="Equipe"></q-tab>
        <q-tab name="saison" label="Saison"></q-tab>
    </q-tabs>

    <q-separator></q-separator>

    <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="match">
            <div class="text-h6 q-mb-md">Création d'un match</div>
            <q-separator />
            <match-formulaire class="q-mt-xl"/>
        </q-tab-panel>

        <q-tab-panel name="equipe">
            <div class="text-h6">Création d'une équipe</div>
            <q-separator />
            <equipe-formulaire class="q-mt-xl"/>
        </q-tab-panel>

        <q-tab-panel name="saison">
            <div class="text-h6">Création d'une saison</div>
            <q-separator />
            <saison-formulaire class="q-mt-xl"/>
        </q-tab-panel>
    </q-tab-panels>
</template>

<script setup lang="ts">
import { ref } from 'vue'
//import { MatchType } from '../types/MatchType';
import MatchFormulaire from '../FormulaireComponent/MatchFormulaire.vue'
import SaisonFormulaire from '../FormulaireComponent/SaisonFormulaire.vue'
import EquipeFormulaire from '../FormulaireComponent/EquipeFormulaire.vue'

const tab = ref('match')

</script>