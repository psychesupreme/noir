# Fly.io Production Deployment Guide

## 1. Environment & Infrastructure Requirements

- **Fly.io Machine Memory**: Shared CPU 1x, 512MB RAM minimum
- **Port Binding**: Internal Port `8080`
- **Database Engine**: SQLite / PostgreSQL
- **Media Engine**: Direct Unsplash CDN integration (`https://images.unsplash.com/photo-[ID]?auto=format&fit=crop&w=600&q=80`) — No external Unsplash API keys or API rate limits required.

---

## 2. Baseline Database Migrations

Database schema uses 4 consolidated domain baseline migrations:
1. `0001_01_01_000000_create_users_and_clients_tables.php`
2. `2026_01_01_000001_create_products_branches_inventory_tables.php`
3. `2026_01_01_000002_create_orders_payments_etims_tables.php`
4. `2026_01_01_000003_create_crm_ar_wastage_reviews_tables.php`

---

## 3. Remote Production Commands

### Container Deployment
```bash
fly deploy
```

### Database Migration & Seeding
```bash
fly ssh console -C "php artisan migrate:fresh --seed --force"
```

### Production Access
- **URL**: [https://noir-bloom-erp.fly.dev/login](https://noir-bloom-erp.fly.dev/login)
- **Admin User**: `admin@noirandbloom.co.ke`
- **Password**: `password`
