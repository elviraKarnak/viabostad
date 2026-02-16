<?php 
// style sheet & scripts

function viabosted_enqueue(){

	$uri = get_theme_file_uri();
    $ver = 1.0;
    $vert = time();

      wp_register_style( 'bootstrap',   $uri. '/assets/css/bootstrap/bootstrap.min.css', [], $ver);
	  wp_register_style( 'font-awesome', $uri.'/assets/css/fontawesome/css/all.min.css', [], $ver);
	  wp_register_style( 'owl', $uri. '/assets/css/owl/owl.carousel.min.css', [], $ver);
	  wp_register_style( 'theme-css',  $uri. '/assets/css/main-style.css', [], $vert);
	  wp_register_style( 'theme_stylesheet', $uri. '/style.css', [], $vert);


	  wp_enqueue_style( 'bootstrap');
	  wp_enqueue_style( 'font-awesome');
	  wp_enqueue_style( 'owl');
	  wp_enqueue_style( 'theme-css');
	  wp_enqueue_style( 'theme_stylesheet');

	
	  wp_register_script( 'bootstrap', $uri . '/assets/js/bootstrap/bootstrap.bundle.min.js', [], $ver, true );
	  wp_register_script( 'owl',     $uri . '/assets/js/owl/owl.carousel.min.js',  [], $ver, true );
	  wp_register_script( 'custom-js', $uri . '/assets/js/function.js', [], $vert, true );

	  wp_enqueue_script('jquery');
	  wp_enqueue_script('bootstrap');
	  wp_enqueue_script('owl');
	  wp_enqueue_script('custom-js');

  }

  add_action( 'wp_enqueue_scripts', 'viabosted_enqueue' );



// register navs
register_nav_menus(
	array(
		'menu-1' => __('Primary', 'viabosted'),
		'menu-3' => __('Login', 'viabosted'),
		'menu-2' => __('Footer First Menu', 'viabosted'),
    )
);

	// theme support
if ( ! function_exists( 'viabosted_setup_theme' ) ) {

	function viabosted_setup_theme() {

		// Basic supports
		add_theme_support( 'custom-logo' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );

		// WooCommerce support
		add_theme_support( 'woocommerce' );

		// Optional (recommended)
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

}
add_action( 'after_setup_theme', 'viabosted_setup_theme', 20 );


require get_template_directory() . '/inc/custom_functions.php';
require get_template_directory() . '/inc/acf-blocks-support.php';
require get_template_directory() . '/inc/shortcodes/home-search.php';