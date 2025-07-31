<template>
    <q-layout view="lHh Lpr lFf">
      <!-- En-tête -->
      <q-header elevated>
        <q-toolbar>
          <q-toolbar-title class="q-ml-xl">
            BBall-Stat
          </q-toolbar-title>
        </q-toolbar>
      </q-header>
  
      <!-- Contenu -->
      <q-page-container>
        <router-view />
      </q-page-container>
    </q-layout>
  </template>
  
  <script setup lang="ts">
  // pas besoin de code ici pour ce layout basique
  </script>
  