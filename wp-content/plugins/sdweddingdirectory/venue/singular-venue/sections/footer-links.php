<?php
/**
 * Venue Singular - Section 12: Full-Width Category Links + City Links
 */

$post_id = isset( $post_id ) ? absint( $post_id ) : absint( get_the_ID() );

$venue_types = get_terms([
    'taxonomy'   => sanitize_key( 'venue-type' ),
    'parent'     => 0,
    'hide_empty' => false,
]);
?>
<div class="sd-profile-footer-links wide-tb-30">
    <div class="container">
        <h5 class="fw-bold mb-3"><?php esc_html_e( 'Other venues for your wedding', 'sdweddingdirectory' ); ?></h5>

        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2">
                <?php
                if( ! is_wp_error( $venue_types ) && ! empty( $venue_types ) ){
                    foreach( $venue_types as $type ){
                        printf(
                            '<a href="%1$s" class="btn-link text-muted">%2$s</a>',
                            esc_url( get_term_link( $type ) ),
                            esc_html( $type->name )
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
 * City links section — nearby cities toggle + all cities fallback.
 */
$venue_locations = get_terms([
    'taxonomy'   => sanitize_key( 'venue-location' ),
    'parent'     => 0,
    'hide_empty' => true,
    'orderby'    => 'name',
]);

$current_location_terms = wp_get_post_terms( $post_id, 'venue-location' );
$nearby_cities = [];

if( ! is_wp_error( $current_location_terms ) && ! empty( $current_location_terms ) ){
    $current_city = $current_location_terms[0];
    $parent_region_id = absint( $current_city->parent );

    if( $parent_region_id > 0 ){
        $sibling_terms = get_terms([
            'taxonomy'   => 'venue-location',
            'parent'     => $parent_region_id,
            'hide_empty' => true,
            'orderby'    => 'name',
            'exclude'    => [ absint( $current_city->term_id ) ],
        ]);

        if( ! is_wp_error( $sibling_terms ) ){
            $nearby_cities = $sibling_terms;
        }
    }
}

$has_nearby = ! empty( $nearby_cities );

if( ! is_wp_error( $venue_locations ) && ! empty( $venue_locations ) ) : ?>
<div class="sd-profile-city-links wide-tb-30">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><?php esc_html_e( 'Browse venues by city', 'sdweddingdirectory' ); ?></h5>
            <?php if( $has_nearby ) : ?>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-dark active" data-city-view="nearby"><?php esc_html_e( 'Nearby Cities', 'sdweddingdirectory' ); ?></button>
                <button type="button" class="btn btn-outline-dark" data-city-view="all"><?php esc_html_e( 'All Cities', 'sdweddingdirectory' ); ?></button>
            </div>
            <?php endif; ?>
        </div>

        <?php if( $has_nearby ) : ?>
        <div class="row g-2 sd-city-view" data-view="nearby">
            <?php foreach( $nearby_cities as $location ) :
                $count = absint( $location->count );
                if( $count < 1 ) continue;
            ?>
            <div class="col-6 col-md-3">
                <a href="<?php echo esc_url( get_term_link( $location ) ); ?>" class="sd-city-link-item">
                    <?php echo esc_html( $location->name ); ?>
                    <span class="text-muted">(<?php echo esc_html( $count ); ?>)</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="row g-2 sd-city-view" data-view="all" <?php echo $has_nearby ? 'style="display:none;"' : ''; ?>>
            <?php foreach( $venue_locations as $location ) :
                $count = absint( $location->count );
                if( $count < 1 ) continue;
            ?>
            <div class="col-6 col-md-3">
                <a href="<?php echo esc_url( get_term_link( $location ) ); ?>" class="sd-city-link-item">
                    <?php echo esc_html( $location->name ); ?>
                    <span class="text-muted">(<?php echo esc_html( $count ); ?>)</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
(function(){
    document.querySelectorAll('[data-city-view]').length && document.querySelectorAll('.btn-group [data-city-view]').forEach(function(btn){
        btn.addEventListener('click', function(){
            var view = this.getAttribute('data-city-view');
            this.closest('.btn-group').querySelectorAll('.btn').forEach(function(b){ b.classList.remove('active'); });
            this.classList.add('active');
            this.closest('.sd-profile-city-links').querySelectorAll('.sd-city-view').forEach(function(el){
                el.style.display = el.getAttribute('data-view') === view ? '' : 'none';
            });
        });
    });
})();
</script>
<?php endif; ?>
