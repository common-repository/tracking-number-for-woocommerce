<?php

/**
 * Plugin Name: Tracking Number for WooCommerce
 * Description: Add order tracking number to WooCommerce.
 * Version: 1.0.2
 * Author: Kostas Vrouvas
 * Requires at least: 4.7
 * Author URI: https://kosvrouvas.dev
 * Text Domain: atnfwoo-tracking-number-woo
 * Domain Path: /languages/
 * WC tested up to: 6.0
 */

define('ATNFWOO_ORDER_TRACKING_NUMBER', plugin_dir_path(__FILE__));

require_once(ATNFWOO_ORDER_TRACKING_NUMBER . '/admin/order-tracking-number-settings.php');


add_action('woocommerce_admin_order_data_after_order_details', 'atnfwoo_add_courier_name_field');
function atnfwoo_add_courier_name_field($order)
{
    woocommerce_wp_text_input(
        array(
            'id'            => 'atnfwoo_courier_name',
            'label'         => __('Courier Name', 'atnfwoo-tracking-number-woo'),
            'value'         => get_post_meta($order->get_id(), 'atnfwoo_courier_name', true),
            'wrapper_class' => 'form-field-wide',
        )
    );
}

add_action('woocommerce_admin_order_data_after_order_details', 'atnfwoo_add_admin_order_field');
function atnfwoo_add_admin_order_field($order)
{
    woocommerce_wp_text_input(
        array(
            'id'            => 'atnfwoo_tracking_number',
            'label'         => __('Courier Tracking Number', 'atnfwoo-tracking-number-woo'),
            'value'         => get_post_meta($order->get_id(), 'atnfwoo_tracking_number', true),
            'wrapper_class' => 'form-field-wide',
        )
    );
}

add_action('woocommerce_process_shop_order_meta', 'atnfwoo_save_admin_order_field');
function atnfwoo_save_admin_order_field($ord_id)
{
    update_post_meta($ord_id, 'atnfwoo_tracking_number', wc_clean($_POST['atnfwoo_tracking_number']));
    update_post_meta($ord_id, 'atnfwoo_courier_name', wc_clean($_POST['atnfwoo_courier_name']));
}

add_filter('woocommerce_email_order_meta_fields', 'atnfwoo_custom_order_email_fields', 10, 3);
function atnfwoo_custom_order_email_fields($fields, $sent_to_admin, $order)
{
    $atnfwoo_tracking_number = get_post_meta($order->get_id(), 'atnfwoo_tracking_number', true);
    $atnfwoo_courier_name = get_post_meta($order->get_id(), 'atnfwoo_courier_name', true);
    $is_order_complete = $order->has_status('completed');
    if (!empty($atnfwoo_tracking_number) && !$sent_to_admin && $is_order_complete) {
        $atnfwoo_number_text = __('Order Tracking Code', 'atnfwoo-tracking-number-woo');
        $atnfwoo_courier_text = __('Courier', 'atnfwoo-tracking-number-woo');
        $atnfwoo_heading = __('Track your order', 'atnfwoo-tracking-number-woo');
        $atnfwoo_email_message = get_option('atnfwoo_tracking_number_description');
        $atnfwoo_email_message = str_replace('{atnfwoo_courier_name}', $atnfwoo_courier_name, $atnfwoo_email_message);
        $atnfwoo_email_message = str_replace('{atnfwoo_tracking_number}', $atnfwoo_tracking_number, $atnfwoo_email_message);

        if (empty($atnfwoo_email_message) || strpos($atnfwoo_email_message, $atnfwoo_tracking_number) === false) {
?>          <?php echo '<h2>' . esc_attr($atnfwoo_heading) . '</h2>'; ?>
            <?php echo '<p>' . esc_attr($atnfwoo_courier_text); '</p>' ?>: <?php echo '<p>' . esc_attr($atnfwoo_courier_name) . '</p>'; ?>
            <?php echo '<p>' . esc_attr($atnfwoo_number_text); '</p>' ?>: <?php echo '<p>' . esc_attr($atnfwoo_tracking_number) . '</p>'; ?>
<?php
        } else {
            echo '<h2>' . esc_attr($atnfwoo_heading) . '</h2>';
            echo '<p>' . esc_attr($atnfwoo_email_message) . '</p>';
        }
    }

    return $fields;
}
