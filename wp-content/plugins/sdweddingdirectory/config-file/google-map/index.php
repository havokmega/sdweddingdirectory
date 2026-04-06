<?php
/**
 *  SDWeddingDirectory - Google Map
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Google_Map_Config' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Google Map
     *  -------------------------------
     */
    class SDWeddingDirectory_Google_Map_Config extends SDWeddingDirectory_Config{

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

            /**
             *  Register map provider
             *  ---------------------
             */
            add_filter( 'sdweddingdirectory/map/provider', [ $this, 'register_provider' ], absint( '20' ), absint( '1' ) );

            /**
             *  Load scripts
             *  ------------
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], absint( '500' ) );
        }

        /**
         *  Register map provider list
         *  --------------------------
         */
        public static function register_provider( $args = [] ){

            return array_merge( $args, [

                'google'   =>  esc_attr__( 'Google Map', 'sdweddingdirectory' ),
            ] );
        }

        /**
         *  Have marker map shortcode ?
         *  ---------------------------
         */
        public static function has_map_shortcode(){

            global $post;

            if( $post instanceof WP_Post ){

                if( has_shortcode( $post->post_content, esc_attr( 'sdweddingdirectory_marker_map' ) ) ){

                    return true;
                }
            }

            return false;
        }

        /**
         *  Map requested via script flag filter
         *  ------------------------------------
         */
        public static function map_flag_requested(){

            $request_collection = apply_filters( 'sdweddingdirectory/enable-script/map', [] );

            if( is_array( $request_collection ) ){

                foreach( $request_collection as $value ){

                    if( $value === true || $value === esc_attr( 'true' ) || $value === absint( '1' ) || $value === esc_attr( '1' ) ){

                        return true;
                    }
                }
            }

            return false;
        }

        /**
         *  Load map scripts condition
         *  --------------------------
         */
        public static function should_load_map_script(){

            if( is_admin() ){

                return false;
            }

            if(
                self:: map_flag_requested() ||
                parent:: is_dashboard() ||
                is_singular( esc_attr( 'website' ) ) ||
                is_singular( esc_attr( 'vendor' ) ) ||
                is_singular( esc_attr( 'venue' ) ) ||
                self:: has_map_shortcode()
            ){

                return true;
            }

            return false;
        }

        /**
         *  Get saved map API key
         *  ---------------------
         */
        public static function map_api_key(){

            $option_keys = [
                esc_attr( 'sdweddingdirectory_map_key' ),
                esc_attr( 'sdweddingdirectory_google_map_key' ),
                esc_attr( 'google_map_api_key_here' ),
            ];

            foreach( $option_keys as $key ){

                $value = sdweddingdirectory_option( $key );

                if( parent:: _have_data( $value ) ){

                    return sanitize_text_field( $value );
                }
            }

            return '';
        }

        /**
         *  Place API support enabled ?
         *  --------------------------
         */
        public static function place_service_enabled(){

            $option_keys = [
                esc_attr( 'sdweddingdirectory_google_map_place_api_supported' ),
                esc_attr( 'google_map_place_api_supported' ),
            ];

            foreach( $option_keys as $key ){

                $value = sdweddingdirectory_option( $key );

                if( $value === esc_attr( 'on' ) || $value === true || $value === absint( '1' ) || $value === esc_attr( '1' ) ){

                    return true;
                }
            }

            return false;
        }

        /**
         *  Enqueue Google map script
         *  -------------------------
         */
        public static function enqueue_scripts(){

            if( ! self:: should_load_map_script() ){

                return;
            }

            $api_key = self:: map_api_key();

            /**
             *  Google Map API Script
             *  ---------------------
             */
            $api_args = [
                'libraries' => esc_attr( 'places' ),
                'v'         => esc_attr( 'weekly' ),
                'language'  => esc_attr( 'en' ),
                'region'    => esc_attr( 'us' ),
            ];

            if( parent:: _have_data( $api_key ) ){

                $api_args['key'] = sanitize_text_field( $api_key );
            }

            wp_enqueue_script(
                esc_attr( 'sdweddingdirectory-google-map-api' ),
                esc_url_raw( add_query_arg( $api_args, esc_url_raw( 'https://maps.googleapis.com/maps/api/js' ) ) ),
                [ esc_attr( 'jquery' ) ],
                null,
                true
            );

            /**
             *  SDWeddingDirectory - Google Map Script
             *  --------------------------------------
             */
            wp_enqueue_script(
                esc_attr( 'sdweddingdirectory-google-map-script' ),
                esc_url( plugin_dir_url( __FILE__ ) . esc_attr( 'script.js' ) ),
                [ esc_attr( 'jquery' ), esc_attr( 'sdweddingdirectory-google-map-api' ) ],
                esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ ) . esc_attr( 'script.js' ) ) ),
                true
            );

            /**
             *  Map defaults
             *  ------------
             */
            $map_provider = sdweddingdirectory_option( esc_attr( 'sdweddingdirectory_map_provider' ) );

            $map_provider = parent:: _have_data( $map_provider )
                            ? sanitize_text_field( $map_provider )
                            : esc_attr( 'google' );

            $map_cluster  = sdweddingdirectory_option( esc_attr( 'sdweddingdirectory_map_cluster' ) );

            $map_cluster  = parent:: _have_data( $map_cluster )
                            ? esc_url( $map_cluster )
                            : ( defined( 'SDWEDDINGDIRECTORY_IMAGES' ) ? esc_url( SDWEDDINGDIRECTORY_IMAGES . 'sdweddingdirectory-map-cluster.png' ) : '' );

            $map_marker   = sdweddingdirectory_option( esc_attr( 'sdweddingdirectory_map_marker' ) );

            $map_marker   = parent:: _have_data( $map_marker )
                            ? esc_url( $map_marker )
                            : ( defined( 'SDWEDDINGDIRECTORY_IMAGES' ) ? esc_url( SDWEDDINGDIRECTORY_IMAGES . 'sdweddingdirectory-map-marker-2.svg' ) : '' );

            $map_latitude = sdweddingdirectory_option( esc_attr( 'sdweddingdirectory_latitude' ) );

            $map_latitude = parent:: _have_data( $map_latitude )
                            ? esc_attr( $map_latitude )
                            : esc_attr( '23.019469943904543' );

            $map_longitude = sdweddingdirectory_option( esc_attr( 'sdweddingdirectory_longitude' ) );

            $map_longitude = parent:: _have_data( $map_longitude )
                            ? esc_attr( $map_longitude )
                            : esc_attr( '72.5730813242451' );

            $map_zoom = sdweddingdirectory_option( esc_attr( 'map_zoom_level' ) );

            $map_zoom = parent:: _have_data( $map_zoom ) ? absint( $map_zoom ) : absint( '9' );

            $close_icon = defined( 'SDWEDDINGDIRECTORY_IMAGES' )
                        ? esc_url( SDWEDDINGDIRECTORY_IMAGES . 'close-icon.svg' )
                        : '';

            wp_localize_script(
                esc_attr( 'sdweddingdirectory-google-map-script' ),
                esc_attr( 'SDWEDDINGDIRECTORY_GOOGLE_MAP_OBJ' ),
                [
                    'map_provider'   => $map_provider,
                    'zoom_level'     => $map_zoom,
                    'marker'         => $map_marker,
                    'cluster'        => $map_cluster,
                    'close_icon'     => $close_icon,
                    'latitude'       => $map_latitude,
                    'longitude'      => $map_longitude,
                    'google_map_api' => $api_key,
                    'place_service'  => self:: place_service_enabled(),
                ]
            );
        }
    }

    /**
     *  SDWeddingDirectory - Google Map
     *  -------------------------------
     */
    SDWeddingDirectory_Google_Map_Config:: get_instance();
}
