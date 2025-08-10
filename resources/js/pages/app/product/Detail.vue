<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import MainInfo from "./partial/MainInfo.vue";
import ActivationHistory from "./partial/ActivationHistory.vue";
import BillHistory from "./partial/BillHistory.vue";

const page = usePage();
const title = "Detail Layanan";
const tab = ref("main");
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #left-button>
      <div class="q-gutter-sm">
        <q-btn
          icon="arrow_back"
          densef
          color="grey-7"
          flat
          rounded
          @click="router.get(route('app.party.index'))"
        />
      </div>
    </template>
    <template #right-button>
      <div class="q-gutter-sm">
        <q-btn
          icon="edit"
          dense
          color="primary"
          @click="
            router.get(route('app.party.edit', { id: page.props.data.id }))
          "
        />
      </div>
    </template>
    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <div class="row">
          <q-card square flat bordered class="col">
            <q-card-section>
              <q-tabs v-model="tab" align="left">
                <q-tab name="main" label="Info Utama" />
                <q-tab name="historyBill" label="Riwayat Tagihan" />
                <q-tab name="historyAktivation" label="Riwayat Ak" />
              </q-tabs>
              <q-tab-panels v-model="tab">
                <q-tab-panel name="main">
                  <main-info />
                </q-tab-panel>
                <q-tab-panel name="historyBill">
                  <bill-history />
                </q-tab-panel>

                <q-tab-panel name="historyActivation">
                  <activation-history />
                </q-tab-panel>
              </q-tab-panels>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page>
  </authenticated-layout>
</template>
