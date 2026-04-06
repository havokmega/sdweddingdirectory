<?php
/**
 * Planning tool sticky signup CTA.
 */

$page_id = isset( $args['page_id'] ) ? absint( $args['page_id'] ) : absint( get_queried_object_id() );

if( ! $page_id ){
    return;
}

$page_label      = get_the_title( $page_id );
$menu_locations  = get_nav_menu_locations();
$planning_root   = 0;
$planning_items  = [];
$current_url     = get_permalink( $page_id );
$register_target = class_exists( 'SDWeddingDirectory_Config' )
    ? '#' . SDWeddingDirectory_Config:: popup_id( 'couple_register' )
    : '#sdweddingdirectory_couple_registration_model_popup';

$normalize_path = static function( $url = '' ){
    $path = wp_parse_url( $url, PHP_URL_PATH );

    if( empty( $path ) ){
        return '/';
    }

    return untrailingslashit( $path );
};

if( ! empty( $menu_locations['primary-menu'] ) ){
    $menu_items = wp_get_nav_menu_items( absint( $menu_locations['primary-menu'] ) );

    if( is_array( $menu_items ) && ! empty( $menu_items ) ){
        foreach ( $menu_items as $menu_item ) {
            $classes = is_array( $menu_item->classes ) ? $menu_item->classes : [];

            if(
                absint( $menu_item->menu_item_parent ) === 0 &&
                in_array( 'sd-mega', $classes, true ) &&
                in_array( 'sd-mega-planning', $classes, true )
            ){
                $planning_root = absint( $menu_item->ID );
                break;
            }
        }

        if( $planning_root > 0 ){
            foreach ( $menu_items as $menu_item ) {
                if( absint( $menu_item->menu_item_parent ) !== $planning_root ){
                    continue;
                }

                $classes = is_array( $menu_item->classes ) ? $menu_item->classes : [];

                if( ! in_array( 'sd-menu-icon', $classes, true ) ){
                    continue;
                }

                $planning_items[] = $menu_item;
            }
        }
    }
}

if( ! empty( $planning_items ) ){
    $current_path = $normalize_path( $current_url );

    foreach ( $planning_items as $planning_item ) {
        if( $normalize_path( $planning_item->url ) !== $current_path ){
            continue;
        }

        if( ! empty( $planning_item->title ) ){
            $page_label = $planning_item->title;
        }

        break;
    }
}

if( empty( $page_label ) ){
    $page_label = esc_html__( 'Wedding Planning', 'sdweddingdirectory' );
}
?>
<div class="sdwd-planning-scroll-cta" data-sdwd-planning-scroll-cta aria-hidden="true">
    <div class="container">
        <div class="sdwd-planning-scroll-cta__inner">
            <span class="sdwd-planning-scroll-cta__spacer" aria-hidden="true"></span>
            <p class="sdwd-planning-scroll-cta__title"><?php echo esc_html( $page_label ); ?></p>
            <button
                type="button"
                class="sdwd-planning-scroll-cta__button"
                data-bs-toggle="modal"
                data-bs-target="<?php echo esc_attr( $register_target ); ?>"
            >
                <?php esc_html_e( 'Sign up', 'sdweddingdirectory' ); ?>
            </button>
        </div>
    </div>
</div>
