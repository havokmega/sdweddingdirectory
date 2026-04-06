<?php
/**
 *  SDWeddingDirectory Couple Request Quote
 *  --------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Request_Quote' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Request Quote
     *  --------------------------------------
     */
    class SDWeddingDirectory_Couple_Request_Quote extends SDWeddingDirectory_Request_Quote_Database{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct(){

            /**
             *  Dashboard Page
             *  --------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [ $this, 'dashboard_page' ], absint( '75' ), absint( '1' ) );
        }

        /**
         *  Dashboard Page
         *  --------------
         */
        public static function dashboard_page( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                if( ! empty( $page ) && $page == esc_attr( 'couple-request-quote' ) ){

                    ?><div class="container"><?php

                        SDWeddingDirectory_Dashboard:: dashboard_page_header(

                            esc_attr__( 'Quote Requests', 'sdweddingdirectory-request-quote' )
                        );

                        self:: dashboard_content();

                    ?></div><?php
                }
            }
        }

        /**
         *  Dashboard Content
         *  -----------------
         */
        public static function dashboard_content(){

            $couple_id = absint( parent:: post_id() );

            if( empty( $couple_id ) ){

                return;
            }

            $query = new WP_Query( [

                'post_type'         =>  esc_attr( 'venue-request' ),

                'post_status'       =>  [ 'publish', 'pending' ],

                'posts_per_page'    =>  -1,

                'orderby'           =>  esc_attr( 'date' ),

                'order'             =>  esc_attr( 'DESC' ),

                'meta_query'        =>  [
                                            [
                                                'key'       =>  esc_attr( 'couple_id' ),
                                                'type'      =>  esc_attr( 'numeric' ),
                                                'compare'   =>  esc_attr( '=' ),
                                                'value'     =>  absint( $couple_id ),
                                            ]
                                        ],
            ] );

            if( $query->have_posts() ){
                ?>
                <div class="card-shadow">
                    <div class="card-shadow-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html__( 'Vendor', 'sdweddingdirectory-request-quote' ); ?></th>
                                        <th><?php echo esc_html__( 'Date', 'sdweddingdirectory-request-quote' ); ?></th>
                                        <th><?php echo esc_html__( 'Status', 'sdweddingdirectory-request-quote' ); ?></th>
                                        <th><?php echo esc_html__( 'Message', 'sdweddingdirectory-request-quote' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ( $query->have_posts() ){

                                    $query->the_post();

                                    $request_id     =   absint( get_the_ID() );

                                    $vendor_id      =   absint( get_post_meta( $request_id, sanitize_key( 'vendor_id' ), true ) );

                                    $venue_id     =   absint( get_post_meta( $request_id, sanitize_key( 'venue_id' ), true ) );

                                    if( empty( $vendor_id ) && ! empty( $venue_id ) ){

                                        $vendor_id  =   absint( parent:: venue_author_post_id( $venue_id ) );
                                    }

                                    $vendor_name    =   ! empty( $vendor_id ) ? get_the_title( $vendor_id ) : '';

                                    $vendor_link    =   ! empty( $vendor_id ) ? get_permalink( $vendor_id ) : '';

                                    $message        =   get_post_meta( $request_id, sanitize_key( 'request_quote_message' ), true );

                                    $message        =   wp_trim_words( wp_strip_all_tags( (string) $message ), absint( '20' ), '...' );

                                    $post_status    =   get_post_status( $request_id );

                                    $status_label   =   $post_status === 'pending'

                                                        ? esc_attr__( 'Pending', 'sdweddingdirectory-request-quote' )

                                                        : esc_attr__( 'Sent', 'sdweddingdirectory-request-quote' );

                                    $date_label     =   get_the_date( get_option( 'date_format' ), $request_id );

                                    ?>
                                    <tr>
                                        <td>
                                            <?php if( ! empty( $vendor_link ) && ! empty( $vendor_name ) ){ ?>
                                                <a href="<?php echo esc_url( $vendor_link ); ?>"><?php echo esc_html( $vendor_name ); ?></a>
                                            <?php }else{ ?>
                                                <span><?php echo esc_html__( 'Vendor', 'sdweddingdirectory-request-quote' ); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo esc_html( $date_label ); ?></td>
                                        <td><?php echo esc_html( $status_label ); ?></td>
                                        <td><?php echo esc_html( $message ); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php

                wp_reset_postdata();

            }else{
                ?>
                <div class="card-shadow">
                    <div class="card-shadow-body">
                        <p class="mb-0">
                            <?php
                            printf(
                                '%1$s <a href="%2$s">%3$s</a>',
                                esc_html__( "You haven't sent any quote requests yet.", 'sdweddingdirectory-request-quote' ),
                                esc_url( home_url( '/vendors/' ) ),
                                esc_html__( 'Browse vendors to get started!', 'sdweddingdirectory-request-quote' )
                            );
                            ?>
                        </p>
                    </div>
                </div>
                <?php
            }
        }
    }

    /**
     *  Request Quote Obj Call
     *  ----------------------
     */
    SDWeddingDirectory_Couple_Request_Quote:: get_instance();
}
