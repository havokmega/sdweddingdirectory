<?php
/**
 *  SDWeddingDirectory Claim Venue AJAX Script Action HERE
 *  ------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Claim_Venue_AJAX' ) && class_exists( 'SDWeddingDirectory_Claim_Venue_Database' ) ){

    /**
     *  SDWeddingDirectory Claim Venue AJAX Script Action HERE
     *  ------------------------------------------------
     */
    class SDWeddingDirectory_Claim_Venue_AJAX extends SDWeddingDirectory_Claim_Venue_Database{

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
        public function __construct() {

            if( wp_doing_ajax() ){

                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    $action = esc_attr( trim( $_POST['action'] ) );

                    $allowed_actions = array(

                        esc_attr( 'sdweddingdirectory_claim_request_action' ),

                        esc_attr( 'sdwd_profile_claim_submit_action' ),
                    );

                    if( in_array( $action, $allowed_actions, true ) ) {

                        if( is_user_logged_in() ){

                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  If Found security issue
         *  -----------------------
         */
        public static function security_issue_found(){

            die( json_encode( array(

                'message'   => esc_attr__( 'Security issue!', 'sdweddingdirectory-claim-venue' ),

                'notice'    => absint( '2' )

            ) ) );
        }

        /**
         *  Is Current User Vendor ?
         *  ------------------------
         */
        public static function can_submit_claim( $user_id = 0 ){

            $user_id = absint( $user_id );

            if( $user_id === 0 || ! current_user_can( 'read' ) ){

                return false;
            }

            $user = get_userdata( $user_id );

            if( empty( $user ) ){

                return false;
            }

            return in_array( 'vendor', (array) $user->roles, true );
        }

        /**
         *  Parse Claim Payload
         *  -------------------
         */
        public static function parse_claim_payload(){

            $target_post_id = isset( $_POST['target_post_id'] ) ? absint( $_POST['target_post_id'] ) : 0;

            if( $target_post_id === 0 && isset( $_POST['venue_id'] ) ){

                $target_post_id = absint( $_POST['venue_id'] );
            }

            $claimant_name = '';

            if( isset( $_POST['claimant_name'] ) ){

                $claimant_name = sanitize_text_field( wp_unslash( $_POST['claimant_name'] ) );

            }elseif( isset( $_POST['first_name'] ) || isset( $_POST['last_name'] ) ){

                $first = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
                $last  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';

                $claimant_name = trim( $first . ' ' . $last );
            }

            $claimant_phone = '';

            if( isset( $_POST['claimant_phone'] ) ){

                $claimant_phone = sanitize_text_field( wp_unslash( $_POST['claimant_phone'] ) );

            }elseif( isset( $_POST['contact_number'] ) ){

                $claimant_phone = sanitize_text_field( wp_unslash( $_POST['contact_number'] ) );
            }

            $claimant_email = isset( $_POST['claimant_email'] )

                                ? sanitize_email( wp_unslash( $_POST['claimant_email'] ) )

                                : '';

            if( empty( $claimant_email ) ){

                $user = wp_get_current_user();

                if( ! empty( $user ) && ! empty( $user->user_email ) ){

                    $claimant_email = sanitize_email( $user->user_email );
                }
            }

            $target_slug = isset( $_POST['target_slug'] )

                            ? sanitize_title( wp_unslash( $_POST['target_slug'] ) )

                            : '';

            $target_post = get_post( absint( $target_post_id ) );

            $target_post_type = ! empty( $target_post ) ? sanitize_key( $target_post->post_type ) : '';

            if( $target_slug === '' && ! empty( $target_post ) ){

                $target_slug = sanitize_title( $target_post->post_name );
            }

            if( empty( $target_post_type ) && isset( $_POST['target_post_type'] ) ){

                $target_post_type = sanitize_key( wp_unslash( $_POST['target_post_type'] ) );
            }

            return [
                'claimant_name'      => $claimant_name,
                'claimant_phone'     => $claimant_phone,
                'claimant_email'     => $claimant_email,
                'target_post_id'     => absint( $target_post_id ),
                'target_post_type'   => $target_post_type,
                'target_slug'        => $target_slug,
                'target_post'        => $target_post,
            ];
        }

        /**
         *  Validate Claim Payload
         *  ----------------------
         */
        public static function validate_claim_payload( $payload = [] ){

            $required_ok =

                ! empty( $payload['claimant_name'] )

                && ! empty( $payload['claimant_phone'] )

                && is_email( $payload['claimant_email'] )

                && absint( $payload['target_post_id'] ) > 0;

            if( ! $required_ok ){

                return [
                    'valid'     => false,
                    'message'   => esc_attr__( 'Please complete all claim fields.', 'sdweddingdirectory-claim-venue' ),
                ];
            }

            if( empty( $payload['target_post'] ) || ! in_array( $payload['target_post_type'], [ 'vendor', 'venue' ], true ) ){

                return [
                    'valid'     => false,
                    'message'   => esc_attr__( 'Invalid target listing for claim request.', 'sdweddingdirectory-claim-venue' ),
                ];
            }

            if( sanitize_title( $payload['target_post']->post_name ) !== sanitize_title( $payload['target_slug'] ) ){

                return [
                    'valid'     => false,
                    'message'   => esc_attr__( 'Claim target URL mismatch.', 'sdweddingdirectory-claim-venue' ),
                ];
            }

            $current_user_id = absint( get_current_user_id() );

            if( absint( $payload['target_post']->post_author ) === $current_user_id ){

                return [
                    'valid'     => false,
                    'message'   => esc_attr__( 'You already own this profile.', 'sdweddingdirectory-claim-venue' ),
                ];
            }

            return [
                'valid'     => true,
                'message'   => '',
            ];
        }

        /**
         *  Insert Claim Record
         *  -------------------
         */
        public static function create_claim_record( $payload = [] ){

            $existing_claim = get_posts( [

                'post_type'      => esc_attr( 'claim-venue' ),

                'post_status'    => [ 'pending' ],

                'posts_per_page' => 1,

                'fields'         => 'ids',

                'meta_query'     => [
                    'relation' => 'AND',
                    [
                        'key'   => 'target_post_id',
                        'value' => absint( $payload['target_post_id'] ),
                    ],
                    [
                        'key'   => 'claimant_email',
                        'value' => sanitize_email( $payload['claimant_email'] ),
                    ],
                ],
            ] );

            if( ! empty( $existing_claim ) ){

                return [
                    'created'   => false,
                    'message'   => esc_attr__( 'A claim request is already pending for this profile.', 'sdweddingdirectory-claim-venue' ),
                ];
            }

            $target_title = get_the_title( absint( $payload['target_post_id'] ) );

            $claim_post_id = wp_insert_post( [

                'post_author'   => absint( get_current_user_id() ),

                'post_title'    => sprintf( esc_attr__( 'Claim Request: %s', 'sdweddingdirectory-claim-venue' ), esc_attr( $target_title ) ),

                'post_status'   => esc_attr( 'pending' ),

                'post_type'     => esc_attr( 'claim-venue' ),

                'post_content'  => '',
            ], true );

            if( is_wp_error( $claim_post_id ) || absint( $claim_post_id ) === 0 ){

                return [
                    'created'   => false,
                    'message'   => esc_attr__( 'Unable to submit claim request.', 'sdweddingdirectory-claim-venue' ),
                ];
            }

            $meta = [

                'claimant_name'      => sanitize_text_field( $payload['claimant_name'] ),
                'claimant_phone'     => sanitize_text_field( $payload['claimant_phone'] ),
                'claimant_email'     => sanitize_email( $payload['claimant_email'] ),
                'target_post_id'     => absint( $payload['target_post_id'] ),
                'target_post_type'   => sanitize_key( $payload['target_post_type'] ),
                'target_slug'        => sanitize_title( $payload['target_slug'] ),
            ];

            foreach( $meta as $key => $value ){

                update_post_meta( absint( $claim_post_id ), sanitize_key( $key ), $value );
            }

            return [
                'created'   => true,
                'message'   => esc_attr__( 'Claim request sent to admin.', 'sdweddingdirectory-claim-venue' ),
            ];
        }

        /**
         *  Unified Claim Submit Action
         *  ---------------------------
         */
        public static function process_claim_request( $nonce_action = '' ){

            if( ! is_user_logged_in() ){

                self:: security_issue_found();
            }

            $current_user_id = absint( get_current_user_id() );

            if( ! self:: can_submit_claim( $current_user_id ) ){

                die( json_encode( array(

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr__( 'Only vendor accounts can submit claim requests.', 'sdweddingdirectory-claim-venue' ),

                ) ) );
            }

            $has_nonce   = isset( $_POST['security'] ) && $_POST['security'] !== '';
            $valid_nonce = $has_nonce ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), $nonce_action ) : false;

            if( ! $valid_nonce ){

                self:: security_issue_found();
            }

            $payload = self:: parse_claim_payload();

            $validation = self:: validate_claim_payload( $payload );

            if( empty( $validation['valid'] ) ){

                die( json_encode( array(

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr( $validation['message'] ),

                ) ) );
            }

            $created = self:: create_claim_record( $payload );

            if( empty( $created['created'] ) ){

                die( json_encode( array(

                    'notice'    => absint( '2' ),

                    'message'   => esc_attr( $created['message'] ),

                ) ) );
            }

            die( json_encode( array(

                'notice'    => absint( '1' ),

                'message'   => esc_attr( $created['message'] ),

            ) ) );
        }

        /**
         *  Legacy Action Name (Now Clean Claim Flow)
         *  -----------------------------------------
         */
        public static function sdweddingdirectory_claim_request_action(){

            self:: process_claim_request( esc_attr( 'sdweddingdirectory_claim_venue_security' ) );
        }

        /**
         *  Profile Claim Submit Action
         *  ---------------------------
         */
        public static function sdwd_profile_claim_submit_action(){

            self:: process_claim_request( esc_attr( 'sdwd_profile_claim_submit' ) );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Claim_Venue_AJAX:: get_instance();
}
