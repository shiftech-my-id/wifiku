<script setup>
import { dateTimeFromNow, formatDateTime } from "@/helpers/formatter";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

// Helper untuk format mata uang Rupiah
const formatCurrency = (value) => {
  if (!value) return "Rp 0";
  // Mengonversi string ke angka untuk formatting
  const numberValue = Number(value);
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0, // Tidak menampilkan desimal
  }).format(numberValue);
};

// Helper untuk menerjemahkan periode
const translatePeriod = (period) => {
  if (period === "monthly") return "Bulanan";
  return period; // Kembalikan nilai asli jika bukan 'monthly'
};
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
        <td>{{ page.props.data.wa }}</td>
      </tr>
      <tr>
        <td>No KTP</td>
        <td>:</td>
        <td>{{ page.props.data.id_card_number }}</td>
      </tr>
      <tr v-if="page.props.data.installation_date">
        <td>Tanggal Pemasangan</td>
        <td>:</td>
        <td>{{ formatDateTime(page.props.data.installation_date) }}</td>
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
      <tr>
        <td>
          <div class="text-subtitle1 text-bold text-grey-8">Info Layanan</div>
        </td>
      </tr>
      <template v-if="page.props.data.product">
        <tr>
          <td>Nama Layanan</td>
          <td>:</td>
          <td>{{ page.props.data.product.name }}</td>
        </tr>
        <tr>
          <td>Harga</td>
          <td>:</td>
          <td>{{ formatCurrency(page.props.data.product.price) }}</td>
        </tr>
        <tr>
          <td>Periode</td>
          <td>:</td>
          <td>{{ translatePeriod(page.props.data.product.bill_period) }}</td>
        </tr>
        <tr>
          <td>Status Layanan</td>
          <td>:</td>
          <td>
            {{ page.props.data.product.active ? "Aktif" : "Tidak Aktif" }}
          </td>
        </tr>
      </template>

      <tr v-if="page.props.data.created_datetime">
        <td>Dibuat</td>
        <td>:</td>
        <td>
          {{ dateTimeFromNow(page.props.data.created_datetime) }} -
          {{ formatDateTime(page.props.data.created_datetime) }}
        </td>
      </tr>
      <tr v-if="page.props.data.updated_datetime">
        <td>Diperbarui</td>
        <td>:</td>
        <td>
          {{ dateTimeFromNow(page.props.data.updated_datetime) }} -
          {{ formatDateTime(page.props.data.updated_datetime) }}
          <template v-if="page.props.data.updated_by_user">
            oleh
            <my-link
              :href="
                route('app.user.detail', {
                  id: page.props.data.updated_by_user.id,
                })
              "
            >
              {{ page.props.data.updated_by_user.email }}
            </my-link>
          </template>
        </td>
      </tr>
    </tbody>
  </table>
</template>
