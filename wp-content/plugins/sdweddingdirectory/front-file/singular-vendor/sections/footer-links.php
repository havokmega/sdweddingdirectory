<?php
/**
 * Vendor Singular - Section 12: Full-Width Category Links + City Links
 */

$post_id = isset( $post_id ) ? absint( $post_id ) : absint( get_the_ID() );

$vendor_categories = get_terms([
    'taxonomy'   => sanitize_key( 'vendor-category' ),
    'parent'     => 0,
    'hide_empty' => false,
]);
?>
<div class="sd-profile-footer-links wide-tb-30">
    <div class="container">
        <h5 class="fw-bold mb-3"><?php esc_html_e( 'Other vendors for your wedding', 'sdweddingdirectory' ); ?></h5>

        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2">
                <?php
                if( ! is_wp_error( $vendor_categories ) && ! empty( $vendor_categories ) ){
                    foreach( $vendor_categories as $category ){
                        printf(
                            '<a href="%1$s" class="btn-link text-muted">%2$s</a>',
                            esc_url( get_term_link( $category ) ),
                            esc_html( $category->name )
                        );
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * City links section — only displayed on venue profiles, not vendor profiles.
 */
