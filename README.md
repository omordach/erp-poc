# ERP PoC (Hybrid SaaS — Laravel 12 + Vue 3)

Modular-monolith ERP. Hybrid SaaS (multi-tenant + dedicated single-tenant).  
**Tenancy:** Spatie Laravel Multitenancy v4. **Auth:** Sanctum personal access tokens.  
**API:** Versioned JSON REST under `/api/v1` (SPA consumes strictly via API).

## ASCII Architecture

```
[Vue SPA] --HTTP JSON--> [/api/v1/*] --Laravel 12 (Sanctum)
                                |
                                +-- Modules/
                                    - Membership (Unions, Locals, Members)
                                    - Grievances
                                    - Events
                                    - Payments (Invoices, Payments)
                                |
                                +-- Core Events (MemberCreated) --> Payments Listener
                                |
                       [Spatie v4 Tenant Resolver]
                                |
           Landlord DB (tenants registry)  <->  Tenant DB (per-tenant schema)
```

## Tech Stack
- **Backend:** Laravel 12 (PHP 8.3), Sanctum, Spatie v4 Multitenancy
- **Frontend:** Vue 3 + Vite
- **DB:** MariaDB (latest stable) — **no SQLite**
- **Node:** 22.x
- **No Docker** (local dev via `bootstrap.sh`)
- **Deployment:** Laravel Envoy

---

## Ubuntu Setup

**Required versions**
- PHP 8.3 (`php -v`)
- Composer 2.x
- MariaDB 10.6+ (`mysql --version`)
- Node.js 22.x (`node -v`)
- npm 10+

**MariaDB prep**
```sql
CREATE DATABASE landlord CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'root'@'%' IDENTIFIED BY ''; -- or use your own creds and update .env
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
FLUSH PRIVILEGES;
```

**Clone & run**
```bash
git clone <this-repo> unionimpact-erp-poc
cd unionimpact-erp-poc
chmod +x bootstrap.sh
./bootstrap.sh
```

- Backend: http://127.0.0.1:8000  
- Frontend: http://127.0.0.1:5173

**Tenants created by default**
- `opeiu33` → DB: `tenant_opeiu33`
- `iatse100` → DB: `tenant_iatse100`

**Hosts / Subdomains (optional)**
For pure subdomain testing add to `/etc/hosts`:
```
127.0.0.1  opeiu33.example.local iatse100.example.local
```
Then hit backend as `http://opeiu33.example.local:8000` (Vite proxy stays on `127.0.0.1:5173`).  
During local dev you can also just set header `X-Tenant: <tenant-key>` (the SPA does this automatically via `TenantPicker`).

---

## Environment

Backend `.env` (auto-copied from `.env.example` by `bootstrap.sh`):
- `LANDLORD_DB_*` — landlord connection
- `TENANT_DB_*` — template for tenant connection (Spatie overrides `database` per-tenant)
- `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`

---

## Running Migrations/Seeders Manually

```bash
cd backend
php artisan migrate --database=landlord --path=database/migrations_landlord --force
php artisan db:seed --class="Database\Seeders\LandlordDatabaseSeeder" --force
php artisan tenants:migrate:poc --force
php artisan tenants:seed:poc --force
```

---

## API (JSON, `/api/v1/...`)

**Auth**
```
POST /api/v1/auth/token
Headers: X-Tenant: <tenant-key>
Body:    { "email":"admin@example.test", "password":"password", "device_name":"web-spa" }

200 OK
{ "data": { "type":"token", "attributes": { "token": "plain|token|here" } } }
```
Use the token as `Authorization: Bearer <token>` on all subsequent requests (SPA handles this).

**Common headers**
- `Authorization: Bearer <token>`
- `X-Tenant: <tenant-key>` (header fallback for dev; subdomain also works)

**Pagination**
Responses include:
```json
{
  "data": [/* resources */],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": { "current_page": 1, "last_page": 5, "per_page": 10, "total": 45 }
}
```

**Errors (examples)**
- `401`:
```json
{ "error": { "status": 401, "code": "UNAUTHENTICATED", "message": "Unauthenticated." } }
```
- `403` (RBAC or tenant mismatch):
```json
{ "error": { "status": 403, "code": "FORBIDDEN", "message": "Insufficient permissions." } }
```
- `403` (token vs tenant):
```json
{ "error": { "status": 403, "code": "TENANT_TOKEN_MISMATCH", "message": "Token is not valid for the current tenant." } }
```
- `404`:
```json
{ "error": { "status": 404, "code": "NOT_FOUND", "message": "Resource not found." } }
```

**Rate limiting**  
Applied via `throttle:60,1` per-token per-minute (PoC-level; swap to per-tenant Redis-based limiter in prod).

### Endpoints (selected)
- **Membership**
  - `GET /unions` (filter: `filter[name]`)
  - `POST /unions`
  - `GET /locals` (filter: `filter[union_id]`)
  - `POST /locals`
  - `GET /members` (filters: `filter[q]`, `filter[local_id]`)
  - `POST /members`
- **Grievances**
  - `GET /grievances` (filter: `filter[status]`)
  - `POST /grievances`
- **Events**
  - `GET /events` (filters: `filter[from]`, `filter[to]`)
  - `POST /events`
- **Payments**
  - `GET /invoices` (filter: `filter[status]`)
  - `POST /invoices`
  - `GET /payments` (filter: `filter[invoice_id]`)
  - `POST /payments`

---

## Frontend Usage

1. Open http://127.0.0.1:5173
2. Set tenant in top-right picker: `opeiu33` (or `iatse100`)
3. Login with:
   - `admin@example.test` / `password` (admin on all modules)
   - `member@example.test` / `password` (viewer)
4. Navigate modules; create/read data. The SPA sends `X-Tenant` and `Authorization` automatically.

---

## Why **Spatie v4** (default) vs **Stancl Tenancy**

**Spatie v4 pros**
- Very small surface area, explicit `TenantFinder`, easy to understand/extend
- Clear switch tasks for DB/cache/queue; good for modular monolith
- Works cleanly with Sanctum and per-tenant DB connections
- We need *separate DB per tenant* and a minimal PoC → Spatie fits with fewer moving parts

**Stancl Tenancy pros**
- Batteries-included multi-tenancy (routes, storage, identification strategies), great for advanced scenarios
- Strong community in Laravel SaaS

**Swap notes (Spatie → Stancl)**
- Replace dependency: `spatie/laravel-multitenancy` → `stancl/tenancy`
- Model: create `App\Models\Tenant` implementing `Stancl\Tenancy\Contracts\TenantWithDatabase`
- Tenancy bootstrapping: use Stancl’s middleware and `tenancy()` helpers
- Migrations: use `tenants:migrate` (Stancl) and move per-tenant migrations to `database/migrations/tenant`
- Tenant identification:
  - Stancl has built-in `DomainTenantIdentifier` and can read headers; configure `tenant_identification_middlewares`
- Token scoping:
  - Keep Sanctum column `tenant_id` and middleware check (works the same)
- Event/listener logic unchanged

---

## Limitations (PoC)

- No auditing/log trails (suggest: Laravel Auditing or custom domain events store)
- No background jobs/queues in PoC (suggest: Horizon + Redis, queue tenant-aware)
- No webhooks or external integrations (Stripe/Mailgun/Twilio left out)
- RBAC is simplified (module-level roles + optional union/local scope). Suggest: policies + permission tables or spatie/laravel-permission adapted per-tenant
- Multi-tenant caches/filesystems only partially wired (Spatie tasks added for DB/Cache; extend for filesystem/queue as needed)
- No CI/CD or TLS; Envoy script is a minimal deploy skeleton

---

## Next Steps

- Add **per-tenant rate limiter** via Redis keys: `rate:{tenant}:{route}`
- Introduce **CQRS/read models** for heavy lists; keep domain events (`MemberCreated`, etc.)
- Add **Jobs** for async reactions (e.g., invoice generation, notifications)
- Implement **audit log** tables per tenant
- Add **Module feature flags** (per-tenant toggles) and **Portal** distinctions (Staff, Member, Contractor) with guard separation
- Harden **TenantFinder** (prefer subdomain, reject missing tenant in prod)

---

## Calling the API from curl (quick smoke)

```bash
# Get token (header fallback tenant)
curl -s -H "X-Tenant: opeiu33" -X POST   -H "Content-Type: application/json"   -d '{"email":"admin@example.test","password":"password","device_name":"curl"}'   http://127.0.0.1:8000/api/v1/auth/token | jq -r .data.attributes.token

# Then:
TOKEN=<paste>
curl -s -H "X-Tenant: opeiu33" -H "Authorization: Bearer $TOKEN"   http://127.0.0.1:8000/api/v1/members | jq .
```

---

## Troubleshooting

- **SQLSTATE[HY000] [1049] Unknown database**  
  Run landlord seed again (it creates tenant DBs):  
  `php artisan db:seed --class="Database\Seeders\LandlordDatabaseSeeder" --force`
- **No tenant resolved**  
  Ensure `X-Tenant: <key>` header is set (SPA TenantPicker) or use subdomain.
- **401/403 on SPA**  
  Set tenant, log in again; tokens are tenant-stamped and won’t work across tenants.
