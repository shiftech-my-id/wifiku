<script setup>
import { formatNumber } from "@/helpers/formatter";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Layanan";
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #left-button>
      <div class="q-gutter-sm">
        <q-btn
          icon="arrow_back"
          dense
          color="grey-7"
          flat
          rounded
          @click="$inertia.get(route('app.product.index'))"
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
            $inertia.get(route('app.product.edit', { id: page.props.data.id }))
          "
        />
      </div>
    </template>
    <div class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <div class="row">
          <q-card square flat bordered class="col">
            <q-card-section>
              <div class="text-subtitle1 text-bold text-grey-9">Info Utama</div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width: 125px">Nama Layanan</td>
                    <td style="width: 1px">:</td>
                    <td>{{ page.props.data.name }}</td>
                  </tr>
                  <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td>{{ page.props.data.description }}</td>
                  </tr>
                  <tr>
                    <td>Periode Tagihan</td>
                    <td>:</td>
                    <td>
                      {{
                        $CONSTANTS.PRODUCT_BILL_PERIODS[
                          page.props.data.bill_period
                        ]
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td>Biaya Layanan</td>
                    <td>:</td>
                    <td>Rp. {{ formatNumber(page.props.data.price) }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                      {{ page.props.data.active ? "Aktif" : "Tidak Aktif" }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>
