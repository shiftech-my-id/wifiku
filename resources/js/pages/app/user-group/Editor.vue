<script setup>
import { handleSubmit } from "@/helpers/client-req-handler";
import { router, useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
const title = !!page.props.data.id
  ? "Edit Grup Pengguna"
  : "Tambah Grup Pengguna";
const form = useForm({
  id: page.props.data.id,
  name: page.props.data.name,
  description: page.props.data.description,
});

const submit = () => handleSubmit({ form, url: route("app.user-group.save") });
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
          @click="$inertia.get(route('admin.user-group.index'))"
        />
      </div>
    </template>
    <div class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <q-form class="row" @submit.prevent="submit">
          <q-card square flat bordered class="col">
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-input
                autofocus
                v-model.trim="form.name"
                label="Nama Grup"
                lazy-rules
                :error="!!form.errors.name"
                :disable="form.processing"
                :error-message="form.errors.name"
                :rules="[
                  (val) => (val && val.length > 0) || 'Nama grup harus diisi.',
                ]"
                hide-bottom-space
              />
              <q-input
                v-model.trim="form.description"
                type="textarea"
                autogrow
                counter
                maxlength="500"
                label="Deskripsi"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.description"
                :error-message="form.errors.description"
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
                @click="submit"
              />
              <q-btn
                icon="cancel"
                label="Batal"
                class="text-black"
                :disable="form.processing"
                @click="router.get(route('admin.user.index'))"
              />
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </div>
  </authenticated-layout>
</template>
