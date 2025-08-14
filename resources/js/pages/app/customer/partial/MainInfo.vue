<script setup>
import {
  dateTimeFromNow,
  formatDate,
  formatDateTime,
  formatNumber,
} from "@/helpers/formatter";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
  <div class="text-subtitle1 text-bold text-grey-8">Info Pelanggan</div>
  <table class="detail">
    <tbody>
      <tr>
        <td style="width: 120px">Kode</td>
        <td style="width: 1px">:</td>
        <td>{{ page.props.data.code }}</td>
      </tr>
      <tr>
        <td>Nama</td>
        <td>:</td>
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
        <td>Tgl Pemasangan</td>
        <td>:</td>
        <td>{{ formatDate(page.props.data.installation_date) }}</td>
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
        <td>Nama Layanan</td>
        <td>:</td>
        <td>{{ page.props.data.product.name }}</td>
        <tr>
          <td>Harga</td>
          <td>:</td>
          <td>{{ formatNumber(page.props.data.product.price) }}</td>
        </tr>
        <tr>
          <td>Periode</td>
          <td>:</td>
          <td>
            {{
              $CONSTANTS.PRODUCT_BILL_PERIODS[
                page.props.data.product.bill_period
              ]
            }}
          </td>
        </tr>
        <tr>
          <td>Status Layanan</td>
          <td>:</td>
          <td>
            {{ page.props.data.product.active ? "Aktif" : "Tidak Aktif" }}
          </td>
        </tr>
      </template>
      <template v-else>
        <tr>
          <td colspan="3">Tidak ada layanan aktif.</td>
        </tr>
      </template>

      <tr>
        <td>
          <div class="text-subtitle1 text-bold text-grey-8">Info Rekaman</div>
        </td>
      </tr>
      <tr v-if="page.props.data.created_at">
        <td>Dibuat</td>
        <td>:</td>
        <td>
          {{ dateTimeFromNow(page.props.data.created_at) }} -
          {{ formatDateTime(page.props.data.created_at) }}
          <template v-if="page.props.data.created_by">
            oleh
            <my-link
              :href="
                route('app.user.detail', {
                  id: page.props.data.creator.id,
                })
              "
            >
              {{ page.props.data.creator.name }}
            </my-link>
          </template>
        </td>
      </tr>
      <tr v-if="page.props.data.updated_at">
        <td>Diperbarui</td>
        <td>:</td>
        <td>
          {{ dateTimeFromNow(page.props.data.updated_at) }} -
          {{ formatDateTime(page.props.data.updated_at) }}
          <template v-if="page.props.data.updated_by">
            oleh
            <my-link
              :href="
                route('app.user.detail', {
                  id: page.props.data.updater.id,
                })
              "
            >
              {{ page.props.data.updater.name }}
            </my-link>
          </template>
        </td>
      </tr>
    </tbody>
  </table>
</template>
