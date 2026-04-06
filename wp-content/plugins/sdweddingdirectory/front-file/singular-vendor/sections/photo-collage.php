<?php
/**
 * Vendor Singular - Section 3: Photo Collage
 */

$post_id = isset( $post_id ) ? absint( $post_id ) : absint( get_the_ID() );

if( empty( $post_id ) ){
    return;
}

$company_name = get_post_meta( $post_id, sanitize_key( 'company_name' ), true );
$company_name = ! empty( $company_name ) ? $company_name : get_the_title( $post_id );

$gallery_meta = get_post_meta( $post_id, sanitize_key( 'venue_gallery' ), true );
$gallery_ids = is_string( $gallery_meta ) ? array_filter( array_map( 'absint', explode( ',', $gallery_meta ) ) ) : [];

$fallback_image = get_template_directory_uri() . '/assets/images/placeholders/vendor-post/vendor-post.jpg';

$featured_id = absint( get_post_thumbnail_id( $post_id ) );
$featured_url = $featured_id ? wp_get_attachment_image_url( $featured_id, 'large' ) : '';

$images = [];

foreach( $gallery_ids as $image_id ){
    $image_url = wp_get_attachment_image_url( absint( $image_id ), 'large' );

    if( ! empty( $image_url ) ){
        $images[] = $image_url;
    }

    if( count( $images ) >= 5 ){
        break;
    }
}

while( count( $images ) < 5 ){
    $images[] = ! empty( $featured_url ) ? $featured_url : $fallback_image;
}
?>
<div class="sd-profile-hero position-relative">
    <div class="container px-3">
        <div class="row gx-4 align-items-start">
            <div class="col-lg-8">
                <div class="row g-2 sd-profile-collage">
                    <div class="col-lg-6 col-md-6">
                        <img src="<?php echo esc_url( $images[0] ); ?>" class="rounded-start" alt="<?php echo esc_attr( $company_name ); ?>">
                    </div>
                    <div class="col-lg-3 col-md-3 d-flex flex-column gap-2">
                        <img src="<?php echo esc_url( $images[1] ); ?>" alt="<?php echo esc_attr( $company_name ); ?>">
                        <img src="<?php echo esc_url( $images[2] ); ?>" alt="<?php echo esc_attr( $company_name ); ?>">
                    </div>
                    <div class="col-lg-3 col-md-3 d-flex flex-column gap-2">
                        <img src="<?php echo esc_url( $images[3] ); ?>" alt="<?php echo esc_attr( $company_name ); ?>">
                        <div class="position-relative w-100 flex-fill">
                            <img src="<?php echo esc_url( $images[4] ); ?>" class="rounded-end" alt="<?php echo esc_attr( $company_name ); ?>">
                            <a href="#section_vendor_gallery" class="position-absolute bottom-0 end-0 m-2 btn btn-sm sd-profile-see-photos">
                                <i class="fa fa-th"></i> <?php esc_html_e( 'See all photos', 'sdweddingdirectory' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 mt-3 mt-lg-0">
                <?php $sidebar_element_id = 'sd-profile-sidebar'; include __DIR__ . '/sidebar-card.php'; ?>
            </div>
        </div>
    </div>
</div>
