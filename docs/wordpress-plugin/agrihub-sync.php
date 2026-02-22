<?php
/**
 * Plugin Name: AgriHub Sync
 * Description: Syncs pre-order listings from AgriHub to WooCommerce and sends orders back to AgriHub.
 * Version: 1.0.0
 * Author: AgriHub
 * Requires at least: 5.0
 * Requires Plugins: woocommerce
 * WC requires at least: 5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AGRIHUB_SYNC_VERSION', '1.0.0');
define('AGRIHUB_SYNC_META_KEY', 'agrihub_listing_id');
define('AGRIHUB_SYNC_HARVEST_META', 'agrihub_expected_harvest');

/**
 * Check WooCommerce is active.
 */
add_action('admin_init', function () {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>AgriHub Sync</strong> requires WooCommerce to be installed and active.</p></div>';
        });
        deactivate_plugins(plugin_basename(__FILE__));
    }
});

/**
 * Register settings.
 */
add_action('admin_init', function () {
    register_setting('agrihub_sync_settings', 'agrihub_api_url', [
        'type'              => 'string',
        'sanitize_callback'  => 'esc_url_raw',
    ]);
    register_setting('agrihub_sync_settings', 'agrihub_api_token', [
        'type'              => 'string',
        'sanitize_callback'  => 'sanitize_text_field',
    ]);
});

/**
 * Add settings page.
 */
add_action('admin_menu', function () {
    add_options_page(
        'AgriHub Sync',
        'AgriHub Sync',
        'manage_options',
        'agrihub-sync',
        'agrihub_sync_render_settings_page'
    );
});

/**
 * Enqueue admin scripts for sync button.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'settings_page_agrihub-sync') {
        return;
    }
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'agrihubSync', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('agrihub_sync'),
    ]);
    wp_add_inline_script('jquery', "
        jQuery(function($) {
            $('#agrihub-preview').on('click', function(e) {
                e.preventDefault();
                var btn = $(this);
                var box = $('#agrihub-preview-results');
                box.hide().empty();
                btn.prop('disabled', true).text('Loading...');
                $.post(ajaxurl, {
                    action: 'agrihub_preview_listings',
                    nonce: (typeof agrihubSync !== 'undefined' && agrihubSync.nonce) ? agrihubSync.nonce : ''
                }).done(function(r) {
                    if (r.success && r.data) {
                        var list = r.data.listings || [];
                        if (list.length === 0) {
                            box.html('<p><em>No active listings in AgriHub. Add listings in AgriHub first.</em></p>');
                        } else {
                            var html = '<table class=\"widefat striped\"><thead><tr><th style=\"width:40px;\">Approve</th><th>Product</th><th>Qty / Unit</th><th>Price</th><th>Harvest</th><th>Farmer</th></tr></thead><tbody>';
                            function esc(s) { return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\"/g,'&quot;'); }
                            for (var i = 0; i < list.length; i++) {
                                var item = list[i];
                                var farmer = item.farmer || {};
                                html += '<tr><td><input type=\"checkbox\" class=\"agrihub-approve\" value=\"' + esc(item.id) + '\" checked /></td><td>' + esc(item.title) + '</td><td>' + esc(item.available_to_sell) + ' ' + esc(item.unit) + '</td><td>' + esc(item.price_per_unit) + '</td><td>' + esc(item.expected_harvest_date || '-') + '</td><td>' + esc(farmer.name) + '</td></tr>';
                            }
                            html += '</tbody></table><p class=\"description\">Check to approve (sync), uncheck to reject. <strong>' + list.length + '</strong> listing(s) available.</p>';
                            html += '<p><button type=\"button\" id=\"agrihub-sync-selected\" class=\"button button-primary\">Sync selected only</button> <button type=\"button\" id=\"agrihub-sync-all\" class=\"button\">Sync all</button></p>';
                            box.html(html);
                        }
                        box.show();
                    } else {
                        box.html('<p class=\"notice notice-error\"><strong>Error:</strong> ' + (r.data || 'Could not fetch listings') + '</p>').show();
                    }
                }).fail(function(xhr) {
                    box.html('<p class=\"notice notice-error\"><strong>Request failed.</strong> Check API URL and token.</p>').show();
                }).always(function() {
                    btn.prop('disabled', false).text('Preview listings from AgriHub');
                });
            });
            function doSync(listingIds) {
                var msg = $('#agrihub-sync-message');
                msg.removeClass('notice-success notice-error').hide();
                var data = { action: 'agrihub_sync_listings', nonce: (typeof agrihubSync !== 'undefined' && agrihubSync.nonce) ? agrihubSync.nonce : '' };
                if (listingIds && listingIds.length) data.listing_ids = listingIds;
                $.post(ajaxurl, data).done(function(r) {
                    if (r.success) {
                        msg.addClass('notice-success').html('<p>Synced ' + (r.data && r.data.count !== undefined ? r.data.count : 0) + ' products from AgriHub.</p>').show();
                    } else {
                        msg.addClass('notice-error').html('<p><strong>Error:</strong> ' + (r.data || 'Unknown error') + '</p>').show();
                    }
                }).fail(function(xhr, status, err) {
                    msg.addClass('notice-error').html('<p><strong>Request failed.</strong> ' + (xhr.responseText || err || 'Check API URL, token, and that AgriHub is reachable from this server.') + '</p>').show();
                });
            }
            $(document).on('click', '#agrihub-sync-selected', function(e) {
                e.preventDefault();
                var btn = $(this);
                var ids = [];
                $('.agrihub-approve:checked').each(function() { ids.push($(this).val()); });
                if (ids.length === 0) {
                    alert('Select at least one listing to sync.');
                    return;
                }
                btn.prop('disabled', true).text('Syncing...');
                doSync(ids);
                setTimeout(function() { btn.prop('disabled', false).text('Sync selected only'); }, 2000);
            });
            $(document).on('click', '#agrihub-sync-all', function(e) {
                e.preventDefault();
                var btn = $(this);
                btn.prop('disabled', true).text('Syncing...');
                doSync(null);
                setTimeout(function() { btn.prop('disabled', false).text('Sync all'); }, 2000);
            });
            $('#agrihub-sync-now').on('click', function(e) {
                e.preventDefault();
                var btn = $(this);
                btn.prop('disabled', true).text('Syncing...');
                doSync(null);
                setTimeout(function() { btn.prop('disabled', false).text('Sync from AgriHub now'); }, 2000);
            });
        });
    ", 'after');
});

/**
 * AJAX handler for preview (fetch listings without syncing).
 */
add_action('wp_ajax_agrihub_preview_listings', function () {
    check_ajax_referer('agrihub_sync', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    $api_url   = get_option('agrihub_api_url');
    $api_token = get_option('agrihub_api_token');

    if (empty($api_url) || empty($api_token)) {
        wp_send_json_error('Configure AgriHub API URL and token first.');
    }

    $api_url = rtrim($api_url, '/');
    $response = wp_remote_get($api_url . '/listings', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
            'Content-Type'  => 'application/json',
        ],
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    if ($code !== 200) {
        $decoded = json_decode($body, true);
        wp_send_json_error('API returned ' . $code . (isset($decoded['message']) ? ': ' . $decoded['message'] : ''));
    }

    $data     = json_decode($body, true);
    $listings = $data['data'] ?? [];

    wp_send_json_success(['listings' => is_array($listings) ? $listings : []]);
});

/**
 * AJAX handler for sync.
 */
add_action('wp_ajax_agrihub_sync_listings', function () {
    check_ajax_referer('agrihub_sync', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    $api_url  = get_option('agrihub_api_url');
    $api_token = get_option('agrihub_api_token');

    if (empty($api_url) || empty($api_token)) {
        wp_send_json_error('Configure AgriHub API URL and token in Settings → AgriHub Sync.');
    }

    $api_url = rtrim($api_url, '/');
    $response = wp_remote_get($api_url . '/listings', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
            'Content-Type'   => 'application/json',
        ],
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    }

    $code = wp_remote_retrieve_response_code($response);
    $body  = wp_remote_retrieve_body($response);

    if ($code !== 200) {
        $msg = 'API returned ' . $code;
        $decoded = json_decode($body, true);
        if (!empty($decoded['message'])) {
            $msg .= ': ' . $decoded['message'];
        }
        wp_send_json_error($msg);
    }

    $data     = json_decode($body, true);
    $listings = $data['data'] ?? [];

    if (!is_array($listings)) {
        wp_send_json_error('Invalid API response format.');
    }

    $listing_ids = null;
    if ( ! empty( $_POST['listing_ids'] ) && is_array( $_POST['listing_ids'] ) ) {
        $listing_ids = array_map( 'intval', array_values( $_POST['listing_ids'] ) );
    }
    if ( $listing_ids !== null && ! empty( $listing_ids ) ) {
        $listings = array_filter( $listings, function ( $l ) use ( $listing_ids ) {
            $id = (int) ( isset( $l['id'] ) ? $l['id'] : 0 );
            return in_array( $id, $listing_ids );
        } );
        $listings = array_values( $listings );
    }

    $count = agrihub_sync_create_products( $listings );
    wp_send_json_success(['count' => $count]);
});

/**
 * Create or update WooCommerce products from AgriHub listings.
 *
 * @param array $listings
 * @return int Number of products synced.
 */
function agrihub_sync_create_products(array $listings): int {
    $count = 0;

    foreach ($listings as $item) {
        $listing_id   = (int) ($item['id'] ?? 0);
        $title        = sanitize_text_field($item['title'] ?? '');
        $price        = isset($item['price_per_unit']) ? (float) $item['price_per_unit'] : 0;
        $stock        = isset($item['available_to_sell']) ? max(0, (float) $item['available_to_sell']) : 0;
        $unit         = sanitize_text_field($item['unit'] ?? 'kg');
        $harvest_date = sanitize_text_field($item['expected_harvest_date'] ?? '');
        $farmer       = $item['farmer'] ?? [];
        $farmer_name  = isset($farmer['name']) ? sanitize_text_field($farmer['name']) : '';
        $farm_name    = isset($farmer['farm_name']) ? sanitize_text_field($farmer['farm_name']) : '';

        if (!$listing_id || !$title) {
            continue;
        }

        $existing = get_posts([
            'post_type'   => 'product',
            'meta_key'    => AGRIHUB_SYNC_META_KEY,
            'meta_value'  => $listing_id,
            'numberposts' => 1,
            'post_status' => 'any',
        ]);

        if ( ! empty( $existing ) ) {
            $product = wc_get_product( $existing[0]->ID );
        } else {
            $product = new WC_Product_Simple();
        }

        if ( ! $product || ! is_object( $product ) ) {
            continue;
        }

        $product->set_name($title);
        $product->set_regular_price($price);
        $product->set_manage_stock(true);
        $product->set_stock_quantity($stock);
        $product->set_sold_individually(false);
        $product->set_catalog_visibility('visible');
        $product->set_status('publish');

        $short_desc = '';
        if ($farm_name) {
            $short_desc .= 'Farm: ' . $farm_name . "\n";
        }
        if ($farmer_name) {
            $short_desc .= 'Farmer: ' . $farmer_name . "\n";
        }
        if ($harvest_date) {
            $short_desc .= 'Expected harvest: ' . $harvest_date . "\n";
        }
        if ($unit) {
            $short_desc .= 'Unit: ' . $unit;
        }
        if ($short_desc) {
            $product->set_short_description(trim($short_desc));
        }

        $product->save();

        update_post_meta($product->get_id(), AGRIHUB_SYNC_META_KEY, $listing_id);
        if ($harvest_date) {
            update_post_meta($product->get_id(), AGRIHUB_SYNC_HARVEST_META, $harvest_date);
        }

        $count++;
    }

    return $count;
}

/**
 * Send order to AgriHub when WooCommerce order is placed.
 */
add_action('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    $api_url  = get_option('agrihub_api_url');
    $api_token = get_option('agrihub_api_token');

    if (empty($api_url) || empty($api_token)) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    $api_url = rtrim($api_url, '/');

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) {
            continue;
        }

        $listing_id = get_post_meta($product->get_id(), AGRIHUB_SYNC_META_KEY, true);
        if (!$listing_id) {
            continue;
        }

        $body = [
            'listing_id'          => (int) $listing_id,
            'quantity'             => (float) $item->get_quantity(),
            'woocommerce_order_id' => (string) $order_id,
            'customer_name'        => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'customer_email'       => $order->get_billing_email(),
            'customer_address'     => $order->get_formatted_billing_address(),
        ];

        wp_remote_post($api_url . '/orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_token,
                'Content-Type'   => 'application/json',
            ],
            'body'    => wp_json_encode($body),
            'timeout' => 15,
        ]);
    }
}, 10, 3);

/**
 * Handle manual sync (fallback when AJAX fails).
 */
add_action('admin_init', function () {
    if (!isset($_GET['page']) || $_GET['page'] !== 'agrihub-sync' || !isset($_GET['sync']) || $_GET['sync'] !== '1') {
        return;
    }
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'agrihub_sync_manual')) {
        wp_die('Invalid nonce');
    }

    $api_url  = get_option('agrihub_api_url');
    $api_token = get_option('agrihub_api_token');

    if (empty($api_url) || empty($api_token)) {
        wp_safe_redirect(add_query_arg('agrihub_error', urlencode('Configure API URL and token first.'), admin_url('options-general.php?page=agrihub-sync')));
        exit;
    }

    $api_url = rtrim($api_url, '/');
    $response = wp_remote_get($api_url . '/listings', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
            'Content-Type'   => 'application/json',
        ],
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wp_safe_redirect(add_query_arg('agrihub_error', urlencode($response->get_error_message()), admin_url('options-general.php?page=agrihub-sync')));
        exit;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body  = wp_remote_retrieve_body($response);

    if ($code !== 200) {
        $decoded = json_decode($body, true);
        $msg = 'API returned ' . $code . (isset($decoded['message']) ? ': ' . $decoded['message'] : '');
        wp_safe_redirect(add_query_arg('agrihub_error', urlencode($msg), admin_url('options-general.php?page=agrihub-sync')));
        exit;
    }

    $data = json_decode($body, true);
    $listings = $data['data'] ?? [];
    $count = is_array($listings) ? agrihub_sync_create_products($listings) : 0;

    wp_safe_redirect(add_query_arg('agrihub_synced', $count, admin_url('options-general.php?page=agrihub-sync')));
    exit;
});

/**
 * Render settings page.
 */
function agrihub_sync_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error(
            'agrihub_sync_messages',
            'agrihub_sync_message',
            'Settings saved.',
            'success'
        );
    }
    if (isset($_GET['agrihub_synced'])) {
        add_settings_error(
            'agrihub_sync_messages',
            'agrihub_sync_message',
            'Synced ' . (int) $_GET['agrihub_synced'] . ' products from AgriHub.',
            'success'
        );
    }
    if (isset($_GET['agrihub_error'])) {
        add_settings_error(
            'agrihub_sync_messages',
            'agrihub_sync_message',
            'Sync failed: ' . esc_html(urldecode($_GET['agrihub_error'])),
            'error'
        );
    }

    settings_errors('agrihub_sync_messages');
    ?>
    <div class="wrap">
        <h1>AgriHub Sync</h1>
        <p>Connect your WooCommerce store to AgriHub for pre-order listings. Farmers add listings in AgriHub; you sync them here. When customers order, orders are sent back to AgriHub.</p>

        <form method="post" action="options.php">
            <?php
            settings_fields('agrihub_sync_settings');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="agrihub_api_url">AgriHub API URL</label></th>
                    <td>
                        <input type="url" id="agrihub_api_url" name="agrihub_api_url" value="<?php echo esc_attr(get_option('agrihub_api_url', '')); ?>" class="regular-text" placeholder="https://your-agrihub.com/api/woocommerce" />
                        <p class="description">Base URL of the AgriHub WooCommerce API (no trailing slash). Example: <code>http://sandbox.rw/api/woocommerce</code> or <code>http://localhost:8000/api/woocommerce</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="agrihub_api_token">AgriHub API Token</label></th>
                    <td>
                        <input type="password" id="agrihub_api_token" name="agrihub_api_token" value="<?php echo esc_attr(get_option('agrihub_api_token', '')); ?>" class="regular-text" autocomplete="off" />
                        <p class="description">Same value as <code>WOOCOMMERCE_API_TOKEN</code> in AgriHub's <code>.env</code> file.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>

        <hr />

        <h2>Preview &amp; Sync</h2>
        <p><strong>Preview</strong> fetches listings and lets you approve/reject each. Uncheck to reject; checked items sync when you click <strong>Sync selected only</strong>. Or use <strong>Sync all</strong> to skip selection.</p>
        <div id="agrihub-preview-results" style="margin: 1em 0; display:none;"></div>
        <div id="agrihub-sync-message" class="notice" style="display:none; margin: 1em 0;"></div>
        <p>
            <button type="button" id="agrihub-preview" class="button">Preview listings from AgriHub</button>
            <button type="button" id="agrihub-sync-now" class="button button-primary">Sync all (no preview)</button>
            <?php
            $sync_url = add_query_arg([
                'page'    => 'agrihub-sync',
                'sync'    => '1',
                '_wpnonce' => wp_create_nonce('agrihub_sync_manual'),
            ], admin_url('options-general.php'));
            ?>
            <a href="<?php echo esc_url($sync_url); ?>" class="button">Sync via link (fallback)</a>
        </p>
        <p class="description">If the button does nothing, use the fallback link. Ensure AgriHub is reachable from this server (e.g. if WordPress is on sandbox.redp.rw, AgriHub must be at a public URL like sandbox.rw, not localhost).</p>
    </div>
    <?php
}
