<template>
    <div class="subcontent" style="width: 80%;">
        <div class="q-pa-md row items-center justify-between" style="width: 100%;">
          <!-- Colonne gauche -->
          <div>
            <q-btn label="Précédent" @click="onPrev" />
          </div>

          <!-- Colonne centrale (centrée) -->
          <div class="text-center">
            <q-btn label="Aujourd'hui" @click="onToday" />
          </div>

          <!-- Colonne droite --> 
          <div>
            <q-btn label="Suivant" @click="onNext" />
          </div>
        </div>

        <q-separator />
        <div class="q-pa-md">
            <p class="text-h6" style="text-align: center;">{{ monthName }} {{ currentDate.getFullYear() }}</p>
        </div>
  
      <div class="row justify-center">
        <div style="display: flex; width: 100%">
          <q-calendar-month
            ref="calendar"
            v-model="selectedDate"
            animated
            bordered
            focusable
            hoverable
            no-active-date
            :day-min-height="60"
            :day-height="0"
          >
            <template #day="{ scope }">
              <template v-for="event in eventsForDay(scope.timestamp.date)" :key="event.id">
                <div
                  :class="badgeClasses(event)"
                  :style="badgeStyles()"
                  class="row justify-start items-center no-wrap my-event"
                >
                  <q-icon :name="getIcon(event.type)" class="q-mr-xs"></q-icon>
                  <div class="title q-calendar__ellipsis">
                    {{ event.title }}
                    <q-tooltip v-if="event.description">{{ event.description }}</q-tooltip>
                  </div>
                </div>
              </template>
            </template>
          </q-calendar-month>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { QCalendarMonth } from '@quasar/quasar-ui-qcalendar'
  import '@quasar/quasar-ui-qcalendar/index.css'
  
  import { ref, computed } from 'vue'
  import type { EventType } from './types/EventType'
  
  // The function below is used to set up our demo data
  const currentDate = ref(new Date())

  const monthNames = [
    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
  ]
  const monthName = computed(() => monthNames[currentDate.value.getMonth()])
  
  const props = defineProps<{
    events: EventType[]
  }>()

  const calendar = ref<QCalendarMonth>(),
    selectedDate = ref(onToday())
  
  const getIcon = (title: string) => {
    switch (title) {
      case 'birthday':
        return 'cake'
      case 'match':
        return 'sports_basketball'
      default:
        return 'event'
    }
  }

  const getBgColor = (type: string) => {
    switch (type) {
      case 'birthday':
        return 'bg-orange'
      case 'match':
        return 'bg-blue'
      default:
        return 'bg-grey'
    }
  }

  function badgeClasses(event: EventType) {
    return {
      'text-white': true,
      [getBgColor(event.type)]: true, // Correction ici
      'q-calendar__ellipsis': true,
    }
  }
  
  function badgeStyles() {
    const s = {}
    // s.left = day.weekday === 0 ? 0 : (day.weekday * this.parsedCellWidth) + '%'
    // s.top = 0
    // s.bottom = 0
    // s.width = (event.days * this.parsedCellWidth) + '%'
    return s
  }
  
  function onToday() {
    if (calendar.value) {
      calendar.value.moveToToday()
    }
  }
  
    function onPrev() {
      if (calendar.value) {
        calendar.value.prev()
        currentDate.value.setMonth(currentDate.value.getMonth() - 1)
        currentDate.value = new Date(currentDate.value)
      }
    }
    function onNext() {
      if (calendar.value) {
        calendar.value.next()
        currentDate.value.setMonth(currentDate.value.getMonth() + 1)
        currentDate.value = new Date(currentDate.value)
      }
    }

  // Ajoute cette fonction pour filtrer les événements du jour
  function eventsForDay(dateStr: string) {
    return props.events.filter(ev => {
      // Format la date de l'événement en YYYY-MM-DD
      const evDate = ev.date instanceof Date
        ? ev.date.toISOString().slice(0, 10)
        : ev.date;
      return evDate === dateStr;
    });
  }
  </script>
  
  <style lang="scss" scoped>
  .my-event {
    position: relative;
    font-size: 12px;
    width: 100%;
    max-width: 100%;
    margin: 1px 0 0 0;
    padding: 0 2px;
    justify-content: start;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    cursor: pointer;
  }
  
  .title {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    max-width: 100%;
  }
  
  .text-white {
    color: white;
  }
  
  .bg-blue {
    background: blue;
  }
  
  .bg-green {
    background: green;
  }
  
  .bg-orange {
    background: orange;
  }
  
  .bg-red {
    background: red;
  }
  
  .bg-teal {
    background: teal;
  }
  
  .bg-grey {
    background: grey;
  }
  
  .bg-purple {
    background: purple;
  }
  
  .rounded-border {
    border-radius: 2px;
  }
  </style>
