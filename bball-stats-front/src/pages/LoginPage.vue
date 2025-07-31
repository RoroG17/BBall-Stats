<template>
    <q-page class="flex flex-center bg-grey-2">
      <q-card class="q-pa-lg shadow-2" style="width: 100%; max-width: 400px;">
        <q-card-section class="text-center">
          <div class="text-h5 q-mb-sm">Connexion</div>
          <q-icon name="lock" size="42px" color="primary" />
        </q-card-section>
  
        <q-form @submit="handleLogin" class="q-gutter-md">
          <q-input
            filled
            v-model="email"
            label="Email"
            type="email"
            required
            lazy-rules
            :rules="[val => !!val || 'Veuillez entrer un email']"
          />
  
          <q-input
            filled
            v-model="password"
            label="Mot de passe"
            type="password"
            required
            lazy-rules
            :rules="[val => !!val || 'Veuillez entrer un mot de passe']"
          />
  
          <div class="row items-center q-mt-sm">
            <q-btn label="Se connecter" type="submit" color="primary" class="full-width" />
          </div>
        </q-form>
  
        <q-card-section class="text-center q-mt-sm">
          <q-btn flat label="Mot de passe oublié ?" size="sm" color="primary" />
        </q-card-section>
      </q-card>
    </q-page>
  </template>
  
  <script setup lang="ts">
  import { useQuasar } from 'quasar'
import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  
  const $q = useQuasar()
  const email = ref('')
  const password = ref('')
  const router = useRouter()
  
  async function handleLogin () {
    try {
    // Exemple de logique d'authentification (à adapter à ton backend)
        if (email.value === 'admin@example.com' && password.value === 'secret') {
        await router.push('/dashboard') // redirige après connexion réussie
        } else {
        $q.notify({
            type: 'negative',
            message: 'Email ou mot de passe incorrect',
        })
        }
    } catch (error) {
        console.log(error)
    }
  }
  </script>
  