<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

 $product_id = get_the_ID();

 $bedroom  = get_field('bedroom_sp', $product_id);
 $bathroom = get_field('bathroom_sp', $product_id);
 $area     = get_field('area', $product_id);
 $location = get_field('location', $product_id);

if ( ! $short_description ) {
	return;
}

?>
<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>

     <div class="middle">
        <div class="title_wrap">
        <h3 class="title">
            <a href="<?php echo esc_url($product_link); ?>">
            <?php echo esc_html($product_title); ?>
            </a>
        </h3>

        <?php if ($location): ?>
            <span>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/location.svg" width="14" height="20" />
            <?php echo esc_html($location); ?>
            </span>
        <?php endif; ?>
        </div>

        <div class="room_info_wrap">

        <?php if ($bedroom): ?>
            <p>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bedroom.svg" width="24" height="18" />
            <?php echo esc_html($bedroom); ?> Bedrooms
            </p>
        <?php endif; ?>

        <?php if ($bathroom): ?>
            <p>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bath.svg" width="24" height="18" />
            <?php echo esc_html($bathroom); ?> Bath
            </p>
        <?php endif; ?>

        <?php if ($area): ?>
            <p>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sqm.svg" width="24" height="18" />
            <?php echo esc_html($area); ?> sqm
            </p>
        <?php endif; ?>

        </div>
    </div>
