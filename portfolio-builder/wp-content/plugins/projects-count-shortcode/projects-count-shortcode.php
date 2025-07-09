<?php
/*
Plugin Name: Projects Count Shortcode
Description: شورت‌کد برای نمایش تعداد پروژه‌های post type projects
Version: 1.0
Author: شما
*/

add_shortcode('projects_count', function($atts) {
    $count = wp_count_posts('projects')->publish;
    $count = round($count / 2); 
    // استایل ساده badge
    $style = 'display:inline-block;background:transparent;color:#424E60;padding:4px 8px;border-radius:5px;font-weight:bold;font-size:0.7rem;border:1pxsolid#e6e8eb;box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.5);';
    return '<span class="projects-count-badge" style="'.$style.'">'.$count.'</span>';
}); ;