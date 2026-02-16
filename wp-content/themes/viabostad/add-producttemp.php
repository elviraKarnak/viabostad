<?php
/**
 * Template Name: Add Product Form
 * Description: Custom product submission form styled like Viabostad registration
 */

get_header();
?>

<style>
    /* Form Styling - Viabostad Design */
    :root {
        --primary-color: #2EAADC;
        --primary-dark: #1a7da8;
        --text-dark: #1a3a4a;
        --text-light: #7c8a95;
        --bg-light: #f8fafc;
        --bg-input: #f1f7fa;
        --border-color: #e2e8f0;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 24px rgba(0, 0, 0, 0.12);
    }

    .product-form-wrapper {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: var(--bg-light);
        min-height: 100vh;
        padding: 60px 0;
    }

    .product-form-container {
        max-width: 680px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .product-form-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        padding: 48px;
    }

    .product-form-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .product-form-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .product-form-header p {
        color: var(--text-light);
        font-size: 15px;
    }

    .form-section {
        margin-bottom: 36px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--bg-light);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .form-group label .required {
        color: #e74c3c;
        margin-left: 2px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="number"],
    .form-group input[type="url"],
    .form-group input[type="tel"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 14px 16px;
        background: var(--bg-input);
        border: 2px solid transparent;
        border-radius: 8px;
        font-size: 15px;
        color: var(--text-dark);
        transition: all 0.3s ease;
        outline: none;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        background: var(--white);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(46, 170, 220, 0.1);
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .form-group select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%231a3a4a' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
    }

    .helper-text {
        font-size: 13px;
        color: var(--text-light);
        margin-top: 6px;
    }

    /* Image Upload */
    .image-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: var(--bg-input);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .image-upload-area:hover {
        border-color: var(--primary-color);
        background: rgba(46, 170, 220, 0.05);
    }

    .upload-icon {
        width: 64px;
        height: 64px;
        background: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: var(--shadow-sm);
    }

    .upload-text {
        font-size: 15px;
        color: var(--text-dark);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .upload-formats {
        font-size: 13px;
        color: var(--text-light);
    }

    /* Categories */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .category-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px;
        background: var(--bg-input);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .category-item:hover {
        background: rgba(46, 170, 220, 0.1);
    }

    .category-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary-color);
    }

    /* Buttons */
    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 32px;
    }

    .btn {
        padding: 14px 32px;
        border: none;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary-color);
        color: var(--white);
        flex: 1;
        box-shadow: 0 4px 12px rgba(46, 170, 220, 0.3);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 170, 220, 0.4);
    }

    .btn-secondary {
        background: var(--bg-light);
        color: var(--text-dark);
        border: 2px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: var(--white);
        border-color: var(--text-dark);
    }

    /* Success Message */
    .success-message {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: none;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    }

    .success-message.show {
        display: flex;
    }

    @media (max-width: 768px) {
        .product-form-card {
            padding: 32px 24px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="product-form-wrapper">
    <div class="product-form-container">
        <div class="product-form-card">
     
            <div class="product-form-header">
                <h1>Add New Product</h1>
                <p>Create a new property listing for your portfolio</p>
            </div>

            <form method="post" enctype="multipart/form-data" id="addProductForm">
                <?php //wp_nonce_field('submit_product_form', 'product_form_nonce'); ?>

                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    
                    <div class="form-group">
                        <label for="product_title">Property Title <span class="required">*</span></label>
                        <input type="text" id="product_title" name="product_title" placeholder="e.g., Luxury Villa in Stockholm" required>
                    </div>

                    <div class="form-group">
                        <label for="product_description">Description <span class="required">*</span></label>
                        <textarea id="product_description" name="product_description" placeholder="Provide a detailed description of the property..." required></textarea>
                        <div class="helper-text">Tell us about the property's features, amenities, and what makes it special</div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="form-section">
                    <h3 class="section-title">Property Details</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bedrooms">Bedrooms <span class="required">*</span></label>
                            <input type="number" id="bedrooms" name="bedrooms" min="0" placeholder="2" required>
                        </div>
                        <div class="form-group">
                            <label for="bathrooms">Bathrooms <span class="required">*</span></label>
                            <input type="number" id="bathrooms" name="bathrooms" min="0" placeholder="2" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="area">Area (sqm) <span class="required">*</span></label>
                            <input type="number" id="area" name="area" min="0" placeholder="144" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price ($) <span class="required">*</span></label>
                            <input type="number" id="price" name="price" min="0" placeholder="2250000" required>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="location">Location <span class="required">*</span></label>
                        <input type="text" id="location" name="location" placeholder="e.g., Stockholm, Sweden" required>
                    </div> -->

                   <div class="form-group">
                        <label for="acf_address">Property Location</label>

                        <input type="text" id="acf_address" name="address" placeholder="Search location..." autocomplete="off">

                        <!-- Hidden ACF Google Map Fields -->
                        <!-- <input type="hidden" name="acf_map[address]" id="acf_map_address">
                        <input type="hidden" name="acf_map[lat]" id="acf_map_lat">
                        <input type="hidden" name="acf_map[lng]" id="acf_map_lng">
                        <input type="hidden" name="acf_map[zoom]" id="acf_map_zoom" value="14">
                        <input type="hidden" name="acf_map[street_number]" id="acf_map_street_number">
                        <input type="hidden" name="acf_map[street_name]" id="acf_map_street_name">
                        <input type="hidden" name="acf_map[city]" id="acf_map_city">
                        <input type="hidden" name="acf_map[state]" id="acf_map_state">
                        <input type="hidden" name="acf_map[post_code]" id="acf_map_post_code">
                        <input type="hidden" name="acf_map[country]" id="acf_map_country"> -->
                    </div>
                </div>

                <!-- Property Images -->
                <div class="form-section">
                    <h3 class="section-title">Property Images</h3>
                    
                    <div class="form-group">
                        <label>Upload Images <span class="required">*</span></label>
                        <div class="image-upload-area">
                            <input type="file" id="property_images" name="property_images[]" multiple accept="image/*" required>
                            <div class="upload-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="#2EAADC">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                </svg>
                            </div>
                            <div class="upload-text">Choose Photos</div>
                            <div class="upload-formats">Accepted formats: JPG, PNG, GIF (Max: 5MB)</div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="form-section">
                    <h3 class="section-title">Property Categories</h3>

                     <?php
                        $terms = get_terms([
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true, // set false if you want empty terms too
                        ]);

                    if (!empty($terms) && !is_wp_error($terms)) {?>
                        <div class="categories-grid">
                            <?php foreach ( $terms as $term ) { ?>
                                <div class="category-item">
                                    <input type="checkbox" id="<?php echo $term->slug; ?>" name="categories[]" value="<?php echo $term->term_id; ?>">
                                    <label for="<?php echo $term->slug; ?>"><?php echo esc_html($term->name); ?></label>
                                </div>
                            <?php  } ?> 
                        </div>
                        <?php } ?>
                    </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" name="submit_product" class="btn btn-primary">
                        <span>Publish Property</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8-8-8z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {


        // function initAutocomplete() {

        //     const input = document.getElementById('acf_address');

        //     const autocomplete = new google.maps.places.Autocomplete(input);

        //     autocomplete.addListener('place_changed', function () {

        //         const place = autocomplete.getPlace();

        //         if (!place.geometry) return;

        //         document.getElementById('acf_map_address').value = place.formatted_address;
        //         document.getElementById('acf_map_lat').value = place.geometry.location.lat();
        //         document.getElementById('acf_map_lng').value = place.geometry.location.lng();

        //         let components = place.address_components;

        //         components.forEach(function(component) {

        //             let types = component.types;

        //             if (types.includes('street_number')) {
        //                 document.getElementById('acf_map_street_number').value = component.long_name;
        //             }

        //             if (types.includes('route')) {
        //                 document.getElementById('acf_map_street_name').value = component.long_name;
        //             }

        //             if (types.includes('locality')) {
        //                 document.getElementById('acf_map_city').value = component.long_name;
        //             }

        //             if (types.includes('administrative_area_level_1')) {
        //                 document.getElementById('acf_map_state').value = component.long_name;
        //             }

        //             if (types.includes('postal_code')) {
        //                 document.getElementById('acf_map_post_code').value = component.long_name;
        //             }

        //             if (types.includes('country')) {
        //                 document.getElementById('acf_map_country').value = component.long_name;
        //             }

        //         });

        //     });
        // }










        $('#addProductForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append('action', 'submit_product_form');
            //formData.append('nonce', product_ajax_obj.nonce);

            $.ajax({
                url: '<?php echo home_url('/wp-admin/admin-ajax.php')?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('.btn-primary')
                        .prop('disabled', true)
                        .html('Submitting...');
                },

                success: function (response) {

                    $('.btn-primary')
                        .prop('disabled', false)
                        .html('Publish Property');

                    if (response.success) {

                        $('.success-message').remove();

                        $('#addProductForm').before(
                            '<div class="success-message show">' +
                            response.data.message +
                            '</div>'
                        );

                        $('#addProductForm')[0].reset();

                    } else {
                        alert(response.data.message);
                    }
                },

                error: function () {
                    alert('Something went wrong.');
                }
            });

        });

    });

</script>




<?php
get_footer();
?>