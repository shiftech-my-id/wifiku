<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { scrollToFirstErrorField } from "@/helpers/utils";
import LocaleNumberInput from "@/components/LocaleNumberInput.vue";
import { createBillPeriodOptions } from "@/helpers/options";

const page = usePage();
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Layanan";

const form = useForm({
  id: page.props.data.id ?? null,
  company_id: page.props.data.company_id ?? null,
  name: page.props.data.name ?? "",
  description: page.props.data.description ?? "",
  bill_period: page.props.data.bill_period ?? "bulanan",
  price: page.props.data.price ?? 0,
  active: page.props.data.active ?? true,
});

const billPeriodOptions = createBillPeriodOptions();

const submit = () => handleSubmit({ form, url: route("app.product.save") });
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #left-button>
      <q-btn
        icon="arrow_back"
        dense
        color="grey-7"
        flat
        rounded
        @click="router.get(route('app.product.index'))"
      />
    </template>
    <q-page class="row justify-center">
      <div class="col col-md-6 q-pa-sm">
        <q-form
          class="row"
          @submit.prevent="submit"
          @validation-error="scrollToFirstErrorField"
        >
          <q-card square flat bordered class="col">
            <q-inner-loading :showing="form.processing">
              <q-spinner size="50px" color="primary" />
            </q-inner-loading>

            <q-card-section class="q-pt-md">
              <input type="hidden" name="id" v-model="form.id" />
              <input
                type="hidden"
                name="company_id"
                v-model="form.company_id"
              />

              <q-input
                autofocus
                v-model.trim="form.name"
                label="Nama Layanan"
                lazy-rules
                :error="!!form.errors.name"
                :disable="form.processing"
                :error-message="form.errors.name"
                :rules="[(val) => !!val || 'Nama harus diisi.']"
              />

              <LocaleNumberInput
                v-model="form.price"
                label="Harga (Rp)"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.price"
                :error-message="form.errors.price"
                :rules="[(val) => val >= 0 || 'Harga tidak boleh negatif.']"
              />

              <q-select
                v-model="form.bill_period"
                :options="billPeriodOptions"
                label="Periode Tagihan"
                emit-value
                map-options
                :disable="form.processing"
                :error="!!form.errors.bill_period"
                :error-message="form.errors.bill_period"
              />

              <q-input
                v-model.trim="form.description"
                type="textarea"
                autogrow
                counter
                maxlength="255"
                label="Deskripsi"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.description"
              />

              <div style="margin-left: -10px">
                <q-checkbox
                  class="full-width q-pl-none"
                  v-model="form.active"
                  :disable="form.processing"
                  label="Aktif"
                />
              </div>
            </q-card-section>

            <q-card-section class="q-gutter-sm">
              <q-btn
                icon="save"
                type="submit"
                label="Simpan"
                color="primary"
                :disable="form.processing"
              />
              <q-btn
                icon="cancel"
                label="Batal"
                :disable="form.processing"
                @click="$goBack()"
              />
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
