<?php 

//acf theme page

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> 'false'
	));

	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Testimonials',
		'menu_title'	=> 'Testimonials',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Additional Settings',
		'menu_title'	=> 'Additional Fields',
		'parent_slug'	=> 'theme-general-settings',
	));

}

add_action('init', 'add_author_support_to_products');

function add_author_support_to_products() {
    add_post_type_support('product', 'author');
}


add_filter('manage_edit-product_columns', 'add_product_author_column');

function add_product_author_column($columns) {
    $columns['author'] = 'Author';
    return $columns;
}


// Home Page Property Filter

add_action('wp_ajax_load_products_by_category', 'load_products_by_category');
add_action('wp_ajax_nopriv_load_products_by_category', 'load_products_by_category');

function load_products_by_category() {

    $slug = sanitize_text_field($_POST['category']);

    $args = [
        'post_type'      => 'property',
        'posts_per_page' => 10,
        'orderby'        => 'ID',
        'order'          => 'ASC',
    ];

    // If not "all"
    if ($slug !== 'pills-all-tab' && $slug !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'property-type',
                'field'    => 'slug',
                'terms'    => $slug,
            ]
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="row gy-md-4 gy-3 property-slider">';

     
               while ( $query->have_posts() ) :
                    
                    $query->the_post();

                        get_template_part( 'template-part/poperty-loop' );

                      ?>
                      

                
            <?php endwhile; 

        echo '</div>';
    else :
        echo '<p>No products found.</p>';
    endif;

    wp_reset_postdata();
    wp_die();
}


// Broker Registration 
add_action('init', 'create_broker_role');

function create_broker_role() {

    //remove_role('broker'); // reset if already created

    add_role(
        'broker',
        'Broker',
        [

            // Basic
            'read' => true,
            'upload_files' => true,

            // Required to appear in Author dropdown
            'edit_posts' => true,

            // WooCommerce product capabilities
            'edit_products' => true,
            'publish_products' => true,
            'delete_products' => true,
            'edit_published_products' => true,

        ]
    );

     add_role(
        'agency',
        'Agency',
        [

            // Basic
            'read' => true,
            'upload_files' => true,

            // Required to appear in Author dropdown
            'edit_posts' => true,

            // WooCommerce product capabilities
            'edit_products' => true,
            'publish_products' => true,
            'delete_products' => true,
            'edit_published_products' => true,

        ]
    );
}

add_action('wp_ajax_nopriv_register_broker', 'handle_broker_registration');
add_action('wp_ajax_register_broker', 'handle_broker_registration');

function handle_broker_registration() {

    if (!isset($_POST['email']) || !is_email($_POST['email'])) {
        wp_send_json_error(['message' => 'Invalid email address']);
    }

    $user_type = isset($_POST['user_type']) ? sanitize_text_field($_POST['user_type']) : '';

    $email      = sanitize_email($_POST['email']);
    $username   = sanitize_user($_POST['username']);
    $password   = $_POST['password'];
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name  = sanitize_text_field($_POST['last_name']);
    $phone      = sanitize_text_field($_POST['phone']);
    $bio        = sanitize_textarea_field($_POST['bio']);
    $agency     = $_POST['agency'];

    $attachment_id = false;

    if (username_exists($username) || email_exists($email)) {
        wp_send_json_error(['message' => 'Username or Email already exists']);
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }


      wp_update_user([
          'ID' => $user_id,
          'first_name' => $first_name,
          'last_name'  => $last_name,
          'role'       => $user_type
      ]);

      update_user_meta($user_id, 'phone', $phone);
      update_user_meta($user_id, 'description', $bio);


       if (!empty($_FILES['profile_picture']['name'])) {

          require_once(ABSPATH . 'wp-admin/includes/file.php');
          require_once(ABSPATH . 'wp-admin/includes/media.php');
          require_once(ABSPATH . 'wp-admin/includes/image.php');

          $attachment_id = media_handle_upload('profile_picture', $post_id);

          if (!is_wp_error($attachment_id)) {
              update_user_meta($user_id, 'profile_picture', $attachment_id);
          } 
      }



    if($user_type == 'broker'){

        update_user_meta($user_id, 'agency_id_viabostad', $agency);
  

      // Create Broker Post
      $post_id = wp_insert_post([
          'post_type'   => 'broker',
          'post_title'  => $first_name . ' ' . $last_name,
          'post_status' => 'publish',
          'post_content'=> $bio,
          'post_author' => $user_id,
      ]);

      if (is_wp_error($post_id)) {
          wp_send_json_error(['message' => 'Failed to create broker profile']);
      }

      update_post_meta($post_id, 'bio', $bio);
      update_post_meta($post_id, '_agency_id', $agency);
      // wp_set_object_terms($post_id, $agency, 'agency');
      update_user_meta($user_id, 'broker_post_id', $post_id);
    
          if (!is_wp_error($attachment_id)) {

              // Set as featured image
              set_post_thumbnail($post_id, $attachment_id);
          } else {
              wp_send_json_error(['message' => 'Image upload failed']);
          }
      }

    // Auto login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    //$user_id = 123; // your user ID

      if ( function_exists( 'bp_core_get_user_domain' ) ) {
          $profile_url = bp_core_get_user_domain( $user_id );
          //echo $profile_url;
      }

    wp_send_json_success([
        'redirect' => $profile_url
    ]);

    wp_die();
}

add_action('wp_ajax_nopriv_check_username', 'check_username_ajax');
add_action('wp_ajax_check_username', 'check_username_ajax');

function check_username_ajax() {

    if (empty($_POST['username'])) {
        wp_send_json_error(['message' => 'Username is required']);
    }

    $username = sanitize_user($_POST['username']);

    if (username_exists($username)) {
        wp_send_json_error(['message' => 'Username already taken']);
    } else {
        wp_send_json_success(['message' => 'Username available']);
    }

    wp_die();
}


add_action('wp_ajax_nopriv_broker_search', 'broker_search_ajax');
add_action('wp_ajax_broker_search', 'broker_search_ajax');

function broker_search_ajax() {

    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $agency  = isset($_POST['agency']) ? sanitize_text_field($_POST['agency']) : '';

    $args = [
        'post_type'      => 'broker',
        'posts_per_page' => -1,
        's'              => $keyword,
    ];

  if (!empty($agency)) {
      $args['meta_query'] = [
          [
              'key'     => '_agency_id',
              'value'   => $agency,
              'compare' => '=',
          ]
      ];
  }

    $query = new WP_Query($args);

    if ($query->have_posts()){   
      
            $totalPosts = $query->found_posts;
              
            if($totalPosts > 1){
                $mess = $totalPosts. ' Results Found';
            }else{
              $mess = $totalPosts. ' Result Found';
            }
              
        ?>
   

              <!-- Results -->
              <div class="row mb-3">
                <div class="col-12">
                  <p class="results-text"><?php echo $mess; ?> </p>
                </div>
              </div>

              <!-- Cards -->
              <div class="row g-4">

                <?php while ($query->have_posts()){ 
                          
                      $query->the_post();

                    
                  $thumb_id = get_post_thumbnail_id();
                  $img_src  = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'full') : '';
                  $img_alt  = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';

                  ?>
        

                <div class="col-lg-4 col-md-6">
                  <a href="<?php the_permalink(); ?>" class="d-block">
                    <div class="agent-card">
                      <?php if ( $img_src ) : ?>
                        <img src="<?php echo esc_url($img_src[0]); ?>"
                          width="<?php echo esc_attr($img_src[1]); ?>"
                          height="<?php echo esc_attr($img_src[2]); ?>"
                          alt="<?php echo esc_attr($img_alt ?: get_the_title()); ?>"
                          class="img-fluid"
                          >
                      <?php endif; 

                      $post_id  = get_the_ID(); // Your post ID
                      $taxonomy = 'agency'; // Change to your taxonomy slug

                      $terms = wp_get_post_terms($post_id, $taxonomy);
                    
                      ?>
                      <div class="agent-info">
                        <h5><?php the_title(); ?></h5>
                        <?php if (!empty($terms) && !is_wp_error($terms)) {
                          foreach ($terms as $term) {
                              echo "<span>". $term->name. "</span>";
                          }
                      }?>
                      </div>
                    </div>
                  </a>
                </div>

                <?php } ?>

              </div>

            <?php }else{?>

              <div class="row g-4">
                <div class="col-md-12">
                  <h6>No Broker Found.</h6>
                </div>
              </div>

            <?php } wp_reset_query(); 

    wp_die();
}

add_action('wp_ajax_nopriv_filter_search_property', 'filter_search_property_cb');
add_action('wp_ajax_filter_search_property', 'filter_search_property_cb');

function filter_search_property_cb() {

    $search   = isset($_POST['property-search']) ? sanitize_text_field($_POST['property-search']) : '';
    $location = isset($_POST['property-location']) ? sanitize_text_field($_POST['property-location']) : '';
    $type     = isset($_POST['property-type']) ? sanitize_text_field($_POST['property-type']) : '';
    $price    = isset($_POST['property-price']) ? floatval($_POST['property-price']) : '';

    if( $search == 'undefined' ){
        $search = '';
    }


      $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

      $args = array(
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'paged'          => $paged,
        'tax_query'      => array(),
        'meta_query'     => array(),
        's'              => $search,
      );

    /* ==========================
       PRICE FILTER
    ========================== */

    if ( !empty($price)) {
        $args['meta_query'][] = array(
            'key'     => '_price',
            'value'   => $price,
            'compare' => '<=',
            'type'    => 'NUMERIC'
        );
    }

    /* ==========================
       LOCATION TAXONOMY
       (replace with your taxonomy)
    ========================== */


    if (!empty($location) && $location !== 'undefined' && $location !== 'all'  && $location !== 'null') {
        $args['tax_query'][] = array(
            'taxonomy' => 'location',
            'field'    => 'slug',
            'terms'    => $location,
        );
    }

    /* ==========================
       TYPE TAXONOMY
    ========================== */

    if (!empty($type) && $type !== 'undefined' && $type !== 'all' && $type !== 'null') {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-type',
            'field'    => 'slug',
            'terms'    => $type,
        );
    }


    /* If multiple tax filters */
    if ( count( $args['tax_query'] ) > 1 ) {
        $args['tax_query']['relation'] = 'AND';
    }


    //print_r($args);

    $product_query = new WP_Query( $args );

    if ( $product_query->have_posts() ) { ?>


			      <div class="total_found_products">
								<?php
		
									$total        = $product_query->found_posts;
									$per_page     = $product_query->get( 'posts_per_page' );
									$current      = $paged;

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
            //   /* PAGINATION */
            //   $big = 999999999;

            //   $pagination = paginate_links(array(
            //       'base'      => str_replace($big, '%#%', esc_url('?paged=' . $big)),
            //       'format'    => '?paged=%#%',
            //       'current'   => max(1, $paged),
            //       'total'     => $product_query->max_num_pages,
            //       'type'      => 'array',
            //   ));

            //   if ($pagination) {
            //       echo '<div class="ajax-pagination"><ul>';
            //       foreach ($pagination as $page) {
            //           echo '<li>' . $page . '</li>';
            //       }
            //       echo '</ul></div>';
            //   }


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
										
      
      <?php } else { ?>
          <p class="no-result">No properties found.</p>
          <?php } wp_reset_postdata(); 
					
    wp_reset_postdata();
    wp_die();
    
}


/*--------------------------------------------------------------
 AJAX PRODUCT SUBMISSION
--------------------------------------------------------------*/
add_action('wp_ajax_submit_property_form', 'handle_ajax_property_submission');
add_action('wp_ajax_nopriv_submit_property_form', 'handle_ajax_property_submission');

function handle_ajax_property_submission() {

    //check_ajax_referer('submit_property_form', 'nonce');

    if (empty($_POST['property_title']) || empty($_POST['property_description'])) {
        wp_send_json_error(['message' => 'Required fields missing.']);
    }

    $property_data = [
        'post_title'   => sanitize_text_field($_POST['property_title']),
        'post_content' => wp_kses_post($_POST['property_description']),
        'post_status'  => 'publish',
        'post_type'    => 'property',
        'post_author'  => get_current_user_id(),
    ];

    $property_id = wp_insert_post($property_data);

    if (!$property_id) {
        wp_send_json_error(['message' => 'Property creation failed.']);
    }

    
        /*--------------------------------
        Save ACF Google Map Field
        --------------------------------*/

        if (!empty($_POST['acf_map']) && is_array($_POST['acf_map'])) {

            $map = $_POST['acf_map'];

                $map_data = array(
                'address' => sanitize_text_field($map['address'] ?? ''),
                'lat' => floatval($map['lat'] ?? 0),
                'lng' => floatval($map['lng'] ?? 0),
                'zoom' => intval($map['zoom'] ?? 14),
                'name' => sanitize_text_field($map['name'] ?? ''),
                'street_number' => sanitize_text_field($map['street_number'] ?? ''),
                'street_name' => sanitize_text_field($map['street_name'] ?? ''),
                'city' => sanitize_text_field($map['city'] ?? ''),
                'state' => sanitize_text_field($map['state'] ?? ''),
                'post_code' => sanitize_text_field($map['post_code'] ?? ''),
                'country' => sanitize_text_field($map['country'] ?? ''),
                'country_short' => sanitize_text_field($map['country_short'] ?? '')
                );
    
            update_field('address_sp', $map_data, $property_id);

            /*--------------------------------
            Save extra location meta separately
            --------------------------------*/

            update_post_meta($property_id, 'street_number', sanitize_text_field($map['street_number'] ?? ''));
            update_post_meta($property_id, 'street_name', sanitize_text_field($map['street_name'] ?? ''));
            update_post_meta($property_id, 'city', sanitize_text_field($map['city'] ?? ''));
            update_post_meta($property_id, 'state', sanitize_text_field($map['state'] ?? ''));
            update_post_meta($property_id, 'post_code', sanitize_text_field($map['post_code'] ?? ''));
            update_post_meta($property_id, 'country', sanitize_text_field($map['country'] ?? ''));
        }
   
    /*--------------------------------
    Property Setup
    --------------------------------*/

    //wp_set_object_terms($property_id, 'simple', 'property-type');

    update_post_meta($property_id, '_regular_price', floatval($_POST['price']));
    update_post_meta($property_id, '_price', floatval($_POST['price']));
    update_post_meta($property_id, '_stock_status', 'instock');
    update_post_meta($property_id, '_visibility', 'visible');

    /*--------------------------------
      Custom Meta
    --------------------------------*/

    update_post_meta($property_id, 'bedroom_sp', intval($_POST['bedrooms']));
    update_post_meta($property_id, 'bathroom_sp', intval($_POST['bathrooms']));
    update_post_meta($property_id, 'area', intval($_POST['area']));
    update_post_meta($property_id, '_location', sanitize_text_field($_POST['location']));

    /*--------------------------------
      Categories (ONLY existing)
    --------------------------------*/

    if (!empty($_POST['propertytype'])) {

        $term_ids = array_map('intval', $_POST['propertytype']);
        wp_set_object_terms($property_id, $term_ids, 'property-type');
    }

    
        /*--------------------------------
        Image Upload - Gallery with Featured Image
        --------------------------------*/
  if (!empty($_FILES['property_images']['name'][0])) {

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $gallery_attachment_ids = [];

    $file_count = count($_FILES['property_images']['name']);

    for ($i = 0; $i < $file_count; $i++) {

        if ($_FILES['property_images']['error'][$i] === UPLOAD_ERR_OK) {

            $file_array = [
                'name'     => $_FILES['property_images']['name'][$i],
                'type'     => $_FILES['property_images']['type'][$i],
                'tmp_name' => $_FILES['property_images']['tmp_name'][$i],
                'error'    => $_FILES['property_images']['error'][$i],
                'size'     => $_FILES['property_images']['size'][$i]
            ];

            // Backup original $_FILES
            $original_files = $_FILES;

            // Replace with single file
            $_FILES = ['upload_file' => $file_array];

            $attachment_id = media_handle_upload('upload_file', $property_id);

            // Restore original $_FILES
            $_FILES = $original_files;

            if (!is_wp_error($attachment_id)) {

                $gallery_attachment_ids[] = $attachment_id;

                if (count($gallery_attachment_ids) === 1) {
                    set_post_thumbnail($property_id, $attachment_id);
                }

            } else {
                error_log($attachment_id->get_error_message());
            }
        }
    }

    if (!empty($gallery_attachment_ids)) {
        update_field('property_images', $gallery_attachment_ids, $property_id);
    }
}


    wp_send_json_success(['message' => 'Property published successfully']);
}

add_filter( 'login_redirect', function( $redirect_to, $request, $user ) {

    // If no user object, return default
    if ( ! isset( $user->roles ) ) {
        return $redirect_to;
    }

    // Admin → Dashboard
    if ( in_array( 'administrator', (array) $user->roles ) ) {
        return admin_url();
    }

    // Other users → BuddyPress profile
    if ( function_exists( 'bp_core_get_user_domain' ) ) {
        return bp_core_get_user_domain( $user->ID );
    }

    return home_url();

}, 10, 3 );