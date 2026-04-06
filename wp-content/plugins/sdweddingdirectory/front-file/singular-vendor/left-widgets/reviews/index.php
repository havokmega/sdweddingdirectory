<?php
/**
 *  SDWeddingDirectory - Vendor Left Widget: Reviews
 *  ------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Reviews' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Left_Side_Widget' ) ){

    class SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Reviews extends SDWeddingDirectory_Vendor_Singular_Left_Side_Widget{

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            // Disabled — the couple plugin review module (priority 50 + 60) already renders reviews on vendor profiles.
            // add_action( 'sdweddingdirectory/vendor/singular/left-side/widget', [ $this, 'widget' ], absint( '85' ), absint( '1' ) );
        }

        public static function widget( $args = [] ){

            if( ! parent::_is_array( $args ) ){
                return;
            }

            $args = wp_parse_args( $args, [

                'post_id'       => absint( '0' ),

                'active_tab'    => false,

                'id'            => sanitize_title( 'vendor_reviews' ),

                'icon'          => 'fa fa-star',

                'heading'       => esc_attr__( 'Reviews', 'sdweddingdirectory' ),

                'card_info'     => true,

                'handler'       => '',

            ] );

            extract( $args );

            if( empty( $post_id ) ){
                return;
            }

            $company_name = get_post_meta( absint( $post_id ), sanitize_key( 'company_name' ), true );
            $company_name = ! empty( $company_name ) ? $company_name : get_the_title( absint( $post_id ) );

            $average = apply_filters( 'sdweddingdirectory/rating/average', '', [
                'vendor_id' => absint( $post_id ),
            ] );

            $count = apply_filters( 'sdweddingdirectory/rating/found', absint( '0' ), [
                'vendor_id' => absint( $post_id ),
            ] );

            $average = $average !== '' ? $average : esc_attr( '0.0' );
            $count   = absint( $count );

            $review_query = new WP_Query([
                'post_type'      => esc_attr( 'venue-review' ),
                'post_status'    => [ 'publish' ],
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'meta_query'     => [
                    [
                        'key'     => esc_attr( 'vendor_id' ),
                        'type'    => esc_attr( 'numeric' ),
                        'compare' => esc_attr( '=' ),
                        'value'   => absint( $post_id ),
                    ],
                ],
            ]);

            if( ! $review_query->have_posts() ){
                wp_reset_postdata();

                $review_query = new WP_Query([
                    'post_type'      => esc_attr( 'venue-review' ),
                    'post_status'    => [ 'publish' ],
                    'posts_per_page' => 3,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'meta_query'     => [
                        [
                            'key'     => esc_attr( 'venue_id' ),
                            'type'    => esc_attr( 'numeric' ),
                            'compare' => esc_attr( '=' ),
                            'value'   => absint( $post_id ),
                        ],
                    ],
                ]);
            }

            if( $layout == absint( '1' ) ){

                $ai_summary = get_post_meta( absint( $post_id ), sanitize_key( 'vendor_review_ai_summary' ), true );
                $ai_summary = sanitize_textarea_field( $ai_summary );

                ob_start();
                ?>
                <div class="card-shadow position-relative">
                    <a id="section_<?php echo esc_attr( sanitize_key( $id ) ); ?>" class="anchor-fake"></a>
                    <div class="card-shadow-header d-flex justify-content-between align-items-center">
                        <h3><?php echo esc_html( sprintf( esc_html__( 'Reviews of %1$s', 'sdweddingdirectory' ), $company_name ) ); ?></h3>
                        <a href="javascript:" class="btn btn-sm text-white <?php echo ! is_user_logged_in() ? '' : 'sdweddingdirectory-request-quote-popup'; ?>" style="background-color: var(--sdweddingdirectory-color-orange);" <?php echo ! is_user_logged_in() ? apply_filters( 'sdweddingdirectory/couple-login/attr', '' ) : sprintf( 'data-venue-id="%1$s" data-event-call="0" id="%2$s"', absint( $post_id ), esc_attr( wp_unique_id( 'sdwd_review_' ) ) ); ?>>
                            <?php esc_html_e( 'Write a review', 'sdweddingdirectory' ); ?>
                        </a>
                    </div>
                    <div class="card-shadow-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="text-warning"><i class="fa fa-star"></i></span>
                            <span class="fw-bold h5 mb-0"><?php echo esc_html( $average ); ?></span>
                            <span><?php echo esc_html( sprintf( esc_html__( 'Fantastic · %1$s Reviews', 'sdweddingdirectory' ), absint( $count ) ) ); ?></span>
                            <select class="form-select form-select-sm ms-auto" style="width: auto;">
                                <option><?php esc_html_e( 'Most recent', 'sdweddingdirectory' ); ?></option>
                                <option><?php esc_html_e( 'Highest rated', 'sdweddingdirectory' ); ?></option>
                            </select>
                        </div>

                        <?php if( ! empty( $ai_summary ) ){ ?>
                            <div class="sd-review-ai-summary p-3 bg-light rounded mb-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fa fa-magic"></i>
                                    <strong><?php esc_html_e( 'Review summary', 'sdweddingdirectory' ); ?></strong>
                                    <span class="badge bg-secondary"><?php esc_html_e( 'Powered by AI', 'sdweddingdirectory' ); ?></span>
                                </div>
                                <p class="mb-0"><?php echo esc_html( $ai_summary ); ?></p>
                            </div>
                        <?php } ?>

                        <div class="sd-review-list">
                        <?php
                        if( $review_query->have_posts() ){
                            while ( $review_query->have_posts() ){
                                $review_query->the_post();

                                $review_id = absint( get_the_ID() );
                                $review_author = get_userdata( absint( get_post_field( 'post_author', $review_id ) ) );

                                $reviewer_name = '';

                                if( ! empty( $review_author ) ){
                                    $reviewer_name = trim( (string) get_user_meta( absint( $review_author->ID ), sanitize_key( 'first_name' ), true ) . ' ' . (string) get_user_meta( absint( $review_author->ID ), sanitize_key( 'last_name' ), true ) );
                                    $reviewer_name = $reviewer_name !== '' ? $reviewer_name : $review_author->display_name;
                                }

                                if( $reviewer_name === '' ){
                                    $reviewer_name = esc_html__( 'Verified Couple', 'sdweddingdirectory' );
                                }

                                $avatar_url = ! empty( $review_author ) ? get_avatar_url( absint( $review_author->ID ), [ 'size' => 50 ] ) : '';
                                if( empty( $avatar_url ) ){
                                    $avatar_url = get_template_directory_uri() . '/assets/images/placeholders/couple-dashboard/couple-profile.jpg';
                                }

                                $rating = get_post_meta( $review_id, sanitize_key( 'average_rating' ), true );
                                $rating = is_numeric( $rating ) ? (float) $rating : 0;
                                $rating = min( 5, max( 0, $rating ) );

                                $wedding_date = get_post_meta( $review_id, sanitize_key( 'wedding_date' ), true );
                                $wedding_label = ! empty( $wedding_date )
                                    ? sprintf( esc_html__( 'Married on %1$s', 'sdweddingdirectory' ), date_i18n( get_option( 'date_format' ), strtotime( $wedding_date ) ) )
                                    : get_the_date();

                                $stars_html = '';
                                for( $i = 1; $i <= 5; $i++ ){
                                    $stars_html .= sprintf( '<i class="fa %1$s"></i>', $i <= round( $rating ) ? 'fa-star' : 'fa-star-o' );
                                }
                                ?>
                                <div class="sd-review-item d-flex gap-3 mb-4">
                                    <div class="sd-review-avatar">
                                        <img src="<?php echo esc_url( $avatar_url ); ?>" class="rounded-circle" width="50" height="50" alt="<?php echo esc_attr( $reviewer_name ); ?>">
                                    </div>
                                    <div class="sd-review-content flex-fill">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo esc_html( $reviewer_name ); ?></strong>
                                            <div class="sd-review-stars"><?php echo $stars_html; ?></div>
                                        </div>
                                        <small class="text-muted"><?php echo esc_html( $wedding_label ); ?></small>
                                        <p class="mt-2"><?php echo esc_html( wp_trim_words( get_the_content(), 40 ) ); ?></p>
                                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-link"><?php esc_html_e( 'Read more', 'sdweddingdirectory' ); ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        }else{
                            ?>
                            <p class="text-muted mb-0"><?php esc_html_e( 'No reviews yet. Be the first to leave a review.', 'sdweddingdirectory' ); ?></p>
                            <?php
                        }
                        ?>
                        </div>

                        <div class="text-center mt-3">
                            <a href="javascript:" class="btn btn-outline-dark"><?php esc_html_e( 'Read all reviews', 'sdweddingdirectory' ); ?> <i class="fa fa-arrow-right"></i></a>
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

    SDWeddingDirectory_Vendor_Singular_Left_Side_Widget_Reviews::get_instance();
}
