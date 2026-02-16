<?php 
      
    $product_id = get_the_ID();
    $product    = wc_get_product($product_id);


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
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
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