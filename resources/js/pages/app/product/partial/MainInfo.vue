<script setup>
import { dateTimeFromNow, formatDatetime } from "@/helpers/formatter";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
  <div class="text-subtitle1 text-bold text-grey-8">Info Pelanggan</div>
  <table class="detail">
    <tbody>
      <tr>
        <td style="width: 120px">Nama</td>
        <td style="width: 1px">:</td>
        <td>{{ page.props.data.name }}</td>
      </tr>
      <tr>
        <td>No WhatsApp</td>
        <td>:</td>
        <td>{{ page.props.data.whatsapp || page.props.data.phone }}</td>
      </tr>
      <tr>
        <td>No Telepon</td>
        <td>:</td>
        <td>{{ page.props.data.phone }}</td>
      </tr>
      <tr>
        <td>No KTP</td>
        <td>:</td>
        <td>{{ page.props.data.ktp }}</td>
      </tr>
      <tr v-if="page.props.data.installation_date">
        <td>Tanggal Pemasangan</td>
        <td>:</td>
        <td>{{ formatDatetime(page.props.data.installation_date) }}</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ page.props.data.address }}</td>
      </tr>
      <tr>
        <td>Status</td>
        <td>:</td>
        <td>{{ page.props.data.active ? "Aktif" : "Tidak Aktif" }}</td>
      </tr>
      <tr>
        <td>Catatan</td>
        <td>:</td>
        <td>{{ page.props.data.notes }}</td>
      </tr>
      <tr v-if="page.props.data.created_at">
        <td>Dibuat</td>
        <td>:</td>
        <td>
          <template v-if="page.props.data.createdBy">
            oleh
            <my-link
              :href="
                route('app.user.detail', {
                  id: page.props.data.createdBy.id,
                })
              "
            >
              {{ page.props.data.createdBy.email }}
            </my-link>
          </template>
        </td>
      </tr>
      <tr v-if="page.props.data.updated_at">
        <td>Diperbarui</td>
        <td>:</td>
        <td>
          {{ dateTimeFromNow(page.props.data.updated_at) }} -
          {{ formatDatetime(page.props.data.updated_at) }}
          <template v-if="page.props.data.updatedBy">
            oleh
            <my-link
              :href="
                route('app.user.detail', {
                  id: page.props.data.updatedBy.id,
                })
              "
            >
              {{ page.props.data.updatedBy.email }}
            </my-link>
          </template>
        </td>
      </tr>
    </tbody>
  </table>
</template>
