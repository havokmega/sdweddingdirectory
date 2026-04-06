<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'SDWeddingDirectory_Admin_Pages' ) ) {
class SDWeddingDirectory_Admin_Pages {
        const COUPLE_SLUG = 'sdweddingdirectory-add-couple';
        const VENDOR_SLUG = 'sdweddingdirectory-add-vendor';
        public static function init() {
            static $instance = null;
            if ( null === $instance ) {
                $instance = new self();
            }
            return $instance;
        }

        private $hook_suffixes = [];

        public function __construct() {
            add_action( 'admin_menu', [ $this, 'register_menus' ], 5 );
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        }

        public function register_menus() {
            $this->hook_suffixes['couple'] = add_submenu_page(
                'edit.php?post_type=couple',
                esc_attr__( 'Add Couple', 'sdweddingdirectory' ),
                esc_attr__( 'Add Couple', 'sdweddingdirectory' ),
                'manage_options',
                self::COUPLE_SLUG,
                [ $this, 'render_add_couple' ]
            );

            $this->hook_suffixes['vendor'] = add_submenu_page(
                'edit.php?post_type=vendor',
                esc_attr__( 'Add Vendor', 'sdweddingdirectory' ),
                esc_attr__( 'Add Vendor', 'sdweddingdirectory' ),
                'manage_options',
                self::VENDOR_SLUG,
                [ $this, 'render_add_vendor' ]
            );

        }

        public function enqueue_scripts( $hook ) {
            if ( isset( $this->hook_suffixes['couple'] ) && $hook === $this->hook_suffixes['couple'] ) {
                $this->enqueue_form_script( 'add-couple', 'create-couple.js' );
            }

            if ( isset( $this->hook_suffixes['vendor'] ) && $hook === $this->hook_suffixes['vendor'] ) {
                $this->enqueue_form_script( 'add-vendor', 'create-vendor.js' );
            }
        }

        private function enqueue_form_script( $handle_suffix, $file ) {
            $handle = 'sdweddingdirectory-admin-' . $handle_suffix;
            $path   = get_template_directory() . '/admin/js/' . $file;

            if ( ! file_exists( $path ) ) {
                return;
            }

            wp_enqueue_script(
                $handle,
                get_template_directory_uri() . '/admin/js/' . $file,
                [ 'jquery' ],
                filemtime( $path ),
                true
            );

            wp_localize_script( $handle, 'SDWEDDINGDIRECTORY_AJAX_OBJECT', [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            ] );
        }

        public function render_add_couple() {
            $this->load_form_template( 'add-couple' );
        }

        public function render_add_vendor() {
            $this->load_form_template( 'add-vendor' );
        }

        private function load_form_template( $slug ) {
            $template = get_template_directory() . '/admin/forms/' . $slug . '.php';

            if ( file_exists( $template ) ) {
                include $template;
                return;
            }

            echo '<div class="notice notice-error"><p>' . esc_html__( 'This form is not available.', 'sdweddingdirectory' ) . '</p></div>';
        }

    }

    SDWeddingDirectory_Admin_Pages::init();
}
