import {
  platformAndVendorOwnerOnly,
  platformOnly,
  platformVendorOwnerAndStaffOnly,
  vendorOwnerAndStaffOnly,
  vendorOwnerOnly,
} from '@/utils/auth-utils';
import { Routes } from '@/config/routes';

export const vendorSiteSettings = {
  name: 'Nuvia Beauty',
  description: '',
  logo: {
    url: '/loho.png',
    alt: 'Nuvia Beauty',
    href: '/',
    width: 160,
    height: 42,
  },
  collapseLogo: {
    url: '/loho.png',
    alt: 'Nuvia Beauty',
    href: '/',
    width: 32,
    height: 32,
  },
  defaultLanguage: 'en',
  author: {
    name: 'Nuvia Beauty',
    websiteUrl: 'https://github.com/Absolute-Martial',
    address: '',
  },
  headerLinks: [],
  authorizedLinks: [
    {
      href: Routes.profileUpdate,
      labelTransKey: 'authorized-nav-item-profile',
      icon: 'UserIcon',
      permission: platformVendorOwnerAndStaffOnly,
    },
    {
      href: Routes.settings,
      labelTransKey: 'authorized-nav-item-settings',
      icon: 'SettingsIcon',
      permission: platformOnly,
    },
    {
      href: Routes.logout,
      labelTransKey: 'authorized-nav-item-logout',
      icon: 'LogOutIcon',
      permission: platformVendorOwnerAndStaffOnly,
    },
  ],
  currencyCode: 'USD',
  sidebarLinks: {
    vendor: {
      root: {
        href: Routes.dashboard,
        label: 'Vendor',
        icon: 'DashboardIcon',
        childMenu: [
          {
            href: Routes.dashboard,
            label: 'sidebar-nav-item-dashboard',
            icon: 'DashboardIcon',
          },
        ],
      },
      shop: {
        href: '',
        label: 'text-vendor-shop-management',
        icon: 'ShopIcon',
        childMenu: [
          {
            href: Routes.vendorMyShops,
            label: 'sidebar-nav-item-my-shops',
            icon: 'MyShopIcon',
          },
        ],
      },
      product: {
        href: '',
        label: 'text-vendor-product-management',
        icon: 'ProductsIcon',
        childMenu: [
          {
            href: Routes.product.list,
            label: 'sidebar-nav-item-products',
            icon: 'ProductsIcon',
            childMenu: [
              {
                href: Routes.product.list,
                label: 'text-all-products',
                icon: 'ProductsIcon',
              },
              {
                href: Routes.product.create,
                label: 'text-new-products',
                icon: 'ProductsIcon',
              },
              {
                href: Routes.draftProducts,
                label: 'text-my-draft',
                icon: 'ProductsIcon',
              },
              {
                href: Routes.outOfStockOrLowProducts,
                label: 'text-all-out-of-stock',
                icon: 'ProductsIcon',
              },
            ],
          },
          {
            href: Routes.productInventory,
            label: 'text-inventory',
            icon: 'InventoryIcon',
          },
        ],
      },
      order: {
        href: Routes.order.list,
        label: 'text-vendor-order-management',
        icon: 'OrdersIcon',
        childMenu: [
          {
            href: Routes.order.list,
            label: 'sidebar-nav-item-orders',
            icon: 'OrdersIcon',
          },
          {
            href: Routes.order.create,
            label: 'sidebar-nav-item-create-order',
            icon: 'CreateOrderIcon',
          },
          {
            href: Routes.transaction,
            label: 'text-transactions',
            icon: 'TransactionsIcon',
          },
        ],
      },
      team: {
        href: '',
        label: 'text-vendor-team-management',
        icon: 'UsersIcon',
        childMenu: [
          {
            href: Routes.myStaffs,
            label: 'sidebar-nav-item-my-staffs',
            icon: 'UsersIcon',
          },
          {
            href: Routes.vendorStaffs,
            label: 'sidebar-nav-item-vendor-staffs',
            icon: 'UsersIcon',
          },
        ],
      },
      support: {
        href: '',
        label: 'text-vendor-support',
        icon: 'ChatIcon',
        childMenu: [
          {
            href: Routes.message.list,
            label: 'sidebar-nav-item-message',
            icon: 'ChatIcon',
          },
          {
            href: Routes.storeNotice.list,
            label: 'sidebar-nav-item-store-notice',
            icon: 'StoreNoticeIcon',
          },
        ],
      },
      finance: {
        href: '',
        label: 'text-vendor-finance-management',
        icon: 'WithdrawIcon',
        childMenu: [
          {
            href: Routes.withdraw.list,
            label: 'sidebar-nav-item-withdraws',
            icon: 'WithdrawIcon',
            permission: vendorOwnerOnly,
          },
        ],
      },
    },
    vendorDashboard: [
      {
        href: Routes.dashboard,
        label: 'sidebar-nav-item-dashboard',
        icon: 'DashboardIcon',
        permissions: vendorOwnerAndStaffOnly,
      },
      {
        href: Routes.vendorDashboardMyShop,
        label: 'common:sidebar-nav-item-my-shops',
        icon: 'MyShopOwnerIcon',
        permissions: vendorOwnerAndStaffOnly,
      },
      {
        href: Routes.vendorDashboardMessage,
        label: 'common:sidebar-nav-item-message',
        icon: 'ChatOwnerIcon',
        permissions: vendorOwnerAndStaffOnly,
      },
      {
        href: Routes.vendorDashboardNotice,
        label: 'common:sidebar-nav-item-store-notice',
        icon: 'StoreNoticeOwnerIcon',
        permissions: vendorOwnerAndStaffOnly,
      },
    ],
  },
  product: {
    placeholder: '/product-placeholder.svg',
  },
  avatar: {
    placeholder: '/avatar-placeholder.svg',
  },
};
