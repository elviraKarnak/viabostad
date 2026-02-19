<?php 
/**
 * Use Classic Editor for specific CPT only
 */
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {

    // Replace 'your_cpt_slug' with your actual post type slug
    if ($post_type === 'property') {
        return false; // Disable Gutenberg (enable Classic Editor)
    }

    return $use_block_editor; // Keep Gutenberg for others
}, 10, 2);


/**
 * Add "My Properties" tab in BuddyPress profile
 */
function viabostad_add_properties_tab() {

    bp_core_new_nav_item([
        'name'                => 'My Properties',
        'slug'                => 'my-properties',
        'screen_function'     => 'viabostad_properties_screen',
        'default_subnav_slug' => 'my-properties',
        'position'            => 40,
    ]);
}
add_action('bp_setup_nav', 'viabostad_add_properties_tab');


function viabostad_properties_screen() {

    add_action('bp_template_content', 'viabostad_properties_content');
    bp_core_load_template('members/single/plugins');
}

function viabostad_properties_content() {

    $user_id = bp_displayed_user_id();

    $args = [
        'post_type'      => 'property',
        'posts_per_page' => -1,
        'author'         => $user_id,
        'post_status'    => 'publish'
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        echo '<div class="row gy-md-4 gy-3 property-slider">';

        while ($query->have_posts()) {
            $query->the_post();
                get_template_part( 'template-part/poperty-loop' );
                   
           ?>
            
            <?php }  echo '</div>';

    } else {
        echo '<p>No properties found.</p>';
    }

    wp_reset_postdata();
}


/**
 * Add "Property" tab in BuddyPress profile
 */
function viabostad_add_property_tab() {

    bp_core_new_nav_item([
        'name'                => 'Add Property',
        'slug'                => 'add-property',
        'screen_function'     => 'viabostad_property_screen',
        'default_subnav_slug' => 'property',
        'position'            => 40,
    ]);
}
add_action('bp_setup_nav', 'viabostad_add_property_tab');


function viabostad_property_screen() {

    add_action('bp_template_content', 'viabostad_property_template');
    bp_core_load_template('members/single/plugins');
}

function viabostad_property_template() {

    locate_template([
        'buddypress/members/single/add-property.php'
    ], true);

}
