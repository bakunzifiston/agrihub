# WooCommerce Pre-order Integration

AgriHub exposes an API so a **WordPress WooCommerce** store can list farmer crops for **pre-order** and send orders back into AgriHub.

**→ What you need on your WordPress site:** see [WORDPRESS-SETUP.md](WORDPRESS-SETUP.md) (requirements, config, and minimal plugin examples).

## Data flow

1. **Farmers** in AgriHub create **Pre-order Listings** from:
   - **Crops** (planted/growing) with expected harvest date and expected yield, or
   - **Harvest outputs** (already harvested stock).
2. **WooCommerce** (or a WordPress plugin) calls the AgriHub API to **fetch active listings** and creates/updates products in the store.
3. **Customers** pre-order on the WooCommerce site.
4. **WooCommerce** sends an **order webhook** (or a script calls the API) to AgriHub with listing id, quantity, and customer details. AgriHub creates a **Pre-order** and effectively “reserves” quantity.
5. **Farmers** see pre-orders under **Pre-orders** in the AgriHub farmer dashboard.

## AgriHub API

Base URL: `http://sandbox.rw/api/woocommerce` (this project). For local testing: `http://127.0.0.1:8000/api/woocommerce`.

All requests must include the API token:

- **Header:** `Authorization: Bearer YOUR_WOOCOMMERCE_API_TOKEN`
- Or **query:** `?token=YOUR_WOOCOMMERCE_API_TOKEN`

Set `WOOCOMMERCE_API_TOKEN` in AgriHub’s `.env` and use the same value in WordPress.

### Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/listings` | List all active pre-order listings (for syncing as WooCommerce products). |
| GET | `/listings/{id}` | Single listing by AgriHub listing ID. |
| POST | `/orders` | Create a pre-order (call when a WooCommerce order is placed). |

### GET /listings

Response shape:

```json
{
  "data": [
    {
      "id": 1,
      "woocommerce_product_id": null,
      "title": "Maize (Yellow)",
      "quantity_available": 500,
      "available_to_sell": 500,
      "unit": "kg",
      "price_per_unit": 0.5,
      "expected_harvest_date": "2026-04-15",
      "farmer": {
        "id": 10,
        "name": "John Doe",
        "farm_name": "Green Valley",
        "location": "Kigali"
      }
    }
  ]
}
```

- **id** – AgriHub listing ID (use this when creating an order).
- **available_to_sell** – Quantity left after existing pre-orders; use this for WooCommerce “stock” or max order quantity.
- **woocommerce_product_id** – Optional; store your WooCommerce product ID here if you want to match by it when sending orders.

### POST /orders (webhook)

Request body (JSON):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| listing_id | integer | One of listing_id or woocommerce_product_id | AgriHub pre-order listing ID. |
| woocommerce_product_id | string | One of listing_id or woocommerce_product_id | If you stored it on the listing, you can send orders by this. |
| quantity | number | Yes | Quantity ordered. |
| woocommerce_order_id | string | No | WooCommerce order ID for reference. |
| customer_name | string | No | Buyer name. |
| customer_email | string | No | Buyer email. |
| customer_address | string | No | Delivery/address. |
| notes | string | No | Order notes. |

Example:

```json
{
  "listing_id": 1,
  "quantity": 25,
  "woocommerce_order_id": "12345",
  "customer_name": "Jane Buyer",
  "customer_email": "jane@example.com",
  "customer_address": "Kigali, Rwanda"
}
```

Responses:

- **201** – Pre-order created; body includes `id`, `listing_id`, `quantity`, `status`.
- **404** – Listing not found or inactive.
- **422** – Quantity exceeds `available_to_sell`.

## WooCommerce / WordPress side

### Option A: Custom plugin or script

1. **Sync products**  
   Periodically call `GET /api/woocommerce/listings` and create/update WooCommerce products (title, price from `price_per_unit`, stock from `available_to_sell`, custom field for `id` and `expected_harvest_date`).
2. **On order placed**  
   In WooCommerce (e.g. `woocommerce_checkout_order_processed` or order status hook), call `POST /api/woocommerce/orders` with the corresponding `listing_id` (or `woocommerce_product_id` if you store it), quantity, and customer data.

### Option B: REST API from WordPress

Use WordPress `wp_remote_get` / `wp_remote_post` with the same URLs and `Authorization: Bearer ...` header. Store the API base URL and token in options or constants.

### Option C: External cron + WooCommerce webhook

- A cron job calls AgriHub `GET /listings` and syncs to WooCommerce (e.g. via WooCommerce REST API or a small plugin).
- When an order is created in WooCommerce, use a “Webhook” to an endpoint that forwards to AgriHub `POST /api/woocommerce/orders` (the endpoint would need to map WooCommerce payload to the AgriHub fields above).

## AgriHub configuration

1. **.env**
   - `WOOCOMMERCE_API_TOKEN` – Long random string (e.g. `openssl rand -hex 32`). Use the same value in WordPress when calling the API.
2. **Run migrations**
   - `php artisan migrate` (creates `pre_order_listings` and `pre_orders` tables).
3. **Farmer workflow**
   - Farmers open **Marketplace (Pre-order)** → **Pre-order Listings**, add listings from crops or harvest outputs, set quantity and optional price and expected harvest date. They can toggle listings active/inactive and edit or delete (if no active pre-orders).

## Summary

- AgriHub is the **source of truth** for what can be pre-ordered (listings) and for reserved quantities (pre-orders).
- WooCommerce is the **storefront**; it pulls listings from the API and pushes orders back via `POST /orders`.
- Farmers manage listings and view pre-orders entirely inside AgriHub.
