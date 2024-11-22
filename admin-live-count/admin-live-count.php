<?php
/**
 * Plugin Name: Admin Live Count
 * Plugin URI: https://profiles.wordpress.org/anas53/
 * Description: A plugin to display the count of currently logged-in admin users in the WordPress top bar.
 * Version: 1.0
 * Author: Anas Shabbir
 * Author URI: https://github.com/Anasshabbir5332
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Function to count logged-in admin users.
 */
function alc_get_logged_in_admin_count() {
    $users = get_users([
        'role'    => 'Administrator',
        'fields'  => ['ID'],
    ]);

    $count = 0;
    foreach ($users as $user) {
        if (get_user_meta($user->ID, 'session_tokens', true)) {
            $count++;
        }
    }

    return $count;
}

/**
 * Add admin live count to the WordPress admin bar.
 */
function alc_add_admin_live_count_to_bar($wp_admin_bar) {
    if (!is_admin() || !is_user_logged_in()) {
        return;
    }

    if (!current_user_can('administrator')) {
        return;
    }

    $count = alc_get_logged_in_admin_count();
    $args = [
        'id'    => 'admin_live_count',
        'title' => '<span style="font-weight: bold; color: #fff;">Admins Online: ' . $count . '</span>',
        'meta'  => [
            'class' => 'admin-live-count',
        ],
    ];

    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'alc_add_admin_live_count_to_bar', 100);

/**
 * Add styles for the admin live count.
 */
function alc_enqueue_styles() {
    echo '<style>
        #wp-admin-bar-admin_live_count {
            background-color: #0073aa;
            padding: 0 10px;
            border-radius: 3px;
        }
    </style>';
}
add_action('admin_head', 'alc_enqueue_styles');
