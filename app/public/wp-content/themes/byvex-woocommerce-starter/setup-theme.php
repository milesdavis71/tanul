<?php

function byvex_woocommerce_starter_overwrite_admin_notice(){ ?>
	<div class="notice notice-warning is-dismissible">
		<h3 style="margin:0.5em 0 0;"><?php echo esc_html('Theme Overwrite Warning'); ?></h3>
		<p><?php echo esc_html('Please do not directly make changes to code of this theme. You can customise with a plugin or by creating a child theme, otherwise your changes will be overwritten when theme updates.'); ?><br />
                   <?php echo esc_html('Contact for tech support:'); ?> <a href="<?php echo esc_attr('mailto:info@byvex.com'); ?>"><?php echo esc_html('info@byvex.com'); ?></a></p>
	</div>
<?php }
add_action('admin_notices', 'byvex_woocommerce_starter_overwrite_admin_notice');


if (!function_exists('byvex_woocommerce_starter_setup')){
	function byvex_woocommerce_starter_setup(){
		add_theme_support('automatic-feed-links');
		add_theme_support('custom-background', array(
			'default-color' => 'ffffff',
		));
		add_theme_support('custom-header', array(
			'height' => '40',
			'flex-height' => true,
			'width' => '140',
			'flex-width' => true,
			'uploads' => true,
			'header-text' => true,
		));
		add_theme_support(
			'custom-logo',
			array(
				'height' => 100,
				'weight' => 100,
				'flex-height' => true,
				'flex-weight' => true,
			)
		);
		add_theme_support('customize-selective-refresh-widgets');
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets'
			)
		);
		add_theme_support('post-thumbnails');
		add_theme_support('title-tag');

		register_nav_menus(array(
			'primary' => esc_html__('Primary', 'byvex-woocommerce-starter'),
		));

		// Set the content width in pixels, based on the theme's design and stylesheet
		// Priority 0 to make it available to lower priority callbacks.
		if (!isset($content_width)) {
			$content_width = 900;
		}
		function byvex_woocommerce_starter_content_width(){
			$GLOBALS['content-width'] = apply_filters('byvex_woocommerce_starter_content_width', 1400);
		}
		add_action('after_setup_theme', 'byvex_woocommerce_starter_content_width', 0);

		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on byvex-woocommerce-starter, use a find and replace
		 * to change 'byvexwoocommercestarter' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('byvex-woocommerce-starter', get_template_directory() . '/languages');
	}
}
add_action('after_setup_theme', 'byvex_woocommerce_starter_setup');



function byvex_woocommerce_starter_enqueue_scripts(){
    $tpl_dir = get_template_directory();
    $tpl_dir_uri = get_template_directory_uri();

    // Bootstrap Style
    $path = '/css/bootstrap.min.css';
	if (file_exists($tpl_dir . '' . $path)) {
		wp_enqueue_style('byvex-woocommerce-starter-bootstrap-style', $tpl_dir_uri . '' . $path, array(), filemtime($tpl_dir . '' . $path));
	}

	// Bootstrap Bundle Script with PopperJS
    $path = '/js/bootstrap.bundle.min.js';
	if (file_exists($tpl_dir . '' . $path)) {
		wp_enqueue_script('byvex-woocommerce-starter-bootstrap-script', $tpl_dir_uri . '' . $path, array(), filemtime($tpl_dir . '' . $path), true);
	}

	// Main Style
    $path = '/style.css';
	if (file_exists($tpl_dir . '' . $path)) {
		wp_enqueue_style('byvex-woocommerce-starter-main-style', $tpl_dir_uri . '' . $path, array('byvex-woocommerce-starter-bootstrap-style'), filemtime($tpl_dir . '' . $path));
	}

	// Main Script
    $path = '/main.js';
	if (file_exists($tpl_dir . '' . $path)) {
		wp_enqueue_script('byvex-woocommerce-starter-main-script', $tpl_dir_uri . '' . $path, array('jquery', 'byvex-woocommerce-starter-bootstrap-script'), filemtime($tpl_dir . '' . $path), true);
	}
}
add_action('wp_enqueue_scripts', 'byvex_woocommerce_starter_enqueue_scripts');



function byvex_woocommerce_starter_preload($hints, $relation_type){
	if ('preconnect' === $relation_type) {
		// Google fonts CDN
		$hints[] = [
			'href'        => 'https://fonts.googleapis.com',
		];
		$hints[] = [
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		];
	}
	return $hints;
}
add_filter('wp_resource_hints', 'byvex_woocommerce_starter_preload', 10, 2);


// Add a pingback url auto-discovery header for single posts, pages, or attachments
function byvex_woocommerce_starter_pingback_header(){
	if (is_singular() && pings_open()) {
		echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '" />';
	}
}
add_action('wp_head', 'byvex_woocommerce_starter_pingback_header');



function byvex_woocommerce_starter_widgets_init(){
	register_sidebar(
		array(
			'name' => __('Footer Widgets', 'byvex-woocommerce-starter'),
			'id' => 'sidebar-1',
			'description' => __('Add widgets here', 'byvex-woocommerce-starter'),
			'before_widget' => '<section id="%1$s" class="widget %2$s col-sm-6 col-md-4 col-lg-3">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="h4 widget-title mb-0 pb-2">',
			'after_title' => '</h3>',
		)
	);
}
add_action('widgets_init', 'byvex_woocommerce_starter_widgets_init');

/**
 * WooCommerce Support Start
 */

// Setup functions
function byvex_woocommerce_starter_woocommerce_support(){
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'byvex_woocommerce_starter_woocommerce_support');


// Disbable Extra WooCoomerce Sidebar
function byvex_woocommerce_starter_disable_woo_commerce_sidebar(){
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}
add_action('init', 'byvex_woocommerce_starter_disable_woo_commerce_sidebar');


// Unhook the WooCommerce wrappers;
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);


// Own functions to display the wrappers your theme requires
function byvex_woocommerce_starter_wc_wrapper_start(){
    echo '<section class="container py-4">';
}
add_action('woocommerce_before_main_content', 'byvex_woocommerce_starter_wc_wrapper_start', 10);
function byvex_woocommerce_starter_wc_wrapper_end(){
    echo '</section>';
}
add_action('woocommerce_after_main_content', 'byvex_woocommerce_starter_wc_wrapper_end', 10);

 /**
 * WooCommerce Support End
 */
