# Noir & Bloom ERP — Production Deployment Guide

This guide details the steps to build, configure, and deploy the Noir & Bloom luxury floral ERP onto cloud-native containerized hosting environments (such as Fly.io or generic Docker-compose platforms).

---

## 1. Local Production Validation (Docker Compose)

Before deploying to the cloud, validate the container build locally using Docker Compose.

### Create `docker-compose.yml`

Create a file named `docker-compose.yml` in the project root:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8080:80"
    environment:
      - APP_KEY=base64:YOUR_GENERATE_KEY_HERE
      - APP_ENV=production
      - APP_DEBUG=false
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - DB_PORT=3306
      - DB_DATABASE=noir_bloom
      - DB_USERNAME=noir_user
      - DB_PASSWORD=noir_password
      - RUN_MIGRATIONS=true
    depends_on:
      - db

  db:
    image: mysql:8.0
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      - MYSQL_DATABASE=noir_bloom
      - MYSQL_USER=noir_user
      - MYSQL_PASSWORD=noir_password
      - MYSQL_ROOT_PASSWORD=root_password
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

### Build and Run locally:
```bash
docker compose up --build -d
```
Access the application at `http://localhost:8080`.

---

## 2. Cloud Deployment (Fly.io)

We have configured `fly.toml` for seamless deployment to Fly.io.

### Step 1: Install Fly CLI and Login
```bash
# Install Fly CTL
powershell -Command "iwr https://fly.io/install.ps1 -useb | iex"

# Authenticate
fly auth login
```

### Step 2: Initialize Database and Secrets
Generate your production application key and add secrets:
```bash
# Create Fly app (using the name configured in fly.toml or dynamic)
fly apps create noir-bloom-erp

# Provision a managed MySQL database cluster on Fly
# (Or attach a database using Fly's extension or external provider like PlanetScale/Aiven)

# Configure required Laravel environment secrets
fly secrets set \
  APP_KEY="base64:$(php artisan key:generate --show)" \
  DB_CONNECTION=mysql \
  DB_HOST="<database-host-address>" \
  DB_DATABASE="<database-name>" \
  DB_USERNAME="<database-username>" \
  DB_PASSWORD="<database-password>"
```

### Step 3: Deploy Application
```bash
fly deploy
```

The startup entrypoint automatically:
- Caches configuration, routes, and views for lightning-fast Laravel performance.
- Runs database migrations under `RUN_MIGRATIONS=true`.
- Launches Nginx, PHP-FPM, and queue worker processes inside a managed Supervisor service.

---

## 3. Production Hardening Checklist

1. **Disable Debug Mode**: Ensure `APP_DEBUG=false` is always set in the production environment variables.
2. **Setup SSL**: Both Nginx configuration and Fly.io automatically route traffic via HTTPS. Do not disable HTTPS redirection.
3. **Queue Configuration**: Ensure the Laravel background worker (managed via Supervisor inside `docker/supervisord.conf`) is running to process transactional emails, M-Pesa ledger payment reconcile logs, and inventory updates.
