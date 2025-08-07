<script setup>
import { defineComponent, onMounted, ref, watch, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useQuasar } from "quasar";

defineComponent({
  name: "AuthenticatedLayout",
});

const LEFT_DRAWER_STORAGE_KEY = "advanta-report.layout.left-drawer-open";
const $q = useQuasar();
const page = usePage();
const leftDrawerOpen = ref(
  JSON.parse(localStorage.getItem(LEFT_DRAWER_STORAGE_KEY) || "false")
);
const isDropdownOpen = ref(false);

const isToggleHovered = ref(false);
const showAsHoverButton = computed(() => $q.screen.gt.sm);
const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value;
  isToggleHovered.value = false;
};

const isAdminOrCashier = computed(() => {
  const userRole = page.props.auth.user.role;
  const CONSTANTS = page.props.constants;
  return (
    userRole === CONSTANTS.USER_ROLE_ADMIN ||
    userRole === CONSTANTS.USER_ROLE_CASHIER
  );
});

watch(leftDrawerOpen, (newValue) => {
  localStorage.setItem(LEFT_DRAWER_STORAGE_KEY, newValue);
});

onMounted(() => {
  if ($q.screen.lt.md) {
    leftDrawerOpen.value = false;
  }
});
</script>

<template>
  <q-layout view="lHh LpR lFf">
    <q-header>
      <q-toolbar class="bg-grey-1 text-black toolbar-scrolled">
        <q-btn
          v-if="!leftDrawerOpen"
          flat
          dense
          round
          aria-label="Menu"
          @click="toggleLeftDrawer"
          @mouseenter="isToggleHovered = true"
          @mouseleave="isToggleHovered = false"
        >
          <template v-if="showAsHoverButton">
            <q-icon
              v-if="isToggleHovered"
              class="material-symbols-outlined"
              name="dock_to_right"
            />
            <q-avatar v-else size="32px">
              <img src="/assets/img/app-logo-wifi.png" alt="Logo" />
            </q-avatar>
            <q-tooltip> Buka Menu </q-tooltip>
          </template>

          <template v-else>
            <q-icon name="menu" />
          </template>
        </q-btn>
        <slot name="left-button"></slot>
        <q-toolbar-title
          :class="{ 'q-ml-sm': leftDrawerOpen }"
          style="font-size: 18px"
        >
          <slot name="title">{{ $config.APP_NAME }}</slot>
        </q-toolbar-title>
        <slot name="right-button"></slot>
      </q-toolbar>
      <slot name="header"></slot>
    </q-header>
    <q-drawer
      :breakpoint="768"
      v-model="leftDrawerOpen"
      bordered
      class="bg-grey-2"
      style="color: #444"
    >
      <div
        class="absolute-top"
        style="
          height: 50px;
          border-bottom: 1px solid #ddd;
          align-items: center;
          justify-content: center;
        "
      >
        <div
          style="
            width: 100%;
            padding: 8px;
            display: flex;
            justify-content: space-between;
          "
        >
          <q-btn-dropdown
            v-model="isDropdownOpen"
            class="profile-btn text-bold"
            flat
            :label="$config.APP_NAME"
            style="
              justify-content: space-between;
              flex-grow: 1;
              overflow: hidden;
            "
            :class="{ 'profile-btn-active': isDropdownOpen }"
          >
            <q-list id="profile-btn-popup" style="color: #444">
              <q-item>
                <q-avatar style="margin-left: -15px"
                  ><q-icon name="person"
                /></q-avatar>
                <q-item-section>
                  <q-item-label>
                    <div class="text-bold">{{ page.props.auth.user.name }}</div>
                    <div class="text-grey-8 text-caption">
                      {{ page.props.auth.user.email }}
                    </div>
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-separator />
              <q-item
                v-close-popup
                class="subnav"
                clickable
                v-ripple
                :active="$page.url.startsWith('/admin/settings/profile')"
                @click="router.get(route('admin.profile.edit'))"
              >
                <q-item-section>
                  <q-item-label
                    ><q-icon name="manage_accounts" class="q-mr-sm" />
                    {{ $t("my_profile") }}</q-item-label
                  >
                </q-item-section>
              </q-item>
              <q-item
                v-close-popup
                class="subnav"
                clickable
                v-ripple
                :active="
                  $page.url.startsWith('/admin/settings/company-profile')
                "
                @click="router.get(route('admin.company-profile.edit'))"
              >
                <q-item-section>
                  <q-item-label
                    ><q-icon name="home_work" class="q-mr-sm" />
                    {{ $t("company_profile") }}</q-item-label
                  >
                </q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
          <q-btn
            v-if="leftDrawerOpen"
            flat
            dense
            aria-label="Menu"
            @click="toggleLeftDrawer"
          >
            <q-icon name="dock_to_right" />
          </q-btn>
        </div>
      </div>
      <q-scroll-area style="height: calc(100% - 50px); margin-top: 50px">
        <q-list id="main-nav" style="margin-bottom: 50px">
          <q-item
            clickable
            v-ripple
            :active="$page.url.startsWith('/app/dashboard')"
            @click="router.get(route('app.dashboard'))"
          >
            <q-item-section avatar>
              <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Dashboard</q-item-label>
            </q-item-section>
          </q-item>
          <q-separator />
          <q-item
            clickable
            v-ripple
            :active="$page.url.startsWith('/app/laporan')"
            @click="router.get(route('app.Laporan.index'))"
          >
            <q-item-section avatar>
              <q-icon name="request_quote" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Laporan</q-item-label>
            </q-item-section>
          </q-item>

          <q-item
            clickable
            v-ripple
            :active="$page.url.startsWith('/app/customer')"
            @click="router.get(route('app.customer.index'))"
          >
            <q-item-section avatar>
              <q-icon name="diversity_3" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Pelanggan</q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            clickable
            v-ripple
            :active="$page.url.startsWith('/app/product')"
            @click="router.get(route('app.product.index'))"
          >
            <q-item-section avatar>
              <q-icon name="construction" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Layanan</q-item-label>
            </q-item-section>
          </q-item>
          <q-separator />
          <q-expansion-item
            v-if="
              $page.props.auth.user.role == $CONSTANTS.USER_ROLE_ADMIN ||
              $page.props.auth.user.role == $CONSTANTS.USER_ROLE_CASHIER
            "
            icon="credit_score"
            label="Tagihan"
            :default-opened="
              $page.url.startsWith('/app/transaction-categories')
            "
          >
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/transaction-categories')"
              @click="router.get(route('app.transaction-category.index'))"
            >
              <q-item-section avatar>
                <q-icon name="credit_score" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Tagihan</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/transaction-categories')"
              @click="router.get(route('app.transaction-category.index'))"
            >
              <q-item-section avatar>
                <q-icon name="bolt" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Generate</q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>
          <q-expansion-item
            v-if="
              $page.props.auth.user.role == $CONSTANTS.USER_ROLE_ADMIN ||
              $page.props.auth.user.role == $CONSTANTS.USER_ROLE_CASHIER
            "
            icon="business_center"
            label="Oprasional"
            :default-opened="
              $page.url.startsWith('app/cost') ||
              $page.url.startsWith('app/cost-category')
            "
          >
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/cost')"
              @click="router.get(route('app.cost.index'))"
            >
              <q-item-section avatar>
                <q-icon name="business_center" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Biaya Oprasional</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/cost-category')"
              @click="router.get(route('app.cost-category.index'))"
            >
              <q-item-section avatar>
                <q-icon name="category" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Kategori Biaya</q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>

          <q-expansion-item
            v-if="
              $page.props.auth.user.role == $CONSTANTS.USER_ROLE_ADMIN ||
              $page.props.auth.user.role == $CONSTANTS.USER_ROLE_CASHIER
            "
            icon="tune"
            label="Sistem"
            :default-opened="
              $page.url.startsWith('/admin/sales-orders')
            "
          >
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/user')"
              @click="router.get(route('app.user.edit'))"
            >
              <q-item-section avatar>
                <q-icon name="person" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Pengguna</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/profile')"
              @click="router.get(route('app.profile.edit'))"
            >
              <q-item-section avatar>
                <q-icon name="diversity_2" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Grup Pengguna</q-item-label>
              </q-item-section>
            </q-item>
            <q-item
              class="subnav"
              clickable
              v-ripple
              :active="$page.url.startsWith('/app/settings/profile')"
              @click="router.get(route('app.profile.edit'))"
            >
              <q-item-section avatar>
                <q-icon name="settings" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Setting</q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>
          <q-separator />
          <div class="absolute-bottom text-grey-6 q-pa-md">
            &copy; 2025 -
            {{ $config.APP_NAME + " v" + $config.APP_VERSION_STR }}
          </div>
        </q-list>
      </q-scroll-area>
    </q-drawer>
    <q-page-container class="bg-grey-1">
      <q-page>
        <slot></slot>
      </q-page>
    </q-page-container>
    <slot name="footer"></slot>
  </q-layout>
</template>

<style>
.profile-btn span.block {
  text-align: left !important;
  width: 100% !important;
  margin-left: 10px !important;
}
</style>
<style scoped>
.q-toolbar {
  border-bottom: 1px solid transparent;
  /* Optional border line */
}

.toolbar-scrolled {
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
  /* Add shadow */
  border-bottom: 1px solid #ddd;
  /* Optional border line */
}

.profile-btn-active {
  background-color: #ddd !important;
}

#profile-btn-popup .q-item--active {
  color: inherit !important;
}
</style>
