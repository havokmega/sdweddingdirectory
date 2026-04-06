<?php
/**
 * Venue Singular - Section 2: Search Bar
 */
?>
<div class="sd-profile-search py-3 bg-light border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <?php
                get_template_part(
                    'template-parts/search/venue-search-bar',
                    null,
                    [
                        'search_button_text' => esc_attr__( 'Search', 'sdweddingdirectory' ),
                        'location_placeholder' => esc_attr__( 'Location', 'sdweddingdirectory' ),
                    ]
                );
                ?>
            </div>
        </div>
    </div>
</div>
