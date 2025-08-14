<script setup>
import { computed, onMounted, reactive, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { getQueryParams } from "@/helpers/utils";
import { useQuasar } from "quasar";
import {
  createMonthOptions,
  createBillPeriodOptions,
  createYearOptions,
} from "@/helpers/options";
import { usePageStorage } from "@/composables/usePageStorage";

const storage = usePageStorage("product");
const title = "Layanan";
const page = usePage();
const $q = useQuasar();
const showFilter = ref(storage.get("show-filter", false));
const rows = ref([]);
const loading = ref(true);

const currentYear = new Date().getFullYear();

const years = [
  { label: "Semua Tahun", value: "all" },
  { label: `${currentYear}`, value: currentYear },
  ...createYearOptions(currentYear - 2, currentYear - 1).reverse(),
];

const months = [
  { value: "all", label: "Semua Bulan" },
  ...createMonthOptions(),
];

const formatCurrency = (value) => {
  if (value === null || value === undefined) return "";
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(value);
};

const billPeriodOptions = createBillPeriodOptions(true);

const filter = reactive(
  storage.get("filter", {
    search: "",
    bill_period: "all",
    active: "all",
    ...getQueryParams(),
  })
);

const pagination = ref(
  storage.get("pagination", {
    page: 1,
    rowsPerPage: 10,
    rowsNumber: 10,
    sortBy: "id",
    descending: true,
  })
);

const columns = [
  {
    name: "name",
    label: "Nama Layanan",
    field: "name",
    align: "left",
    sortable: true,
  },
  {
    name: "description",
    label: "Deskripsi",
    field: "description",
    align: "left",
    sortable: true,
  },
  {
    name: "bill_period",
    label: "Periode Tagihan",
    field: "bill_period",
    align: "center",
    sortable: true,
  },
  {
    name: "price",
    label: "Biaya",
    field: "price",
    align: "right",
    format: (val) => formatCurrency(val),
    sortable: true,
  },
  {
    name: "active",
    label: "Status",
    field: "active",
    align: "right",
    format: (val) => (val ? "Aktif" : "Nonaktif"),
  },
  { name: "action", align: "right" },
];
const statuses = [
  { value: "all", label: "Semua" },
  { value: "active", label: "Aktif" },
  { value: "inactive", label: "Tidak Aktif" },
];

onMounted(() => {
  fetchItems();
});

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus layanan "${row.name}"?`,
    url: route("app.product.delete", row.id),
    fetchItemsCallback: fetchItems,
    loading,
  });

const fetchItems = (props = null) => {
  handleFetchItems({
    pagination,
    filter,
    props,
    rows,
    url: route("app.product.data"),
    loading,
  });
};

const onFilterChange = () => {
  pagination.value.page = 1;
  fetchItems();
};

const computedColumns = computed(() => {
  if ($q.screen.gt.sm) return columns;
  return columns.filter((col) => col.name === "name" || col.name === "action");
});

watch(
  () => filter.search,
  () => {
    onFilterChange();
  }
);

watch(showFilter, () => storage.set("show-filter", showFilter.value), {
  deep: true,
});
watch(filter, () => storage.set("filter", filter), { deep: true });
watch(pagination, () => storage.set("pagination", pagination.value), {
  deep: true,
});
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <template #right-button>
      <q-btn
        icon="add"
        dense
        color="primary"
        @click="router.get(route('app.product.add'))"
      />
      <q-btn
        class="q-ml-sm"
        :icon="!showFilter ? 'filter_alt' : 'filter_alt_off'"
        color="grey"
        dense
        @click="showFilter = !showFilter"
      />
    </template>
    <template #header v-if="showFilter">
      <q-toolbar class="filter-bar">
        <div class="row q-col-gutter-xs items-center q-pa-sm full-width">
          <q-select
            class="custom-select col-xs-12 col-sm-2"
            style="min-width: 150px"
            v-model="filter.status"
            :options="statuses"
            label="Status"
            dense
            map-options
            emit-value
            outlined
            @update:model-value="onFilterChange"
          />
          <q-input
            class="col"
            outlined
            dense
            debounce="300"
            v-model="filter.search"
            placeholder="Cari nama atau deskripsi..."
            @update:model-value="onFilterChange"
            clearable
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
      </q-toolbar>
    </template>
    <div class="q-pa-sm">
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
          <div class="full-width row flex-center text-grey-8 q-gutter-sm">
            <span>{{ message }}</span>
          </div>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props">
            <template v-if="$q.screen.gt.sm">
              <q-td key="name" :props="props">
                {{ props.row.name }}
              </q-td>
              <q-td key="description" :props="props">
                {{ props.row.description }}
              </q-td>
              <q-td key="bill_period" :props="props">
                {{ $CONSTANTS.PRODUCT_BILL_PERIODS[props.row.bill_period] }}
              </q-td>
              <q-td key="price" :props="props">
                {{ formatCurrency(props.row.price) }}
              </q-td>
              <q-td key="active" :props="props" class="text-center">
                <q-badge
                  :color="props.row.active ? 'positive' : 'negative'"
                  text-color="white"
                >
                  {{ props.row.active ? "Aktif" : "Nonaktif" }}
                </q-badge>
              </q-td>
            </template>

            <template v-else>
              <q-td key="name" :props="props">
                <div class="text-weight-bold">{{ props.row.name }}</div>
                <div v-if="props.row.bill_period" class="text-grey-8">
                  <q-icon name="event_repeat" size="xs" />
                  {{ props.row.bill_period }}
                </div>
                <div class="text-grey-8">
                  <q-icon name="payments" size="xs" />
                  {{ formatCurrency(props.row.price) }}
                </div>
                <div>
                  <q-badge
                    :color="props.row.active ? 'positive' : 'negative'"
                    text-color="white"
                  >
                    {{ props.row.active ? "Aktif" : "Nonaktif" }}
                  </q-badge>
                </div>
              </q-td>
            </template>

            <q-td key="action" :props="props">
              <div class="flex justify-end">
                <q-btn
                  icon="more_vert"
                  dense
                  flat
                  style="height: 40px; width: 30px"
                  @click.stop
                >
                  <q-menu
                    anchor="bottom right"
                    self="top right"
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-list style="width: 200px">
                      <q-item
                        clickable
                        v-ripple
                        v-close-popup
                        @click.stop="
                          router.get(
                            route('app.product.duplicate', props.row.id)
                          )
                        "
                      >
                        <q-item-section avatar>
                          <q-icon name="file_copy" />
                        </q-item-section>
                        <q-item-section> Duplikat </q-item-section>
                      </q-item>
                      <q-item
                        clickable
                        v-ripple
                        v-close-popup
                        @click.stop="
                          router.get(route('app.customer.edit', props.row.id))
                        "
                      >
                        <q-item-section avatar>
                          <q-icon name="edit" />
                        </q-item-section>
                        <q-item-section>Edit</q-item-section>
                      </q-item>
                      <q-item
                        @click.stop="deleteItem(props.row)"
                        clickable
                        v-ripple
                        v-close-popup
                        class="text-negative"
                      >
                        <q-item-section avatar>
                          <q-icon name="delete_forever" />
                        </q-item-section>
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
  </authenticated-layout>
</template>
