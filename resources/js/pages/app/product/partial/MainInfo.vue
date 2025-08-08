<script setup>
import { dateTimeFromNow, formatDatetime } from "@/helpers/formatter";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
  <div class="text-subtitle1 text-bold text-grey-8">Detail Layanan</div>
  <table class="detail">
    <tbody>
      <tr>
        <td>Nama Layanan</td>
        <td>:</td>
        <td>{{ page.props.data.service_name }}</td>
      </tr>
      <tr>
        <td>Pelanggan</td>
        <td>:</td>
        <td>
          <my-link
            :href="
              route('app.customer.detail', { id: page.props.data.customer_id })
            "
          >
            {{ page.props.data.customer_name }}
          </my-link>
        </td>
      </tr>
      <tr>
        <td>Kategori</td>
        <td>:</td>
        <td>{{ page.props.data.category?.label }}</td>
      </tr>
      <tr v-if="page.props.data.installation_date">
        <td>Tanggal Pemasangan</td>
        <td>:</td>
        <td>{{ formatDatetime(page.props.data.installation_date) }}</td>
      </tr>
      <tr>
        <td>Harga</td>
        <td>:</td>
        <td>{{ page.props.data.price }}</td>
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
    </tbody>
  </table>
</template>
