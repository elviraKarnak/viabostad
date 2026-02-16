<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>


<?php 
        $dummyImg =  get_stylesheet_directory_uri() .'/assets/images/contact-banner.webp';
        $innerBanner = get_field('product_details_banner', 'option');


        if($innerBanner){
            $imgUrl   = $innerBanner['url'];
            $alt      = $innerBanner['alt'];
            $height   = $innerBanner['height'];;
            $width    = $innerBanner['width'];;
        }else{
            $imgUrl   = $dummyImg;
            $alt      = 'Inner banner';
            $height   = '390';
            $width    = '1920';
        }
        ?>
    
    
    <section class="inner-banner">
      <img src="<?php echo $imgUrl; ?>" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" >
    </section>

    <section class="default-content-defult-pages pt_100 pb_100">
        
        <div class="container">

            <?php
                /**
                 * woocommerce_before_main_content hook.
                 *
                 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                 * @hooked woocommerce_breadcrumb - 20
                 */
                do_action( 'woocommerce_before_main_content' );
            ?>

                <?php while ( have_posts() ) : ?>
                    <?php the_post(); ?>

                    <?php wc_get_template_part( 'content', 'single-product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php
                /**
                 * woocommerce_after_main_content hook.
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );
            ?>

            <?php
                /**
                 * woocommerce_sidebar hook.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                //do_action( 'woocommerce_sidebar' );
            ?>
        </div>
    </section> 

<?php get_footer(); ?>