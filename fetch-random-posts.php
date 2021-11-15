<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              test
 * @since             1.0.0
 * @package           Fetch_Random_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Fetch Random Posts
 * Description:       This plugin fetches random posts from an API and displays them via a shortcode.
 * Version:           1.0.0
 * Author:            Olivija Guzelyte
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fetch-random-posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('FETCH_RANDOM_POSTS_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fetch-random-posts-activator.php
 */
function activate_fetch_random_posts()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-fetch-random-posts-activator.php';
    Fetch_Random_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fetch-random-posts-deactivator.php
 */
function deactivate_fetch_random_posts()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-fetch-random-posts-deactivator.php';
    Fetch_Random_Posts_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_fetch_random_posts');
register_deactivation_hook(__FILE__, 'deactivate_fetch_random_posts');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-fetch-random-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fetch_random_posts()
{

    $plugin = new Fetch_Random_Posts();
    $plugin->run();

}
run_fetch_random_posts();

/* Fetch random posts from api https://jsonplaceholder.typicode.com/posts */
function get_random_posts_from_api()
{

    $args = array(
        'timeout' => 10,
    );
	/* Get the posts saved as a transient if it exists */
    $body = get_transient('frp_random_posts_expiry_time');

	/* If transient doesn't exist, fetch posts again */
    if (false === $body) {
        $url = 'https://jsonplaceholder.typicode.com/posts';
        $response = wp_remote_get($url, $args);
        // Check for error
        if (is_wp_error($response)) {
            return;
        }
        // Parse remote HTML file
        $wpshout_homepage_html = wp_remote_retrieve_body($response);

        // Check for errors
        if (is_wp_error($wpshout_homepage_html)) {
            return;
        }

        $body = $wpshout_homepage_html;
        set_transient('frp_random_posts_expiry_time', $body, 30 * MINUTE_IN_SECONDS);
    }

    return $body;

}

/* Add shortcode random posts */
add_shortcode('random_posts', 'create_random_posts_shortcode');
function create_random_posts_shortcode($count, $order)
{
    extract(shortcode_atts(array(
        'count' => 'count',
        'order' => 'order',
    ), $count, $order));

	/* Get random posts from api */
    $posts_html = get_random_posts_from_api();
    $data = json_decode($posts_html);
    $post_content;
    $max_posts;
	/* Check if count attribute added. If it is - overwrite the default posts nr */
    if ($count >= 1 && $count <= count($data)) {
        $max_posts = $count;
    } else if ($count != 'count' && $count > count($data)) {
        $max_posts = count($data);
    } else {
        $max_posts = get_max_posts_value();
    }
    if ($max_posts) {
        $data = array_slice($data, 0, $max_posts);
    }
	/* Order posts array */
    $data = order_posts_array($data, $order);
	/* Create the post display */
    foreach ($data as $line) {
        $post_content .= '<div class="random-post-entry">';
        $post_content .= '<p class="random-post-id">Post ID: ' . $line->id . '</p>';
        $post_content .= '<p class="random-post-user-id">User ID: ' . $line->userId . '</p>';
        $post_content .= '<h2 class="random-post-title">' . $line->title . '</h2>';
        $post_content .= '<p class="random-post-body">' . $line->body . '</p>';
        $post_content .= '</div>';
    }
    return $post_content;

}

/* Get max posts default setting */
function get_max_posts_value()
{
    $max_posts = get_option('frp_random_posts_max_posts');
    return $max_posts;
}

/* Get default ordering setting */
function get_default_ordering()
{
    $default_ordering = get_option('frp_random_posts_order');
    return $default_ordering;
}

/* Order posts array by existing default value or shortcode attribute */
function order_posts_array($data, $order)
{
    if ($order != null) {
        if ($order == 'asc') {
            usort($data, 'sort_asc');
        } else if ($order == 'desc') {
            usort($data, 'sort_desc');
        }
    } else {
        if (get_default_ordering() != null) {
            if (get_default_ordering() == 'ascending') {
                usort($data, 'sort_asc');
            } else if (get_default_ordering() == 'descending') {
                usort($data, 'sort_desc');
            }
        }
    }
    return $data;
}

/* Usort ascending/descending sorting functions */
function sort_asc($a, $b)
{
    return $a->id > $b->id;
}
function sort_desc($a, $b)
{
    return $a->id < $b->id;
}
