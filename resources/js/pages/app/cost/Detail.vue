<script setup>
import { formatDateTime } from "@/helpers/formatter";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Biaya Operasional";
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
          @click="router.get(route('app.cost.index'))"
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
            router.get(route('app.cost.edit', { id: page.props.data.id }))
          "
        />
      </div>
    </template>

    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <div class="row">
          <q-card square flat bordered class="col">
            <q-card-section>
              <div class="text-subtitle1 text-bold text-grey-8">
                Info Biaya Operasional
              </div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width: 125px">Waktu</td>
                    <td style="width: 1px">:</td>
                    <td>{{ formatDateTime(page.props.data.datetime) }}</td>
                  </tr>
                  <tr>
                    <td>Kategori</td>
                    <td>:</td>
                    <td>{{ page.props.data.category.name }}</td>
                  </tr>
                  <tr>
                    <td>Jumlah (Rp.)</td>
                    <td>:</td>
                    <td>{{ page.props.data.amount }}</td>
                  </tr>
                  <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td>{{ page.props.data.description }}</td>
                  </tr>
                  <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td>{{ page.props.data.notes }}</td>
                  </tr>
                </tbody>
              </table>
            </q-card-section>

            <q-card-section>
              <div class="text-subtitle1 text-bold text-grey-8">
                Informasi Ekstra
              </div>
              <table class="detail">
                <tbody>
                  <tr>
                    <td style="width: 125px">Dibuat</td>
                    <td style="width: 1px">:</td>
                    <td>
                      {{
                        $dayjs(new Date(page.props.data.created_at)).format(
                          "DD MMMM YY HH:mm:ss"
                        )
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td>Diperbarui</td>
                    <td>:</td>
                    <td>
                      {{
                        $dayjs(new Date(page.props.data.updated_at)).format(
                          "DD MMMM YY HH:mm:ss"
                        )
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page>
  </authenticated-layout>
</template>
