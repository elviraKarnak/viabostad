<?php
// Register shortcode
add_action( 'init', function () {
    add_shortcode( 'property-listing', 'property_listing_callback' );
});

// Shortcode callback
function property_listing_callback() {
    ob_start();
    ?>

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
												<!-- <div class="field">
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
														<?php //foreach ( $terms as $term ) { ?>
															<!-- <option value="<?php echo $term->slug; ?>"> <?php echo esc_html($term->name); ?></option> -->
														<?php //} ?>
													<!-- </select> -->
												<?php } ?>
											<!-- </div>  -->
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
														'taxonomy'   => 'property-type',
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

							 <?php 

                                $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
                             
                               $product_query = new WP_Query([
                                'post_type'      => 'property',
                                'posts_per_page' => 9, // IMPORTANT (not -1)
                                'orderby' => 'id',
                                'order' => 'ASC',
                                ]);

                            if ( $product_query->have_posts() ) { ?>

							<div class="total_found_products">
								<?php
			
								
									$total        = $product_query->found_posts;
									$per_page     = $product_query->get( 'posts_per_page' );
									$current      = max( 1, get_query_var( 'paged' ) );

									$first = ( $per_page * $current ) - $per_page + 1;
									$last  = min( $total, $per_page * $current );
									?>

									<p class="search_result">
										Showing Newest Results <?php echo esc_html( $first ); ?>–<?php echo esc_html( $last ); ?>
										of <?php echo esc_html( $total ); ?>
									</p>

							</div>
					 
						
                                                                
                                            <div class="row gy-md-4 gy-3 property-slider">
                        
                                                <?php while ( $product_query->have_posts() ) :
                            
                                                    $product_query->the_post();

                                                    get_template_part( 'template-part/poperty-loop' );
                                                
                                                endwhile; ?>

                                            </div>

                                            <?php

                                

                                            // /* PAGINATION */
                                            // $big = 999999999;

                                            // $pagination = paginate_links(array(
                                            //     'base'      => str_replace($big, '%#%', esc_url('?paged=' . $big)),
                                            //     'format'    => '?paged=%#%',
                                            //     'current'   => max(1, $paged),
                                            //     'total'     => $product_query->max_num_pages,
                                            //     'type'      => 'array',
                                            // ));

                                            // if ($pagination) {
                                            //     echo '<div class="ajax-pagination"><ul>';
                                            //     foreach ($pagination as $page) {
                                            //         echo '<li>' . $page . '</li>';
                                            //     }
                                            //     echo '</ul></div>';
                                            // }

                                       
                                        ?>


                                            <?php } else { ?>
                                                <p class="no-result">No properties found.</p>
                                                <?php } wp_reset_postdata(); ?>


                                                <?php
                                                    $total_pages = $product_query->max_num_pages;

                                                    if ( $paged < $total_pages ) : ?>
                                                        
                                                        <div class="col-12 mt-md-5 mt-3">
                                                            <div class="show_more_wrapper text-center">
                                                                <a href="javascript:void(0)" 
                                                                class="primary_btn icon arrow load-more-btn"
                                                                data-page="<?php echo esc_attr($paged + 1); ?>"
                                                                data-max="<?php echo esc_attr($total_pages); ?>">
                                                                Show More
                                                                </a>
                                                            </div>
                                                        </div>

                                                    <?php endif; ?>
										
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


 
    
        let currentFilters = {};

        function getFormValues() {
            var search   = $('input[name="property-search"]').val().trim();
            var location = $('select[name="property-location"]').val();
            var type     = $('select[name="property-type"]').val();
            var price    = $('input[name="property-price"]').val().trim();

            price = price.replace(/,/g, '');

            return {
                search: search,
                location: location,
                type: type,
                price: price
            };
        }

        function load_properties(paged = 1, append = false) {

            var formData = new FormData();

            formData.append('action', 'filter_search_property');
            formData.append('paged', paged);

            formData.append('property-search', currentFilters.search);
            formData.append('property-location', currentFilters.location);
            formData.append('property-type', currentFilters.type);
            formData.append('property-price', currentFilters.price);

            // ✅ Only show full loader for fresh filter
            if(!append){
                $('#all_properties').html('<span class="loader-property"></span>');
            } else {
                $('.load-more-btn').text('Loading...');
            }

            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    if(append){
                         var temp = $('<div>').html(response);

                        var newItems  = temp.find('.property-slider').html();
                        var newButton = temp.find('.show_more_wrapper');
                        var newCount  = temp.find('.total_found_products').html();

                        console.log('New Items:', newItems);
                        console.log('New Button:', newButton);  
                            console.log('New Count:', newCount);

                        // Append new property items
                        if(newItems){
                            $('.property-slider').append(newItems);
                        }

                        // Update result counter
                        if(newCount){
                            $('.total_found_products').html(newCount);
                        }

                        // Replace Load More button
                        $('.show_more_wrapper').remove();

                        if(newButton.length){
                            $('#all_properties').append(newButton);
                        }
                    } else {
                        $('#all_properties').html(response);
                    }
                }
            });
        }
            /* FILTER SUBMIT */
            $('#filter-property').on('submit', function (e) {
                e.preventDefault();

                currentFilters = getFormValues(); // store values

                if (
                    currentFilters.search === '' &&
                    (!currentFilters.location || currentFilters.location === 'all') &&
                    (!currentFilters.type || currentFilters.type === 'all') &&
                    currentFilters.price === ''
                ) {
                    alert('Please select at least one filter option.');
                    return false;
                }

                load_properties(1, false); // load first page with new filters
            });

        
        
            /* LOAD MORE CLICK */
                $(document).on('click', '.load-more-btn', function (e) {
                    e.preventDefault();

                    var button = $(this);
                    var nextPage = button.data('page');
                    var maxPage = button.data('max');

                    load_properties(nextPage, true);

                    if(nextPage >= maxPage){
                        button.closest('.show_more_wrapper').remove();
                    }
                });

        });


    </script>

    <?php
    return ob_get_clean();
}
