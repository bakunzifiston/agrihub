# What You Need on Your WordPress Site

To connect your WordPress WooCommerce store to AgriHub for crop pre-orders, you need the following on the WordPress side.

**Example for this project:**
- **AgriHub app (Laravel):** [http://sandbox.rw/](http://sandbox.rw/)
- **AgriHub API URL:** `http://sandbox.rw/api/woocommerce` — use this in your WordPress plugin to fetch listings and send orders.
- **WordPress store (customers shop here):** [https://sandbox.redp.rw/](https://sandbox.redp.rw/)
- **Local testing:** `http://127.0.0.1:8000/api/woocommerce` when running AgriHub with `php artisan serve`.

---

## 1. Requirements

- **WordPress** (current version)
- **WooCommerce** installed and active
- **PHP** that can do HTTP requests (`wp_remote_get`, `wp_remote_post`) – standard in WordPress
- Your **AgriHub API base URL** and **API token** (from AgriHub `.env`: `WOOCOMMERCE_API_TOKEN`)

---

## 2. What to Configure in WordPress

Store these two values somewhere WordPress can read them (e.g. in **Settings** or **wp-config.php**):

| Setting | Example | Description |
|--------|---------|-------------|
| **AgriHub API URL** | `https://your-agrihub.com/api/woocommerce` | Base URL of the AgriHub WooCommerce API (no trailing slash). |
| **AgriHub API token** | Same value as `WOOCOMMERCE_API_TOKEN` in AgriHub `.env` | Used as `Authorization: Bearer <token>` for every request. |

**About Consumer key / Consumer secret:** Those are **WooCommerce** REST API keys (from WooCommerce → Settings → Advanced → REST API). The WordPress plugin that *calls AgriHub* does **not** use them. If you want **AgriHub (Laravel)** to call **WooCommerce** (e.g. push products from Laravel to the store), put them in AgriHub’s `.env` as `WOOCOMMERCE_STORE_URL`, `WOOCOMMERCE_CONSUMER_KEY`, and `WOOCOMMERCE_CONSUMER_SECRET` (see AgriHub `.env.example`).

You will use the AgriHub API URL and token in the plugin or script that syncs products and sends orders.

---

## 3. What the WordPress Side Must Do

Two things:

1. **Sync products from AgriHub**  
   Call AgriHub `GET /listings`, then create or update WooCommerce products (title, price from `price_per_unit`, stock from `available_to_sell`, and store AgriHub `id` so you can send it back when ordering).

2. **Send orders to AgriHub when a customer places an order**  
   When a WooCommerce order is created (or paid), call AgriHub `POST /orders` with:
   - `listing_id` = AgriHub listing ID (stored on the product)
   - `quantity` = quantity ordered
   - `woocommerce_order_id`, `customer_name`, `customer_email`, `customer_address` (optional but useful)

You can do this with a **small custom plugin** (recommended) or with a **scheduled task + webhook** that calls your own endpoint which then calls AgriHub.

---

## 4. Option A: Minimal Custom Plugin (recommended)

Create a WordPress plugin that:

- Adds a **Settings** page where you enter AgriHub API URL and API token.
- Provides a **“Sync from AgriHub”** button (or cron) that:
  - Calls `GET {api_url}/listings` with `Authorization: Bearer {token}`.
  - For each listing: create or update a WooCommerce product (title, price, “stock” from `available_to_sell`), and save the AgriHub listing `id` in product meta (e.g. `agrihub_listing_id`).
- Hooks into **WooCommerce order creation** (e.g. `woocommerce_checkout_order_processed` or `woocommerce_payment_complete`):
  - For each order line item, read `agrihub_listing_id` from the product.
  - Call `POST {api_url}/orders` with `listing_id`, `quantity`, and customer data.

### Example: Fetch listings (PHP)

```php
$api_url = get_option('agrihub_api_url');   // e.g. https://your-agrihub.com/api/woocommerce
$token   = get_option('agrihub_api_token');

$response = wp_remote_get($api_url . '/listings', [
    'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Content-Type'  => 'application/json',
    ],
]);

if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
    // Log error and abort
    return;
}

$body = json_decode(wp_remote_retrieve_body($response), true);
$listings = $body['data'] ?? [];
```

### Example: Create/update WooCommerce product per listing

```php
foreach ($listings as $item) {
    $listing_id = $item['id'];
    $title      = $item['title'];
    $price      = $item['price_per_unit'];
    $stock      = $item['available_to_sell'];
    $unit       = $item['unit'];
    $harvest    = $item['expected_harvest_date'] ?? '';

    // Find existing product by agrihub_listing_id meta
    $existing = get_posts([
        'post_type'   => 'product',
        'meta_key'    => 'agrihub_listing_id',
        'meta_value'  => $listing_id,
        'numberposts' => 1,
    ]);

    if (!empty($existing)) {
        $product = wc_get_product($existing[0]->ID);
    } else {
        $product = new WC_Product_Simple();
    }

    $product->set_name($title);
    $product->set_regular_price($price ?: 0);
    $product->set_manage_stock(true);
    $product->set_stock_quantity($stock);
    $product->set_sold_individually(false);
    $product->save();
    update_post_meta($product->get_id(), 'agrihub_listing_id', $listing_id);
    if ($harvest) {
        update_post_meta($product->get_id(), 'agrihub_expected_harvest', $harvest);
    }
}
```

### Example: Send order to AgriHub when WooCommerce order is placed

```php
add_action('woocommerce_checkout_order_processed', 'agrihub_send_order_to_agrihub', 10, 3);

function agrihub_send_order_to_agrihub($order_id, $posted_data, $order) {
    $api_url = get_option('agrihub_api_url');
    $token   = get_option('agrihub_api_token');
    if (!$api_url || !$token) return;

    $order = wc_get_order($order_id);
    if (!$order) return;

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $listing_id = get_post_meta($product->get_id(), 'agrihub_listing_id', true);
        if (!$listing_id) continue;

        $body = [
            'listing_id'            => (int) $listing_id,
            'quantity'               => (float) $item->get_quantity(),
            'woocommerce_order_id'   => (string) $order_id,
            'customer_name'          => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'customer_email'         => $order->get_billing_email(),
            'customer_address'       => $order->get_formatted_billing_address(),
        ];

        wp_remote_post($api_url . '/orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body' => json_encode($body),
        ]);
    }
}
```

You can run the sync on a **schedule** (e.g. every 15 minutes via cron) so stock and new listings stay in sync.

---

## 5. Option B: No Plugin – External Script + Webhook

If you prefer not to build a plugin:

1. **Sync products:** Run a script (PHP, Node, or cron job) that calls AgriHub `GET /listings` and then uses the **WooCommerce REST API** to create/update products and store `agrihub_listing_id` in meta/custom field.
2. **Send orders:** In WooCommerce, add a **Webhook** that fires on “Order created” (or “Order updated”) and points to a small **middleware** (e.g. a Laravel route or serverless function) that:
   - Receives the WooCommerce order payload.
   - Maps line items to AgriHub listing IDs (you must have stored them when syncing).
   - Calls AgriHub `POST /api/woocommerce/orders` for each relevant line.

AgriHub does not receive WooCommerce webhooks directly; you need that middleware to translate WooCommerce → AgriHub API.

---

## 6. WooCommerce Webhook form (if you use webhooks)

If you are setting up a **WooCommerce Webhook** (WooCommerce → Settings → Advanced → Webhooks → Add webhook), fill the form like this:

| Field | What to put |
|--------|-----------------------------|
| **Name** | Any label, e.g. `AgriHub Pre-orders`. |
| **Status** | **Active** so the webhook is sent. |
| **Topic** | **Order created** (or **Order updated** if you prefer to sync when orders change). |
| **Delivery URL** | **Not** AgriHub directly. WooCommerce sends its own JSON order payload, so use one of: (1) A URL in your **WordPress plugin** that receives this webhook, reads each line item’s product, gets `agrihub_listing_id` from product meta, and calls AgriHub `POST /api/woocommerce/orders` for each; or (2) A **middleware** (e.g. small script or Laravel route) that does the same. Example plugin URL: `https://sandbox.redp.rw/wp-json/agrihub/v1/order-webhook` (WordPress store). AgriHub API: `http://sandbox.rw/api/woocommerce`. (you implement this in the plugin). |
| **Secret** | Optional. A random string you choose; use the same value in your plugin/middleware to verify the webhook (e.g. `X-WC-Webhook-Signature`). You can leave blank if you don’t verify. |
| **API Version** | Use the default WooCommerce shows (e.g. **WP REST API Integration v3** or **wc/v3**). |

Important: AgriHub’s `POST /api/woocommerce/orders` expects a **simple body** (listing_id, quantity, customer_*), not WooCommerce’s full order JSON. So the **Delivery URL** must be something that converts the webhook payload into one or more calls to AgriHub (usually your WordPress plugin or a small middleware).

---

## 7. Checklist

| On WordPress | Done |
|--------------|------|
| WooCommerce installed | ☐ |
| AgriHub API URL and token stored (e.g. in plugin options) | ☐ |
| Sync from AgriHub: fetch listings → create/update products, save `agrihub_listing_id` | ☐ |
| On order: read `agrihub_listing_id` from products → call AgriHub POST /orders | ☐ |
| (Optional) Cron or button to run sync regularly | ☐ |
| (If using webhook) Delivery URL = plugin/middleware that then calls AgriHub | ☐ |

For full API details (request/response shapes, errors), see [WOOCOMMERCE-INTEGRATION.md](WOOCOMMERCE-INTEGRATION.md).
