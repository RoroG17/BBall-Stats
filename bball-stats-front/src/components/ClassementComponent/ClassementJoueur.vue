<template>
    <q-card class="q-pa-xs">
      <q-table
        :title="props.title"
        :rows="props.data"
        :columns="colonnes"
        row-key="licence"
        flat
        bordered
        separator="horizontal"
        :pagination="pagination"
        style="height: 500px;"
        hide-header
      >

        <template v-slot:top>  
          <div class="q-pa-sm" style="width: 100%;">
            <div class="q-gutter-sm row items-center">
              <q-icon name="sports_basketball" size="md" color="primary" />
              <div class="text-h6 text-primary">{{ props.title }}</div>
            </div>

            <q-separator color="red" class="q-mt-sm"/>
          </div>
        </template>

        
        <!-- Slot pour la photo -->
        <template v-slot:body-cell-photo="props">
        <q-td :props="props">
            <q-avatar>
            <img :src="`http://localhost:8000/storage/joueur/${props.row.photo}`" alt="Photo joueur" />
            </q-avatar>
        </q-td>
        </template>

        <!-- Slot pour la position -->
        <template v-slot:body-cell-position="props">
        <q-td :props="props">
            {{ props.pageIndex + 1 }}
        </q-td>
        </template>
      </q-table>
    </q-card>
  </template>
  
  <script lang="ts" setup>
  import type { QTableProps } from 'quasar';

  type Classement = {
    licence: string;
    nom: string;
    prenom: string;
    photo: string;
    valeur: number;
    total : number;
  }

  const props = defineProps<{
    title: string,
    data: Classement[]
    }>()
  
  
  const colonnes: QTableProps['columns'] = [
    { name: 'position', label: '#', align: 'left', field: 'position', sortable: false },
    { name: 'photo', label: '', align: 'center', field: 'photo', sortable: false },
    { name: 'nom', label: 'Nom', align: 'left', field: 'nom', sortable: true },
    { name: 'prenom', label: 'Prénom', align: 'left', field: 'prenom', sortable: true },
    { name: 'value' , label: props.title, align: 'right', field: 'valeur', sortable: true },
    { name: 'total' , label: props.title, align: 'right', field: 'total', sortable: true }
  ];
  
  const pagination = {
    rowsPerPage: 0
  };
  
  </script>
  