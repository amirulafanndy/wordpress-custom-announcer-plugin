<?php
/*
Plugin Name: CompAsia Announcement Bar
Description: [COMPASIA TECHNICAL TEST] A simple plugin to display an announcer bar at the top of the website.
Version: 1.0
Author: Amirul
Plugin URI: https://github.com/amirulafanndy/wordpress-custom-announcer-plugin
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'wp_announcer_add_admin_menu');
add_action('admin_init', 'wp_announcer_settings_init');

function wp_announcer_add_admin_menu() {
    // Add a top-level menu in the sidebar
    add_menu_page(
        'WP Announcer',                // Page title
        'Announcement Bar',            // Menu title
        'manage_options',              // Capability
        'wp_announcer',                // Menu slug
        'wp_announcer_options_page',   // Function to display the settings page
        'dashicons-megaphone',         // Icon
        81                             // Position
    );
}

function wp_announcer_settings_init() {
    register_setting('wp_announcer_settings', 'wp_announcer_options');

    add_settings_section(
        'wp_announcer_section',
        __('Announcement Bar Settings', 'wp-announcer'),
        '',
        'wp_announcer_settings'
    );

    add_settings_field(
        'wp_announcer_toggle',
        __('Enable Announcement Bar', 'wp-announcer'),
        'wp_announcer_toggle_render',
        'wp_announcer_settings',
        'wp_announcer_section'
    );

    add_settings_field(
        'wp_announcer_message',
        __('Announcement Message', 'wp-announcer'),
        'wp_announcer_message_render',
        'wp_announcer_settings',
        'wp_announcer_section'
    );
}

function wp_announcer_toggle_render() {
    $options = get_option('wp_announcer_options');
    $toggle = isset($options['wp_announcer_toggle']) ? $options['wp_announcer_toggle'] : 0;
    ?>
    <input type='checkbox' name='wp_announcer_options[wp_announcer_toggle]' <?php checked($toggle, 1); ?> value='1'>
    <?php
}

function wp_announcer_message_render() {
    $options = get_option('wp_announcer_options');
    $message = isset($options['wp_announcer_message']) ? esc_html($options['wp_announcer_message']) : '';
    ?>
    <input type='text' name='wp_announcer_options[wp_announcer_message]' value='<?php echo $message; ?>' style='width: 100%;' placeholder='Enter your announcement message here'>
    <?php
}

function wp_announcer_options_page() {
    ?>
    <div class="wrap">
        <h2>CompAsia Announcement Bar Settings</h2>
        <form action='options.php' method='post'>
            <?php
            settings_fields('wp_announcer_settings');
            do_settings_sections('wp_announcer_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wp_announcer_enqueue_styles() {
    wp_enqueue_style('wp-announcer-style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'wp_announcer_enqueue_styles');

add_action('wp_footer', 'wp_announcer_display');

function wp_announcer_display() {
    $options = get_option('wp_announcer_options');
    if (!empty($options['wp_announcer_toggle'])) {
        $message = isset($options['wp_announcer_message']) && !empty($options['wp_announcer_message'])
            ? esc_html($options['wp_announcer_message'])
            : 'This is the default announcement if the message is null!';
        echo '<div id="wp-announcer-bar">' . $message . '</div>';
    }
}


