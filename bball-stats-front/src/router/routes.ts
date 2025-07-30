import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/AccueilPage.vue') }],
  },
  {
    path: '/calendrier',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/CalendrierPage.vue') }],
  },
  {
    path: '/match',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/MatchCreation.vue') }],
  },
  {
    path: '/effectif',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/EffectifPage.vue') }],
  },

  {
    path: '/joueur',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/JoueurCreation.vue') }],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
