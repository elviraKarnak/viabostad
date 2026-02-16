<?php
// Register shortcode
add_action( 'init', function () {
    add_shortcode( 'home-search', 'home_search_callback' );
});

// Shortcode callback
function home_search_callback() {
    ob_start();
    ?>
      
          <div class="find_property_wrapper">
            <div class="row align-items-center gy-md-4 gy-3">
              <div class="col-xxl-9 col-xl-8 col-12">
                <div class="form_wrapper">
                  <div class="field">
                    <label for="location"
                      ><img
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/location.svg"
                        alt="location"
                        width="14"
                        height="20"
                      />
                      Location</label
                    >
                    <select name="location" id="location">
                      <option value="all">Choose your location</option>
                    </select>
                  </div>
                  <div class="field">
                    <label for="type"
                      ><img
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/home.svg"
                        alt="home"
                        width="20"
                        height="20"
                      />
                      What type you looking for</label
                    >
                    <select name="type" id="type">
                      <option value="villa">Villa</option>
                    </select>
                  </div>
                  <div class="field">
                    <label for="price"
                      ><img
                        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/price.svg"
                        alt="price"
                        width="20"
                        height="20"
                      />
                      Price</label
                    >
                    <input
                      type="text"
                      id="price"
                      placeholder="22,500,000"
                      readonly
                    />
                  </div>
                </div>
              </div>
              <div class="col-xxl-3 col-xl-4 col-12">
                <div class="btn_groups">
                  <a href="#" class="primary_btn icon search">Find Property</a>
                  <button>
                    <img
                      src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/filter_btn.svg"
                      alt="filter_btn"
                      width="35"
                      height="35"
                    />
                  </button>
                </div>
              </div>
            </div>
          </div>  
    <?php
    return ob_get_clean();
}
