<?php

/**
 * Plugin Name:       AI Project Description Generator
 * Description:       Adds a button to the project editor to generate a description using AI.
 * Version:           1.0
 * Author:            Parsa Ghafari
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Our code will go here.
function aigen_enqueue_admin_scripts($hook)
{
    // Check if we are on the new post or edit post screen
    if ('post.php' != $hook && 'post-new.php' != $hook) {
        return;
    }

    // Check if the post type is 'project'
    // Change 'project' to 'post' if you want it for regular blog posts
    if ('project' != get_post_type()) {
        return;
    }

    // Enqueue our script
    wp_enqueue_script(
        'aigen-script', // A unique name for our script
        plugin_dir_url(__FILE__) . 'aigen-script.js', // Path to the script
        ['jquery'], // Dependencies, our script needs jQuery
        '1.0', // Version number
        true // Load in the footer
    );

    // Pass data from PHP to our JavaScript file (VERY IMPORTANT for security and functionality)
    wp_localize_script('aigen-script', 'ai_generator_data', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ai_generator_nonce')
    ]);
}
add_action('admin_enqueue_scripts', 'aigen_enqueue_admin_scripts');
