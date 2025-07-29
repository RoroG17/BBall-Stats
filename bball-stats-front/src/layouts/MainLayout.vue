<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          BBall Stats
        </q-toolbar-title>

        <div>VT043904 - Romain Guérin</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      class="bg-primary text-white"
    >
      <q-list>
        <q-item-label
          header
          class="text-h5 text-secondary q-mb-md flex items-center"
        >
          <q-icon name="dashboard" class="q-mr-sm" />
          Tableau de Bord
        </q-item-label>

        <EssentialLink
          v-for="link in linksList"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import EssentialLink, { type EssentialLinkProps } from 'components/EssentialLink.vue';

const linksList: EssentialLinkProps[] = [
  {
    title: 'Accueil',
    icon: 'home',
    link: '/'
  },
  {
    title: 'Calendrier',
    icon: 'event',
    link: '/calendrier'
  },
  {
    title: 'Espace Joueur',
    icon: 'person',
    link: '/joueur'
  },
  {
    title: 'Espace Equipe',
    icon: 'sports_basketball',
    link: '/equipe'
  },
];

const leftDrawerOpen = ref(false);

function toggleLeftDrawer () {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>

<style scoped>
.titre {
  color: white;
  font-size: 19px;
  margin-top: 1em;
  margin-left: 2em;
  margin-bottom: 2em;
}
</style>
