<script setup>
import { ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { formatDateTimeForEditing } from "@/helpers/formatter";

import LocaleNumberInput from "@/components/LocaleNumberInput.vue";
import DateTimePicker from "@/components/DateTimePicker.vue";

const { props } = usePage();
const { data, categories: rawCategories } = props;

const title = `${data.id ? "Edit" : "Tambah"} Biaya Operasional`;

const form = useForm({
  id: data.id,
  category_id: data.category_id,
  company_id: data.company_id,
  datetime: formatDateTimeForEditing(data.datetime),
  notes: data.notes,
  description: data.description,
  amount: data.amount ? parseFloat(data.amount) : 0,
});

console.log(data.datetime);

const submit = () => handleSubmit({ form, url: route("app.cost.save") });

const categories = ref(
  (rawCategories ?? []).map(({ id, name }) => ({
    label: name ?? "",
    value: id,
  }))
);

const filteredCategories = ref([...categories.value]);

const filterCategories = (val, update) => {
  update(() => {
    const search = val?.toLowerCase() ?? "";
    filteredCategories.value = search
      ? categories.value.filter((c) => c.label.toLowerCase().includes(search))
      : [...categories.value];
  });
};
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
        @click="router.get(route('app.cost.index'))"
      />
    </template>

    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <q-form class="row" @submit.prevent="submit">
          <q-card square flat bordered class="col">
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <DateTimePicker
                v-model="form.datetime"
                label="Tanggal"
                :error="!!form.errors.datetime"
                :disable="form.processing"
              />

              <q-select
                v-model="form.category_id"
                label="Kategori"
                use-input
                input-debounce="300"
                clearable
                :options="filteredCategories"
                map-options
                emit-value
                @filter="filterCategories"
                :error="!!form.errors.category_id"
                :disable="form.processing"
              />

              <q-input
                v-model.trim="form.description"
                type="textarea"
                autogrow
                maxlength="1000"
                label="Deskripsi"
                :disable="form.processing"
                :error="!!form.errors.description"
                hide-bottom-space
              />

              <LocaleNumberInput
                v-model:modelValue="form.amount"
                label="Jumlah"
                :disable="form.processing"
                :error="!!form.errors.amount"
                :errorMessage="form.errors.amount"
                hide-bottom-space
              />
              <q-input
                v-model.trim="form.notes"
                type="textarea"
                autogrow
                maxlength="1000"
                label="Catatan"
                :disable="form.processing"
                :error="!!form.errors.notes"
                hide-bottom-space
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
