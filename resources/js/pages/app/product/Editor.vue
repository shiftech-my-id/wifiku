<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { scrollToFirstErrorField } from "@/helpers/utils";
import { createOptions } from "@/helpers/options";

const page = usePage();
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Pelanggan";
const types = createOptions(window.CONSTANTS.PARTY_TYPES);
const today = new Date().toLocaleDateString("en-CA");
const form = useForm({
  id: page.props.data.id,
  name: page.props.data.name,
  whatsapp: page.props.data.whatsapp,
  phone: page.props.data.phone,
  ktp: page.props.data.ktp,
  installation_date:
    page.props.data.installation_date || today.replaceAll("-", "/"),
  type: page.props.data.type,
  address: page.props.data.address,
  notes: page.props.data.notes,
  active: !!page.props.data.active,
});

const submit = () => handleSubmit({ form, url: route("app.party.save") });
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <q-page class="row justify-center">
      <div class="col col-md-6 q-pa-sm">
        <q-form
          class="row q-col-gutter-md"
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

              <q-input
                v-model="form.whatsapp"
                type="tel"
                label="No WhatsApp"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.whatsapp"
                :error-message="form.errors.whatsapp"
              />

              <q-input
                v-model.trim="form.phone"
                type="tel"
                label="No Telepon"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.phone"
                :error-message="form.errors.phone"
              />

              <q-input
                v-model="form.ktp"
                type="tel"
                label="No KTP"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.ktp"
                :error-message="form.errors.ktp"
              />

              <q-input
                filled
                v-model="form.installation_date"
                label="Tanggal Pemasangan"
                mask="date"
                :rules="['date']"
                :disable="form.processing"
                :error="!!form.errors.installation_date"
                :error-message="form.errors.installation_date"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date v-model="form.installation_date">
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Selesai"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>

              <q-input
                v-model.trim="form.address"
                type="textarea"
                autogrow
                counter
                maxlength="255"
                label="Alamat"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.address"
                :error-message="form.errors.address"
              />

              <q-input
                v-model.trim="form.notes"
                type="textarea"
                autogrow
                counter
                maxlength="255"
                label="Catatan"
                lazy-rules
                :disable="form.processing"
                :error="!!form.errors.notes"
                :error-message="form.errors.notes"
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
