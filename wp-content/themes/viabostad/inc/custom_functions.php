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
        'post_type'      => 'product',
        'posts_per_page' => 10,
        'orderby'        => 'ID',
        'order'          => 'ASC',
    ];

    // If not "all"
    if ($slug !== 'pills-all-tab' && $slug !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
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

                    $product_id = get_the_ID();

                    $product = wc_get_product( $product_id );

                      if ( ! $product ) {
                          return;
                      }

                      // ACF fields
                      $bedroom  = get_field('bedroom_sp', $product_id);
                      $bathroom = get_field('bathroom_sp', $product_id);
                      $area     = get_field('area', $product_id);
                      $location = get_field('location', $product_id);

                      // Product data
                      $product_link  = get_permalink();
                      $product_title = get_the_title();
                      $product_price = $product->get_price_html();
                      $product_img   = get_the_post_thumbnail_url($product_id, 'full');
                      ?>

                  <div class="col-lg-4 col-md-6">
                    <div class="inner_card product_card">

                    <!-- TOP -->
                    <div class="top">
                      <a href="<?php echo esc_url($product_link); ?>">
                        <img
                          src="<?php echo esc_url($product_img ?: get_stylesheet_directory_uri() . '/assets/images/placeholder.webp'); ?>"
                          alt="<?php echo esc_attr($product_title); ?>"
                          width="520"
                          height="300"
                        />
                      </a>

                      <div class="heart_icon">
                        <i class="far fa-heart initial"></i>
                        <i class="fas fa-heart active"></i>
                      </div>
                    </div>

                    <!-- MIDDLE -->
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

                    <!-- BOTTOM -->
                    <div class="bottom">
                      <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>

                      <div class="price_wrapper">
                        <h4 class="price"><?php echo $product_price; ?></h4>

                        <a href="<?php echo esc_url($product_link); ?>" class="arrow_btn">
                          <img
                            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-btn-lgblue.svg"
                            width="50"
                            height="50"
                          />
                        </a>
                      </div>
                    </div>

                  </div>
                </div>
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



      $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => array(),
        'meta_query'     => array(),
        's'              => $search,
      );

    /* ==========================
       PRICE FILTER
    ========================== */

    if ( ! empty( $price ) ) {
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

    if ( !empty($location)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'location', // change if different
            'field'    => 'slug',
            'terms'    => $location,
        );
    }

    /* ==========================
       TYPE TAXONOMY
    ========================== */

    if (!empty($type)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat', // change if different
            'field'    => 'slug',
            'terms'    => $type,
             
        );
    }

    /* If multiple tax filters */
    if ( count( $args['tax_query'] ) > 1 ) {
        $args['tax_query']['relation'] = 'AND';
    }


    $query = new WP_Query( $args );

    if ( $query->have_posts() ) { ?>


    	  <div class="total_found_products">
								<?php
					


									// $total        = $query->found_posts;
									// $per_page     = $query->get( 'posts_per_page' );
									// $current      = max( 1, get_query_var( 'paged' ) );

									// $first = ( $per_page * $current ) - $per_page + 1;
									//$last  = min( $total, $per_page * $current );
                   $first = 1;

                  $total = $query->found_posts;

                    if ( $total > 0 ) {
                        echo '<p class="search_result">';
                        echo 'Showing Results ' . $first . '–' . $total . ' of ' . $total;
                        echo '</p>';
                    }
                 ?>

									<!-- <p class="search_result">
										Showing Newest Results <?php //echo esc_html( $first ); ?>–<?php// echo esc_html( $last ); ?>
										of <?php //echo esc_html( $total ); ?>
									</p> -->

							
							</div>

       <?php 
       
       	woocommerce_product_loop_start();
       
          while ( $query->have_posts() ) {
                $query->the_post();

                wc_get_template_part( 'content', 'product' );
            }

        woocommerce_product_loop_end();

    } else {
        echo '<p>No properties found.</p>';
    }



    wp_reset_postdata();
    wp_die();
    
}


/*--------------------------------------------------------------
 AJAX PRODUCT SUBMISSION
--------------------------------------------------------------*/
add_action('wp_ajax_submit_product_form', 'handle_ajax_product_submission');
add_action('wp_ajax_nopriv_submit_product_form', 'handle_ajax_product_submission');

function handle_ajax_product_submission() {

    //check_ajax_referer('submit_product_form', 'nonce');

    if (empty($_POST['product_title']) || empty($_POST['product_description'])) {
        wp_send_json_error(['message' => 'Required fields missing.']);
    }

    $product_data = [
        'post_title'   => sanitize_text_field($_POST['product_title']),
        'post_content' => wp_kses_post($_POST['product_description']),
        'post_status'  => 'publish',
        'post_type'    => 'product',
        'post_author'  => get_current_user_id(),
    ];

    $product_id = wp_insert_post($product_data);

    if (!$product_id) {
        wp_send_json_error(['message' => 'Product creation failed.']);
    }

    /*--------------------------------
      WooCommerce Product Setup
    --------------------------------*/

    wp_set_object_terms($product_id, 'simple', 'product_type');

    update_post_meta($product_id, '_regular_price', floatval($_POST['price']));
    update_post_meta($product_id, '_price', floatval($_POST['price']));
    update_post_meta($product_id, '_stock_status', 'instock');
    update_post_meta($product_id, '_visibility', 'visible');

    /*--------------------------------
      Custom Meta
    --------------------------------*/

    update_post_meta($product_id, 'bedroom_sp', intval($_POST['bedrooms']));
    update_post_meta($product_id, 'bathroom_sp', intval($_POST['bathrooms']));
    update_post_meta($product_id, 'area', intval($_POST['area']));
    update_post_meta($product_id, '_location', sanitize_text_field($_POST['location']));
    update_post_meta($product_id, 'address_sp', sanitize_text_field($_POST['address']));

    /*--------------------------------
      Categories (ONLY existing)
    --------------------------------*/

    if (!empty($_POST['categories'])) {

        $term_ids = array_map('intval', $_POST['categories']);
        wp_set_object_terms($product_id, $term_ids, 'product_cat');
    }

    /*--------------------------------
      Image Upload
    --------------------------------*/

    if (!empty($_FILES['property_images']['name'][0])) {

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $files = $_FILES['property_images'];
        $gallery = [];

        foreach ($files['name'] as $key => $value) {

            if ($files['name'][$key]) {

                $file = [
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key]
                ];

                $_FILES = ['property_images' => $file];

                $attachment_id = media_handle_upload('property_images', $product_id);

                if (!is_wp_error($attachment_id)) {

                    $gallery[] = $attachment_id;

                    if (count($gallery) === 1) {
                        set_post_thumbnail($product_id, $attachment_id);
                    }
                }
            }
        }

        if (!empty($gallery)) {
            update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery));
        }
    }

    wp_send_json_success(['message' => 'Product submitted successfully and is pending review.']);
}
