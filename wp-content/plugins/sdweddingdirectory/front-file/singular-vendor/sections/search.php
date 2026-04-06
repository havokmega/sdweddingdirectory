<?php
/**
 * Vendor Singular - Section 2: Search Bar
 */
?>
<div class="sd-profile-search py-3 bg-light border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <?php
                get_template_part(
                    'template-parts/search/vendor-search-bar',
                    null,
                    [
                        'search_button_text' => esc_attr__( 'Search', 'sdweddingdirectory' ),
                    ]
                );
                ?>
            </div>
        </div>
    </div>
</div>
