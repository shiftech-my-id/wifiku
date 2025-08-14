<script setup>
import { handleSubmit } from "@/helpers/client-req-handler";
import { formatDateTime } from "@/helpers/formatter";
import { scrollToFirstErrorField } from "@/helpers/utils";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const nameInputRef = ref();
const page = usePage();

const form = useForm({
  code: page.props.company.code,
  name: page.props.company.name,
  owner_name: page.props.company.owner_name,
  phone: page.props.company.phone,
  address: page.props.company.address,
  registration_datetime: formatDateTime(
    page.props.company.registration_datetime
  ),
});

const submit = () =>
  handleSubmit({ form, url: route("app.company-profile.update") });
</script>

<template>
  <q-form
    class="row"
    @submit.prevent="submit"
    @validation-error="scrollToFirstErrorField"
  >
    <q-card flat bordered square class="col">
      <q-card-section>
        <div class="text-subtitle1 q-my-xs">Profil Perusahaan</div>
        <p class="text-caption text-grey-9">Perbarui profil perusahaan.</p>
        <q-input
          v-model.trim="form.code"
          readonly
          label="Kode Perusahaan"
          :disable="form.processing"
        />
        <q-input
          ref="nameInputRef"
          hide-bottom-space
          v-model.trim="form.name"
          label="Nama Perusahaan"
          :disable="form.processing"
          lazy-rules
          :error="!!form.errors.name"
          :error-message="form.errors.name"
          :rules="[
            (val) => (val && val.length > 0) || 'Nama Perusahaan harus diisi.',
          ]"
        />
        <q-input
          v-model.trim="form.owner_name"
          hide-bottom-space
          label="Nama Pemilik"
          :disable="form.processing"
          lazy-rules
          :error="!!form.errors.owner_name"
          :error-message="form.errors.owner_name"
          :rules="[
            (val) => (val && val.length > 0) || 'Nama Pemilik harus diisi.',
          ]"
        />
        <q-input
          v-model.trim="form.phone"
          hide-bottom-space
          label="No Telepon"
          :disable="form.processing"
          lazy-rules
          :error="!!form.errors.phone"
          :error-message="form.errors.phone"
        />
        <q-input
          type="textarea"
          counter
          autogrow
          maxlength="1000"
          v-model.trim="form.address"
          label="Alamat"
          :disable="form.processing"
          lazy-rules
          :error="!!form.errors.address"
          :error-message="form.errors.address"
          hide-bottom-space
        />
        <q-input
          v-model.trim="form.registration_datetime"
          readonly
          label="Tanggal Registrasi"
          :disable="form.processing"
        />
      </q-card-section>
      <q-card-section>
        <q-btn
          icon="save"
          type="submit"
          color="primary"
          label="Simpan"
          :disable="form.processing"
        />
      </q-card-section>
    </q-card>
  </q-form>
</template>
