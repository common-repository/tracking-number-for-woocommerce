<?php


class atnfwoo_tracking_number
{

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init()
    {
        add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50);
        add_action('woocommerce_settings_tabs_atnfwoo_tracking_number', __CLASS__ . '::settings_tab');
        add_action('woocommerce_update_options_atnfwoo_tracking_number', __CLASS__ . '::update_settings');
    }



    //  * Add a new settings tab to the WooCommerce settings tabs array.
    //  *
    //  * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
    //  * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.

    public static function add_settings_tab($settings_tabs)
    {
        $settings_tabs['atnfwoo_tracking_number'] = __('Tracking Number Message', 'atnfwoo-tracking-number-woo');
        return $settings_tabs;
    }


    // /
    //  * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
    //  *
    //  * @uses woocommerce_admin_fields()
    //  * @uses self::get_settings()
    //  */
    public static function settings_tab()
    {
        woocommerce_admin_fields(self::get_settings());
    }


    // /
    //  * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
    //  *
    //  * @uses woocommerce_update_options()
    //  * @uses self::get_settings()
    //  */
    public static function update_settings()
    {
        woocommerce_update_options(self::get_settings());
    }


    // /
    //  * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
    //  *
    //  * @return array Array of settings for @see woocommerce_admin_fields() function.
    //  */
    public static function get_settings()
    {

        $settings = array(
            'section_title' => array(
                'name'     => __('Tracking Number Message', 'atnfwoo-tracking-number-woo'),
                'type'     => 'title',
                'desc'     => __('Use {atnfwoo_tracking_number} to display the tracking code & {atnfwoo_courier_name} to display the courier name in the message.', 'atnfwoo-tracking-number-woo'),
                'id'       => 'atnfwoo_tracking_number_section_title'
            ),

            'description' => array(
                'name' => __('Message', 'atnfwoo-tracking-number-woo'),
                'type' => 'textarea',
                'id'   => 'atnfwoo_tracking_number_description'
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'atnfwoo_tracking_number_section_end'
            )
        );

        return apply_filters('atnfwoo_tracking_number_settings', $settings);
    }
}

atnfwoo_tracking_number::init();
