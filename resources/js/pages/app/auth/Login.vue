<script setup>
import { handleSubmit } from "@/helpers/client-req-handler";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

let form = useForm({
  company_code: window.CONFIG.APP_DEMO ? "shiftnet" : "",
  username: window.CONFIG.APP_DEMO ? "admin" : "",
  password: window.CONFIG.APP_DEMO ? "123456" : "",
  remember: true,
});

const submit = () => handleSubmit({ form, url: route("app.auth.login") });
const showPassword = ref(false);
</script>

<template>
  <i-head title="Login" />
  <guest-layout>
    <q-page class="row justify-center items-center">
      <div class="column">
        <div class="row">
          <q-form class="q-gutter-md" @submit.prevent="submit">
            <q-card square bordered class="q-pa-md shadow-1">
              <q-card-section class="text-center">
                <h5 class="q-my-sm">Masuk</h5>
              </q-card-section>
              <q-card-section>
                <q-input
                  v-model.trim="form.company_code"
                  label="Kode Perusahaan"
                  lazy-rules
                  :error="!!form.errors.company_code"
                  :error-message="form.errors.company_code"
                  :disable="form.processing"
                  :rules="[
                    (val) =>
                      (val && val.length > 0) || 'Masukkan Kode Perusahaan',
                  ]"
                  hide-bottom-space
                >
                  <template v-slot:append>
                    <q-icon name="domain" />
                  </template>
                </q-input>
                <q-input
                  v-model.trim="form.username"
                  label="Username"
                  lazy-rules
                  :error="!!form.errors.username"
                  autocomplete="username"
                  :error-message="form.errors.username"
                  :disable="form.processing"
                  :rules="[
                    (val) =>
                      (val && val.length > 0) || 'Masukkan alamat username',
                  ]"
                  hide-bottom-space
                >
                  <template v-slot:append>
                    <q-icon name="person" />
                  </template>
                </q-input>
                <q-input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  label="Kata Sandi"
                  :error="!!form.errors.password"
                  autocomplete="current-password"
                  :error-message="form.errors.password"
                  lazy-rules
                  :disable="form.processing"
                  :rules="[
                    (val) => (val && val.length > 0) || 'Masukkan kata sandi',
                  ]"
                  hide-bottom-space
                >
                  <template v-slot:append>
                    <q-btn
                      dense
                      flat
                      round
                      @click="showPassword = !showPassword"
                      ><q-icon :name="showPassword ? 'key_off' : 'key'"
                    /></q-btn>
                  </template>
                </q-input>
                <q-checkbox
                  class="q-mt-sm q-pl-none"
                  style="margin-left: -10px"
                  v-model="form.remember"
                  :disable="form.processing"
                  label="Ingat saya di perangkat ini"
                />
              </q-card-section>
              <q-card-actions>
                <div class="full-width">
                  <q-btn
                    icon="login"
                    type="submit"
                    color="primary"
                    class="full-width"
                    label="Login"
                    :disable="form.processing"
                  />
                </div>
              </q-card-actions>

              <q-card-section class="text-center q-pa-none q-mt-md">
                <p class="q-my-xs text-grey-7">
                  Belum punya akun?
                  <i-link :href="route('app.auth.register')">Daftar</i-link>
                </p>
                <p class="q-my-xs text-grey-7">
                  Lupa kata sandi?
                  <i-link :href="route('app.auth.forgot-password')"
                    >Atur ulang</i-link
                  >
                </p>
              </q-card-section>
            </q-card>
          </q-form>
        </div>
      </div>
    </q-page>
  </guest-layout>
</template>

<style>
.q-card {
  width: 360px;
}
</style>
