<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.4' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

// plugin for ai description generator 

// add button to generate description with ai below project_description field in acf
add_action('acf/render_field/name=project_description', 'add_ai_button_to_acf_field');
function add_ai_button_to_acf_field($field) {
    // add custom style to button
    $button_style = 'margin-top: 10px; cursor: pointer;';

    echo '<button type="button" id="generate-ai-description-btn" class="button button-primary" style="' . $button_style . '">✨ تولید توضیحات با هوش مصنوعی</button>';
	// add a empty space to show loading status
    echo '<p id="ai-status" style="display:none; color:#555; font-style:italic;"></p>';
}

// 

// load scripts for ai generator button
add_action('admin_enqueue_scripts', 'load_ai_generator_scripts');
function load_ai_generator_scripts($hook) {
    // فقط در صفحات ویرایش و افزودن پست جدید اجرا شود
    if ('post.php' != $hook && 'post-new.php' != $hook) {
        return;
    }

    // مطمئن می‌شویم که در پست تایپ "project" هستیم
    // global $post;
    // if ('project' != $post->post_type) {
    //     return;
    // }

    // فایل جاوااسکریپت را ثبت و بارگذاری می‌کنیم
	// TODO: add ai-generator.js to the theme
    wp_enqueue_script('ai-generator', get_template_directory_uri() . '/js/ai-generator.js', array('jquery'), '1.0', true);

    // ارسال متغیرهای لازم از PHP به JavaScript (مانند nonce برای امنیت)
    wp_localize_script('ai-generator', 'ai_generator_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ai_generator_nonce')
    ));
}

// 

// handle ajax request for generate project description with ai
add_action('wp_ajax_generate_project_description_ai', 'handle_ai_generation_request');

function handle_ai_generation_request() {
    // بخش‌های اولیه بدون تغییر
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ai_generator_nonce')) {
        wp_send_json_error('Nonce نامعتبر است');
        return;
    }

    $api_key = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';
    if (empty($api_key)) {
        wp_send_json_error('کلید API برای Gemini تعریف نشده است');
        return;
    }

    // ==================> پیاده‌سازی دقیق بر اساس curl <==================

    $model_name = 'gemini-2.0-flash';
    $api_url = "https://generativelanguage.googleapis.com/v1beta/models/{$model_name}:generateContent";

	// پاک‌سازی عنوان پروژه از فاصله‌های اضافی
    $project_title = $_POST['project_title'];

    $prompt = "You are a highly creative and experienced technology copywriter.\n\n
	First, automatically detect the language of the provided project_title. 
	If the project_title is in English, your response must be in English. 
	If the project_title is in Persian (Farsi), your response must be in Persian.\n\n
	Your task is to craft an engaging, multi-paragraph project description about a technology or innovative project, based solely on the provided project title. 
	The description should introduce the project, highlight its main goals, and explain its benefits for the target audience. 
	Make sure the description is relevant to modern technology and innovation.\n\n
	Project Title: \"{$project_title}\"\n\nRespond ONLY with the project description in the detected language.";

	// request body structure
    $body = [
        'contents' => [
            ['parts' => [['text' => $prompt]]]
        ]
    ];

    $args = [
        'body'        => json_encode($body),
        'headers'     => [
            'Content-Type'   => 'application/json',
            'x-goog-api-key' => $api_key,
        ],
        'timeout'     => 60,
    ];

    // =========================================================================

    // ارسال درخواست و پردازش پاسخ (بخش دیباگ همچنان فعال است)
    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        wp_send_json_error('خطا در ارتباط با Gemini API: ' . $response->get_error_message());
        return;
    }

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($response_body)) {
        $raw_body = wp_remote_retrieve_body($response);
        wp_send_json_error('پاسخ خالی یا با فرمت نامعتبر از Gemini دریافت شد. بدنه پاسخ: ' . esc_html($raw_body));
        return;
    }
    
    if (isset($response_body['candidates'][0]['content']['parts'][0]['text'])) {
        $generated_text = trim($response_body['candidates'][0]['content']['parts'][0]['text']);
        wp_send_json_success($generated_text);
    } 
    elseif (isset($response_body['error']['message'])) {
        wp_send_json_error('خطا از Gemini API: ' . $response_body['error']['message']);
    }
    else {
        wp_send_json_error('پاسخ نامشخص از Gemini API دریافت شد. پاسخ خام: ' . json_encode($response_body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}


// plugin for ai generator description (end) --------------------------------------------------------------




require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();




