<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { scrollToFirstErrorField } from "@/helpers/utils";
import { createOptions } from "@/helpers/options";
import DatePicker from "@/components/DatePicker.vue";
import { ref } from "vue";

const page = usePage();
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Pelanggan";
const today = new Date().toLocaleDateString("en-CA");
const form = useForm({
  id: page.props.data.id,
  company_id: page.props.data.company_id,
  product_id: page.props.data.product_id,
  code: page.props.data.code,
  name: page.props.data.name,
  wa: page.props.data.wa,
  phone: page.props.data.phone,
  id_card_number: page.props.data.id_card_number,
  installation_date:
    page.props.data.installation_date || today.replaceAll("-", "/"),
  type: page.props.data.type,
  address: page.props.data.address,
  notes: page.props.data.notes,
  active: !!page.props.data.active,
});
const showAllFields = ref(false);

function toggleFields() {
  showAllFields.value = !showAllFields.value;
}
// console.log(page.props.products);
const advanceMode = ref({
  active: false,
});
const submit = () => handleSubmit({ form, url: route("app.customer.save") });

const products = ref(
  (page.props.products ?? []).map(({ id, name }) => ({
    label: name ?? "",
    value: id,
  }))
);

const filteredProducts = ref([...products.value]);

const filterProducts = (val, update) => {
  update(() => {
    const search = val?.toLowerCase() ?? "";
    filteredProducts.value = search
      ? products.value.filter((c) => c.label.toLowerCase().includes(search))
      : [...products.value];
  });
};
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
          @click="router.get(route('app.customer.index'))"
        />
      </div>
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

              <q-input
                v-if="form.id"
                v-model="form.id"
                label="Id Pelanggan"
                readonly
                disable
              />

              <q-input
                autofocus
                v-model.trim="form.name"
                label="Nama"
                lazy-rules
                :error="!!form.errors.name"
                :disable="form.processing"
                :error-message="form.errors.name"
                :rules="[(val) => !!val || 'Nama harus diisi.']"
              />
              <date-picker
                v-model="form.installation_date"
                label="Tanggal Pemasangan"
                :disable="form.processing"
                :error="!!form.errors.installation_date"
                :error-message="form.errors.installation_date"
              />
              <!-- <q-select
                v-model="form.product_id"
                label="Layanan"
                use-input
                input-debounce="300"
                clearable
                :options="filteredProducts"
                map-options
                emit-value
                @filter="filterProducts"
                :error="!!form.errors.product_id"
                :disable="form.processing"
              /> -->
              <q-input
                v-model="form.wa"
                type="tel"
                label="No WhatsApp"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.wa"
                :error-message="form.errors.wa"
              />
              <q-btn
                label="mode simple"
                flat
                type="button"
                color="secondary"
                :icon="showAllFields ? 'arrow_drop_up' : 'arrow_drop_down'"
                @click="toggleFields"
              />

              <transition name="slide-fade">
                <div v-if="showAllFields">
                  <q-input
                    v-model.trim="form.address"
                    type="textarea"
                    autogrow
                    maxlength="255"
                    label="Alamat"
                    lazy-rules
                    :disable="form.processing"
                    :error="!!form.errors.address"
                    :error-message="form.errors.address"
                  />
                  <q-input
                    v-model="form.id_card_number"
                    type="tel"
                    label="No KTP"
                    lazy-rules
                    :disable="form.processing"
                    :error="!!form.errors.id_card_number"
                    :error-message="form.errors.id_card_number"
                  />
                  <q-input
                    v-model.trim="form.notes"
                    type="textarea"
                    autogrow
                    maxlength="255"
                    label="Catatan"
                    lazy-rules
                    :disable="form.processing"
                    :error="!!form.errors.notes"
                    :error-message="form.errors.notes"
                  />
                </div>
              </transition>

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

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease-in-out;
}

.slide-fade-leave-active {
  transition: all 0.1s ease-in-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
