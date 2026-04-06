<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Professional Network & Endorsements
 *  -----------------------------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Endorsements' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Endorsements extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '82' ), absint( '1' ) );
        }

        public static function widget( $args = [] ){

            if( ! parent::_is_array( $args ) ){
                return;
            }

            $args = wp_parse_args( $args, [

                'post_id'       => absint( '0' ),

                'active_tab'    => false,

                'id'            => sanitize_title( 'vendor_endorsements' ),

                'icon'          => 'fa fa-handshake-o',

                'heading'       => esc_attr__( 'Professional Network & Endorsements', 'sdweddingdirectory' ),

                'card_info'     => true,

                'handler'       => '',

            ] );

            extract( $args );

            if( empty( $post_id ) ){
                return;
            }

            $endorsed_venue_ids = get_post_meta( absint( $post_id ), sanitize_key( 'sdwd_endorsed_venues' ), true );

            if( ! is_array( $endorsed_venue_ids ) || empty( $endorsed_venue_ids ) ){
                return;
            }

            $venues = [];

            foreach( $endorsed_venue_ids as $venue_id ){
                $venue_id = absint( $venue_id );

                if( get_post_status( $venue_id ) !== 'publish' ){
                    continue;
                }

                $venue_name = get_the_title( $venue_id );
                $venue_url  = get_permalink( $venue_id );
                $thumb_id   = absint( get_post_thumbnail_id( $venue_id ) );
                $thumb_url  = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';

                if( empty( $thumb_url ) ){
                    $thumb_url = get_template_directory_uri() . '/assets/images/placeholders/vendor-post/vendor-post.jpg';
                }

                $average = apply_filters( 'sdweddingdirectory/rating/average', '', [
                    'venue_id' => $venue_id,
                ] );

                $count = apply_filters( 'sdweddingdirectory/rating/found', absint( '0' ), [
                    'venue_id' => $venue_id,
                ] );

                $venues[] = [
                    'id'      => $venue_id,
                    'name'    => $venue_name,
                    'url'     => $venue_url,
                    'image'   => $thumb_url,
                    'rating'  => $average !== '' ? $average : '0.0',
                    'reviews' => absint( $count ),
                ];
            }

            if( empty( $venues ) ){
                return;
            }

            if( $layout == absint( '1' ) ){

                ob_start();
                ?>
                <div class="card-shadow position-relative">
                    <a id="section_<?php echo esc_attr( sanitize_key( $id ) ); ?>" class="anchor-fake"></a>
                    <div class="card-shadow-header">
                        <h3><i class="<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $heading ); ?></h3>
                    </div>
                    <div class="card-shadow-body">
                        <div class="sd-endorsements-grid">
                            <?php foreach( $venues as $venue ) : ?>
                            <a href="<?php echo esc_url( $venue['url'] ); ?>" class="sd-endorsement-card">
                                <div class="sd-endorsement-img">
                                    <img src="<?php echo esc_url( $venue['image'] ); ?>" alt="<?php echo esc_attr( $venue['name'] ); ?>">
                                </div>
                                <div class="sd-endorsement-info">
                                    <div class="fw-bold"><?php echo esc_html( $venue['name'] ); ?></div>
                                    <div class="sd-endorsement-rating">
                                        <span class="text-warning"><i class="fa fa-star"></i></span>
                                        <span><?php echo esc_html( $venue['rating'] ); ?></span>
                                        <span class="text-muted">(<?php echo esc_html( $venue['reviews'] ); ?>)</span>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php
                $handler .= ob_get_clean();

                if( $card_info ){
                    print $handler;
                }
            }

            if( $layout == absint( '2' ) ){

                printf(
                    '<a href="#section_%1$s" class="%2$s"><i class="%3$s"></i> %4$s</a>',
                    esc_attr( $id ),
                    $active_tab ? sanitize_html_class( 'active' ) : '',
                    esc_attr( $icon ),
                    esc_html( $heading )
                );
            }
        }
    }

    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Endorsements::get_instance();
}
