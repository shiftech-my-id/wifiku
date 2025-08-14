<script setup>
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Grup Pengguna";
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
          @click="$inertia.get(route('app.user-group.index'))"
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
            $inertia.get(
              route('app.user-group.edit', { id: page.props.data.id })
            )
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
                    <td style="width: 125px">Nama Grup</td>
                    <td style="width: 1px">:</td>
                    <td>{{ page.props.data.name }}</td>
                  </tr>
                  <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td>{{ page.props.data.description }}</td>
                  </tr>

                  <!-- <tr>
                    FIXME: cek ulang di database apakah punya timestamp???
                    <td>Dibuat</td>
                    <td>:</td>
                    <td>
                      {{
                        $dayjs(page.props.data.created_datetime).format(
                          "DD MMMM YY HH:mm:ss"
                        )
                      }}
                    </td>
                  </tr> -->
                </tbody>
              </table>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>
