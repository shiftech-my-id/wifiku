<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { getQueryParams } from "@/helpers/utils";
import { useQuasar } from "quasar";
import { formatDate } from "@/helpers/formatter"; 

const $q = useQuasar();
const rows = ref([]);
const loading = ref(true);
const page = usePage();

const filter = reactive({
  customer_id: page.props.data.id,
  ...getQueryParams(),
});

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "activation_date",
  descending: true,
});

const status_colors = {
  pending: "orange",
  active: "green",
  inactive: "red",
  cancelled: "grey",
};

const columns = [
  { name: "id", label: "#", field: "id", align: "left", sortable: true },
  { name: "activation_date", label: "Tgl. Aktivasi", field: "activation_date", align: "left", sortable: true },
  { name: "service_name", label: "Layanan", field: "service_name", align: "left", sortable: true },
  { name: "technician_name", label: "Teknisi", field: "technician_name", align: "left", sortable: true },
  { name: "status", label: "Status", field: "status", align: "center" },
  { name: "action", align: "right" },
];

onMounted(() => fetchItems());

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus riwayat aktivasi untuk layanan ${row.service_name}?`,
    url: route("app.activation.delete", row.id),
    fetchItemsCallback: fetchItems,
    loading,
  });

const fetchItems = (props = null) =>
  handleFetchItems({
    pagination,
    filter,
    props,
    rows,
    url: route("app.activation.data"),
    loading,
  });

const onRowClicked = (row) =>
  router.get(route("app.activation.detail", { id: row.id }));

const computedColumns = computed(() =>
  $q.screen.gt.sm
    ? columns
    : columns.filter((col) => ["id", "action"].includes(col.name))
);
</script>

<template>
  <div class="q-pa-none">
    <q-table
      flat
      bordered
      square
      color="primary"
      row-key="id"
      v-model:pagination="pagination"
      :loading="loading"
      :columns="computedColumns"
      :rows="rows"
      :rows-per-page-options="[10, 25, 50]"
      @request="fetchItems"
      binary-state-sort
    >
      <template v-slot:loading>
        <q-inner-loading showing color="primary" />
      </template>

      <template v-slot:no-data>
        <div class="full-width row flex-center text-grey-8 q-gutter-sm q-pa-md">
          <q-icon name="history_toggle_off" size="2em" />
          <span> Riwayat aktivasi masih kosong. </span>
        </div>
      </template>

      <template v-slot:body="props">
        <q-tr
          :props="props"
          class="cursor-pointer"
          @click="onRowClicked(props.row)"
        >
          <q-td key="id" :props="props">
            <div>#{{ props.row.id }}</div>
            <div v-if="$q.screen.lt.md" class="q-mt-sm q-gutter-xs text-grey-8">
              <div>
                <q-icon name="rss_feed" class="q-mr-xs" />{{ props.row.service_name }}
              </div>
              <div>
                <q-icon name="event" class="q-mr-xs" />{{ formatDate(props.row.activation_date) }}
              </div>
              <q-badge :color="status_colors[props.row.status]">
                {{ props.row.status_label }}
              </q-badge>
            </div>
          </q-td>

          <q-td key="activation_date" :props="props">
            {{ formatDate(props.row.activation_date) }}
          </q-td>

          <q-td key="service_name" :props="props">
            {{ props.row.service_name }}
          </q-td>

          <q-td key="technician_name" :props="props">
            {{ props.row.technician_name }}
          </q-td>

          <q-td key="status" :props="props">
            <q-badge :color="status_colors[props.row.status]">
              {{ props.row.status_label }} </q-badge>
          </q-td>

          <q-td key="action" :props="props">
            <div class="flex justify-end">
              <q-btn icon="more_vert" dense flat @click.stop>
                <q-menu anchor="bottom right" self="top right">
                  <q-list style="min-width: 150px">
                    <q-item
                      clickable
                      v-ripple
                      v-close-popup
                      @click.stop="router.get(route('app.activation.edit', props.row.id))"
                    >
                      <q-item-section avatar><q-icon name="edit" /></q-item-section>
                      <q-item-section>Edit</q-item-section>
                    </q-item>
                    <q-item
                      clickable
                      v-ripple
                      v-close-popup
                      @click.stop="deleteItem(props.row)"
                    >
                      <q-item-section avatar><q-icon name="delete_forever" /></q-item-section>
                      <q-item-section>Hapus</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
            </div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>
