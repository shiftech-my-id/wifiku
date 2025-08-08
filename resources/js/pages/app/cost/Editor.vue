<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import LocaleNumberInput from "@/components/LocaleNumberInput.vue";
import DatePicker from "@/components/DatePicker.vue";
import { ref } from "vue";

const page = usePage();
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Biaya Operasional";


const form = useForm({
  id: page.props.data.id,
  category_id: page.props.data.category_id,
  company_id: page.props.data.company_id,
  datetime: page.props.data.datetime,
  notes: page.props.data.notes,
  amount: parseFloat(page.props.data.amount),
  });

const submit = () => handleSubmit({ form, url: route("app.cost.save") });

// const Companies = ref(page.props.companies || []);
// const filteredCompanies = ref(Companies.value);


// const filterCategories = (val, update, abort) => {
//   update(() => {
//     const needle = val.toLowerCase();
//     filteredCategories.value = categories.value.filter(
//       (v) => v.label.toLowerCase().indexOf(needle) > -1
//     );
//   });
// };

// const filterCompanies = (val, update, abort) => {
//   update(() => {
//     const needle = val.toLowerCase();
//     filteredCompanies.value = Companies.value.filter(
//       (v) => v.label.toLowerCase().indexOf(needle) > -1
//     );
//   });
// };
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #left-button>
      <div class="q-gutter-sm">
        <q-btn icon="arrow_back" dense color="grey-7" flat rounded void:
        @click="get(route('app.cost.index'))" />
      </div>
    </template>

    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <q-form
          class="row"
          @submit.prevent="submit"
          @validation-error="scrollToFirstErrorField"
        >
          <q-card square flat bordered class="col">
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-select
                autofocus
                v-model="form.category_id"
                label="Kategori"
                use-input
                input-debounce="300"
                clearable
                :options="filteredCategories"
                map-options
                emit-value
                :error="!!form.errors.category_id"
                :disable="form.processing"
              >
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section>Kategori tidak ditemukan</q-item-section>
                  </q-item>
                </template>
              </q-select>
              <q-select
                autofocus
                v-model="form.company_id"
                label="Perusahaan"
                use-input
                input-debounce="300"
                clearable
                :options="filteredCompanies"
                map-options
                emit-value
                @filter="filterCompanies"
                :error="!!form.errors.company_id"
                :disable="form.processing"
              >
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section>Perusahaan tidak ditemukan</q-item-section>
                  </q-item>
                </template>
              </q-select>
              <date-picker
                v-model="form.date"
                label="Tanggal"
                :error="!!form.errors.date"
                :disable="form.processing"
              />

              <LocaleNumberInput
                v-model:modelValue="form.amount"
                label="Jumlah"
                lazyRules
                :disable="form.processing"
                :error="!!form.errors.amount"
                :errorMessage="form.errors.amount"
                :rules="[]"
              />
              <q-input
                v-model.trim="form.notes"
                type="textarea"
                autogrow
                counter
                maxlength="1000"
                label="Catatan"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.notes"
                :error-message="form.errors.notes"
              />
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
                @click="router.get(route('app.cost.index'))"
              />
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
