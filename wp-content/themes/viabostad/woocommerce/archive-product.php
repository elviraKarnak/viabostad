<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

  <style>
	.woocommerce-ordering,
	.woocommerce-result-count{
		display: none;
	}
  </style>


	<?php 
        $dummyImg =  get_stylesheet_directory_uri() .'/assets/images/contact-banner.webp';
        $thumb_id = get_post_thumbnail_id();
        $img_src  = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'large') : '';
        $img_alt  = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';


        if($innerBanner){
            $imgUrl   = $img_src[0];
            $alt      = $img_alt;
            $height   = $img_src[2];
            $width    = $img_src[1];
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

	  <section class="sticky_map_locations">
        <div class="container-fluid">
          <div class="outer_wrapper">
            <div class="row">       
              <div class="col-lg-8 col-xl-7">
				<div class="left_wrapper">
					<div class="sec_head">
						<form id="filter-property">
							<div class="shop_filter">
								<div class="find_property_wrapper">
									<div class="row align-items-center gy-md-4 gy-3">
										<div class=" col-md-6">
											<div class="form_wrapper">
												<div class="input_wrap">
													<div class="field">
														<input type="text" placeholder="Search for a place" name="property-search">
													</div>
													<a href="javascript:void(0)" class="filter-open-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/filter-btn-blue.svg" alt="filter-btn-blue" width="62" height="47"></a>
												</div>
												<div class="filter_fields">
												<div class="field">
													<label for="location">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/location.svg" alt="location" width="14" height="20"/>
													Location
													</label>

													<?php
														$terms = get_terms([
															'taxonomy'   => 'location',
															'hide_empty' => true, // set false if you want empty terms too
														]);

													if (!empty($terms) && !is_wp_error($terms)) { ?>
					

													<select name="property-location" id="location">
														<option value="all" disabled selected >Choose your location</option>
														<?php foreach ( $terms as $term ) { ?>
															<option value="<?php echo $term->slug; ?>"> <?php echo esc_html($term->name); ?></option>
														<?php } ?>
													</select>
												<?php } ?>
											</div>
											<div class="field">
												<label for="type">
													<img
														src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home.svg"
														alt="home"
														width="20"
														height="20"
													/>
													What type you looking for
												</label>
												<?php
													$terms = get_terms([
														'taxonomy'   => 'product_cat',
														'hide_empty' => true, // set false if you want empty terms too
													]);

													if (!empty($terms) && !is_wp_error($terms)) {?>
														<select name="property-type" id="type">
															<option value="all" disabled selected >Choose your Category</option>
															<?php foreach ( $terms as $term ) { ?>
																	<option value="<?php echo $term->slug; ?>"> <?php echo esc_html($term->name); ?></option>
																<?php } ?>
														</select>
													<?php } ?>
											</div>
											<div class="field">
												<label for="price">
													<img
													src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/price.svg"
													alt="price"
													width="20"
													height="20"
												/>
												Price</label
												>
												<input
												type="text"
												name="property-price"
												id="price"
												placeholder="22,500,000"
												/>
											</div>
											<div class="btn_groups">
												<button class="primary_btn icon search" type="submit">Filter Property</button>
											</div>
											</div>
											</div>
										</div>
										
											
										
									</div>
								</div>  
							</div>
						</form>
						</div>

						<div id="all_properties">

							
							<div class="total_found_products">
								<?php
								if ( woocommerce_product_loop() ) :

									global $wp_query;

									$total        = $wp_query->found_posts;
									$per_page     = $wp_query->get( 'posts_per_page' );
									$current      = max( 1, get_query_var( 'paged' ) );

									$first = ( $per_page * $current ) - $per_page + 1;
									$last  = min( $total, $per_page * $current );
									?>

									<p class="search_result">
										Showing Newest Results <?php echo esc_html( $first ); ?>–<?php echo esc_html( $last ); ?>
										of <?php echo esc_html( $total ); ?>
									</p>

								<?php endif; ?>
							</div>
					 
							<?php 

								/**
								 * Hook: woocommerce_before_main_content.
								 *
								 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
								 * @hooked woocommerce_breadcrumb - 20
								 * @hooked WC_Structured_Data::generate_website_data() - 30
								 */
								do_action( 'woocommerce_before_main_content' );

								/**
								 * Hook: woocommerce_shop_loop_header.
								 *
								 * @since 8.6.0
								 *
								 * @hooked woocommerce_product_taxonomy_archive_header - 10
								 */
								do_action( 'woocommerce_shop_loop_header' );

								if ( woocommerce_product_loop() ) {

									/**
									 * Hook: woocommerce_before_shop_loop.
									 *
									 * @hooked woocommerce_output_all_notices - 10
									 * @hooked woocommerce_result_count - 20
									 * @hooked woocommerce_catalog_ordering - 30
									 */
									//do_action( 'woocommerce_before_shop_loop' );

									woocommerce_product_loop_start();

									if ( wc_get_loop_prop( 'total' ) ) {
										while ( have_posts() ) {
											the_post();

											/**
											 * Hook: woocommerce_shop_loop.
											 */
											do_action( 'woocommerce_shop_loop' );

											wc_get_template_part( 'content', 'product' );
										}
									}

									woocommerce_product_loop_end();

									/**
									 * Hook: woocommerce_after_shop_loop.
									 *
									 * @hooked woocommerce_pagination - 10
									 */
									do_action( 'woocommerce_after_shop_loop' );
								} else {
									/**
									 * Hook: woocommerce_no_products_found.
									 *
									 * @hooked wc_no_products_found - 10
									 */
									do_action( 'woocommerce_no_products_found' );
								}

								/**
								 * Hook: woocommerce_after_main_content.
								 *
								 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
								 */
								do_action( 'woocommerce_after_main_content' );

								/**
								 * Hook: woocommerce_sidebar.
								 *
								 * @hooked woocommerce_get_sidebar - 10
								 */
								//do_action( 'woocommerce_sidebar' );?>

				</div>
              </div>
			</div> 
              <div class="col-lg-4 col-xl-5">
                <div class="iframe_wrapper">
			      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7762535.5839049015!2d6.879605339430866!3d61.640868860537914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x465cb2396d35f0f1%3A0x22b8eba28dad6f62!2sSweden!5e0!3m2!1sen!2sin!4v1770904750717!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>		
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </section>


	


   <script>
      jQuery(document).ready(function ($) {


        // $("#search-broker").on('click', function(e) {
        //    e.preventDefault();
        //   $('#brokerSearchForm').submit();
        // })

        //   $(".broker-agencies").on('change', function(e) {
        //    $('#brokerSearchForm').submit();
        // })

        $('#filter-property').on('submit', function (e) {
          e.preventDefault();


		    var search   = $('input[name="property-search"]').val().trim();
			var location = $('select[name="property-location"]').val();
			var type     = $('select[name="property-type"]').val();
			var price    = $('input[name="property-price"]').val().trim();

			// Remove commas from price
			price = price.replace(/,/g, '');

			// Check if at least one field is selected
			if (
				search === '' &&
				(location === null || location === 'all') &&
				(type === null || type === 'all') &&
				price === ''
			) {
				alert('Please select at least one filter option.');
				return false;
			}

          var formData = new FormData(this);

          formData.append('action', 'filter_search_property');
             
           $('#all_properties').html('<span class="loader-property"></span>');

            $.ajax({
              url: '<?php echo home_url('/wp-admin/admin-ajax.php')?>', // WP default
              type: 'POST',
              data:formData,
              processData: false,
              contentType: false,
              success: function (response) {
				
                $('#all_properties').html(response);
				$(".filter_fields").removeClass("active"); 
              }
			
            });

          });

      });
  
    </script>

	<?php get_footer();