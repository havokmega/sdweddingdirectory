<?php
/**
 *  SDWeddingDirectory - Seating Chart AJAX
 *  --------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Seating_Chart_AJAX' ) && class_exists( 'SDWeddingDirectory_Seating_Chart_Database' ) ) {

    class SDWeddingDirectory_Seating_Chart_AJAX extends SDWeddingDirectory_Seating_Chart_Database {

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            if ( ! wp_doing_ajax() ) {
                return;
            }

            add_action( 'wp_ajax_sdweddingdirectory_seating_chart_save', [ $this, 'save' ] );
        }

        public static function save() {

            if ( ! is_user_logged_in() || ! parent::can_manage_chart() ) {
                wp_send_json_error( [ 'message' => esc_attr__( 'Unauthorized request.', 'sdweddingdirectory' ) ], 403 );
            }

            $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

            if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'sdweddingdirectory_seating_chart_nonce' ) ) {
                wp_send_json_error( [ 'message' => esc_attr__( 'Security check failed.', 'sdweddingdirectory' ) ], 403 );
            }

            $chart_data_raw = isset( $_POST['chart_data'] ) ? wp_unslash( $_POST['chart_data'] ) : '';
            $chart_data     = json_decode( $chart_data_raw, true );

            if ( ! is_array( $chart_data ) ) {
                wp_send_json_error( [ 'message' => esc_attr__( 'Invalid chart payload.', 'sdweddingdirectory' ) ], 422 );
            }

            $saved = parent::save_chart_data( $chart_data );

            if ( ! is_array( $saved ) ) {
                wp_send_json_error( [ 'message' => esc_attr__( 'Unable to save chart.', 'sdweddingdirectory' ) ], 500 );
            }

            wp_send_json_success( [
                'message' => esc_attr__( 'Seating chart saved successfully.', 'sdweddingdirectory' ),
                'data'    => $saved,
            ] );
        }
    }

    SDWeddingDirectory_Seating_Chart_AJAX::get_instance();
}
