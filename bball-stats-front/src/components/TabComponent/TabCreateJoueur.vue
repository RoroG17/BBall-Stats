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
        <q-tab name="joueur" label="Joueur"></q-tab>
        <q-tab name="equipe" label="Equipe"></q-tab>
    </q-tabs>

    <q-separator></q-separator>

    <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="joueur">
            <div class="text-h6 q-mb-md">Création d'un Joueur</div>
            <q-separator />
            <joueur-formulaire class="q-mt-xl"/>
        </q-tab-panel>

        <q-tab-panel name="equipe">
            <div class="text-h6">Création d'une équipe</div>
            <q-separator />
            <equipe-formulaire class="q-mt-xl"/>
        </q-tab-panel>
    </q-tab-panels>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import JoueurFormulaire from '../FormulaireComponent/JoueurFormulaire.vue'
import EquipeFormulaire from '../FormulaireComponent/EquipeFormulaire.vue'

const tab = ref('joueur')

</script>