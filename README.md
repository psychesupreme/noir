# Noir & Bloom Atelier 🌸✨
### Luxury Floriculture ERP, Bespoke Curation & Logistics Platform

[![Laravel](https://img.shields.io/badge/Laravel-11%20%2F%2012-FF2D20.svg?style=flat&logo=laravel&logoColor=white)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6.svg?style=flat&logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4.svg?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![PostgreSQL](https://img.shields.io/badge/Database-PostgreSQL%20%2F%20SQLite-4169E1.svg?style=flat&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Compliance](https://img.shields.io/badge/Compliance-KRA%20eTIMS%20QR%20Vector-00A651.svg?style=flat)]()
[![Deployment](https://img.shields.io/badge/Deployed%20on-Fly.io-7A5AF8.svg?style=flat&logo=flydotio&logoColor=white)](https://noir-bloom-erp.fly.dev)

**Noir & Bloom Atelier** is a bespoke enterprise ERP, cold-chain inventory logistics, and floral curation system engineered for Kenya's luxury floriculture and gifting market. It pairs an artisan storefront experience with an enterprise operational backplane—managing raw flower stems, branch allocations, automated KRA eTIMS statutory tax invoicing, courier proof-of-delivery (PoD), and wastage controls.

---

## 🌟 Core System Capabilities

### 1. Luxury Floral Curation & Pricing Architecture
* **Stem & Arrangement Dynamics**: Supports single flower stems (e.g. Naivasha Red Roses @ KES 350), hand-tied bespoke bouquets (Standard KES 2,500 to Grand KES 12,000), and luxury curated hampers (up to KES 35,000).
* **Tiered Atelier Assembly Service**:
  * **Base / Small Curation**: KES 150
  * **Medium Curation**: KES 350
  * **Grand Luxury Curation**: KES 750
* **Add-on Accessory Integration**: Calligraphy greeting cards, handcrafted glass vases, and premium satin ribbons dynamically bundled with orders.

### 2. Statutory Compliance & KRA eTIMS Invoicing
* **Vector QR Code Generation**: Generates compliant Kenya Revenue Authority (KRA) eTIMS QR codes embedded directly into PDF invoices.
* **Automated PDF Engine**: Powered by Barryvdh DOMPDF with custom artisan styling and tax breakdown (16% VAT, zero-rated exports).
* **Transactional Email Delivery**: High-deliverability transactional order confirmations and eTIMS tax invoices dispatched via the official **Resend** driver (`resend/resend-laravel`).

### 3. Inventory, Spoilage & Cold-Chain Logistics
* **Multi-Branch Inventory**: Stock allocation across distinct branches, cold storage rooms, and florist design tables.
* **Vendor Purchase Orders**: Full vendor lifecycle management from purchase order requisition to receiving and stock replenishment.
* **Event-Driven Spoilage & Wastage Tracking**: Wilting, stem damage, and handling loss logged through the inventory portal.
* **Real-Time Storefront Invalidation**: `WastageLog::saved` model events trigger immediate invalidation via `StorefrontCacheService::flush()` to maintain strict live catalog availability.

### 4. Courier Logistics & Mobile Proof of Delivery (PoD)
* **Mobile Courier Portal** (`/courier/orders/{order}`): Lightweight responsive interface for delivery drivers.
* **Photo Proof of Delivery**: Direct camera upload verifying delivery condition and recipient handover.
* **Recipient Verification**: Digital signature capture, handover confirmation notes, and instant transition to `delivered` status.

### 5. CRM & Accounts Receivable (A/R)
* **B2B Corporate Accounts**: Deal pipeline tracking for corporate floral arrangements, hotel contracts, and wedding curations.
* **Accounts Receivable Ledger**: Invoice issuance, payment tracking, aged debt reporting, and customer lifetime value analytics.

---

## 🏗️ Consolidated Database Architecture

The database schema has been engineered into 4 clean domain baseline migrations to eliminate migration bloat:

```text
database/migrations/
├── 0001_01_01_000000_create_users_and_clients_tables.php
│   └── Users, password reset tokens, sessions, clients, personal access tokens, notifications, system logs, cache, jobs.
├── 2026_01_01_000001_create_products_branches_inventory_tables.php
│   └── Branches, products, occasions, occasion_product, branch_product_stock, inventory logs, vendors, purchase orders.
├── 2026_01_01_000002_create_orders_payments_etims_tables.php
│   └── Orders, order_product items, payment transactions, and eTIMS tax invoices.
└── 2026_01_01_000003_create_crm_ar_wastage_reviews_tables.php
    └── CRM deals, timeline logs, A/R invoices, A/R payments, wastage logs, and customer reviews.
```

---

## 💻 Local Setup & Development

### Prerequisites
* PHP 8.3+
* Composer
* Node.js 20+ & NPM
* PostgreSQL or SQLite

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/psychesupreme/noir.git
   cd noir
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations and seed realistic demo data:**
   ```bash
   npm run build
   php artisan migrate:fresh --seed
   ```

5. **Start development servers:**
   ```bash
   php artisan serve
   ```

**Default Admin Credentials:**
* **Email:** `admin@noirandbloom.co.ke`
* **Password:** `password`

---

## 🧪 Automated Testing

Run the automated test suite verifying Livewire components, inventory calculations, and order workflows:
```bash
php artisan test
```

---

## 🚀 Fly.io Production Deployment

The platform is containerized and configured for one-command deployment to **Fly.io**:

```bash
# Deploy container to Fly.io
fly deploy

# Run remote database migrations and seeds
fly ssh console -C "php artisan migrate:fresh --seed --force"
```

* **Live Staging / Demo**: [https://noir-bloom-erp.fly.dev](https://noir-bloom-erp.fly.dev)

---

## 📄 License
Proprietary & Confidential. All rights reserved.
