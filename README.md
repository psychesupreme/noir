# Noir & Bloom Atelier | Luxury Floral ERP & Curation Platform

Noir & Bloom is an enterprise ERP, logistics, and bespoke floral curation system built for Kenya's luxury floriculture market.

---

## 1. Architectural Highlights

- **Framework**: Laravel 11 / 12 & Livewire 3
- **Database**: PostgreSQL / SQLite with 4 Squashed Domain Baseline Migrations
- **Email Driver**: Resend Official Driver (`resend/resend-laravel`)
- **PDF & Tax Invoicing**: Barryvdh DOMPDF & KRA eTIMS QR Code Vector Integration
- **Caching**: Tagged Storefront & Catalog Caching with Event-Driven Model Invalidation (`StorefrontCacheService`)
- **Media Engine**: Direct High-Performance Unsplash CDN Image Integration (`w=600&q=80`) — No external Unsplash API keys required

---

## 2. Kenyan Market Pricing Structure (KES)

- **Single Flower Stems**: KES 250 – KES 450 per stem (e.g. Naivasha Red Roses @ KES 350)
- **Bespoke Hand-tied Bouquets**:
  - **Standard**: KES 2,500
  - **Deluxe**: KES 5,500
  - **Grand**: KES 12,000
- **Luxury Hampers & Giftings**: KES 6,500 – KES 35,000
- **Atelier Hand Curation Service Fee**:
  - **Base / Small Curation**: KES 150
  - **Medium Curation**: KES 350
  - **Grand Luxury Curation**: KES 750
- **Add-on Accessories**:
  - **Calligraphy Greeting Card**: KES 200
  - **Glass Vase**: KES 1,200
  - **Premium Satin Ribbon**: KES 150

---

## 3. Database Migration Baseline Architecture

The database schema has been consolidated into 4 clean domain migrations:

1. `0001_01_01_000000_create_users_and_clients_tables.php`
   - Handles `users`, `password_reset_tokens`, `sessions`, `clients`, `personal_access_tokens`, `notifications`, `system_logs`, `cache`, `jobs`.
2. `2026_01_01_000001_create_products_branches_inventory_tables.php`
   - Handles `branches`, `products`, `occasions`, `occasion_product`, `branch_product_stock`, `inventory_logs`, `vendors`, `purchase_orders`, `purchase_order_items`.
3. `2026_01_01_000002_create_orders_payments_etims_tables.php`
   - Handles `orders`, `order_product`, `payments`, `etims_invoices`.
4. `2026_01_01_000003_create_crm_ar_wastage_reviews_tables.php`
   - Handles `deals`, `crm_timeline_logs`, `accounts_receivable_invoices`, `accounts_receivable_payments`, `wastage_logs`, `reviews`.

---

## 4. Local Setup & Seeding

```bash
composer install
npm install && npm run build
php artisan migrate:fresh --seed
php artisan test
```

Default Admin Credentials:
- **Email**: `admin@noirandbloom.co.ke`
- **Password**: `password`
