<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { getQueryParams } from "@/helpers/utils";
import { useQuasar } from "quasar";
import { formatDate, formatNumberWithSymbol } from "@/helpers/formatter";

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
  sortBy: "customer_date",
  descending: true,
});

const status_colors = {
  draft: "grey",
  unpaid: "orange",
  paid: "green",
  overdue: "red",
  cancelled: "black",
};

const columns = [
  {
    name: "customer_number",
    label: "Nomor Tagihan",
    field: "customer_number",
    align: "left",
    sortable: true,
  },
  {
    name: "customer_date",
    label: "Tgl. Tagihan",
    field: "customer_date",
    align: "left",
    sortable: true,
  },
  {
    name: "due_date",
    label: "Jatuh Tempo",
    field: "due_date",
    align: "left",
    sortable: true,
  },
  {
    name: "total_amount",
    label: "Jumlah",
    field: "total_amount",
    align: "right",
    sortable: true,
  },
  { name: "status", label: "Status", field: "status", align: "center" },
  { name: "action", align: "right" },
];

onMounted(() => fetchItems());

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus tagihan ${row.customer_number}?`,
    url: route("app.customer.delete", row.id),
    fetchItemsCallback: fetchItems,
    loading,
  });

const fetchItems = (props = null) =>
  handleFetchItems({
    pagination,
    filter,
    props,
    rows,
    url: route("app.customer.data"),
    loading,
  });

const onRowClicked = (row) =>
  router.get(route("app.customer.detail", { id: row.id }));

const computedColumns = computed(() =>
  $q.screen.gt.sm
    ? columns
    : columns.filter((col) => ["customer_number", "action"].includes(col.name))
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

      <template v-slot:no-data="{ message }">
        <div class="full-width row flex-center text-grey-8 q-gutter-sm q-pa-md">
          <q-icon name="receipt_long" size="2em" />
          <span> Riwayat tagihan masih kosong. </span>
        </div>
      </template>

      <template v-slot:body="props">
        <q-tr
          :props="props"
          class="cursor-pointer"
          @click="onRowClicked(props.row)"
        >
          <q-td key="customer_number" :props="props">
            <div>{{ props.row.customer_number }}</div>
            <div v-if="$q.screen.lt.md" class="q-mt-sm q-gutter-xs">
              <div>
                <q-icon name="event" /> Tgl:
                {{ formatDate(props.row.customer_date) }}
              </div>
              <div>
                <strong>{{
                  formatNumberWithSymbol(props.row.total_amount)
                }}</strong>
              </div>
              <q-badge :color="status_colors[props.row.status]">
                {{ props.row.status_label }}
              </q-badge>
            </div>
          </q-td>

          <q-td key="customer_date" :props="props">
            {{ formatDate(props.row.customer_date) }}
          </q-td>

          <q-td key="due_date" :props="props">
            {{ formatDate(props.row.due_date) }}
          </q-td>

          <q-td key="total_amount" :props="props">
            {{ formatNumberWithSymbol(props.row.total_amount) }}
          </q-td>

          <q-td key="status" :props="props">
            <q-badge :color="status_colors[props.row.status]">
              {{ props.row.status_label }}
            </q-badge>
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
                      @click.stop="
                        router.get(route('app.customer.edit', props.row.id))
                      "
                    >
                      <q-item-section avatar
                        ><q-icon name="edit"
                      /></q-item-section>
                      <q-item-section>Edit</q-item-section>
                    </q-item>
                    <q-item
                      clickable
                      v-ripple
                      v-close-popup
                      @click.stop="deleteItem(props.row)"
                    >
                      <q-item-section avatar
                        ><q-icon name="delete_forever"
                      /></q-item-section>
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
