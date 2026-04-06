<?php
/**
 *  SDWeddingDirectory - Seating Chart Menu Filter
 *  ---------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Seating_Chart_Menu_Filter' ) && class_exists( 'SDWeddingDirectory_Seating_Chart_Filters' ) ) {

    class SDWeddingDirectory_Seating_Chart_Menu_Filter extends SDWeddingDirectory_Seating_Chart_Filters {

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct() {

            add_filter( 'sdweddingdirectory/couple/dashboard/menu', [ $this, 'menu' ], absint( '85' ), absint( '1' ) );
        }

        public static function menu( $args = [] ) {

            return array_merge( $args, [
                'seating-chart' => [
                    'menu_show'   => apply_filters( 'sdweddingdirectory/couple-menu/seating-chart/menu-show', true ),
                    'menu_class'  => apply_filters( 'sdweddingdirectory/couple-menu/seating-chart/menu-class', '' ),
                    'menu_id'     => apply_filters( 'sdweddingdirectory/couple-menu/seating-chart/menu-id', '' ),
                    'menu_name'   => apply_filters( 'sdweddingdirectory/couple-menu/seating-chart/menu-name', esc_attr__( 'Seating Chart', 'sdweddingdirectory' ) ),
                    'menu_icon'   => apply_filters( 'sdweddingdirectory/couple-menu/seating-chart/menu-icon', esc_attr( 'sdweddingdirectory-four-side-table-1' ) ),
                    'menu_active' => parent::dashboard_page_set( 'seating-chart' ) ? sanitize_html_class( 'active' ) : null,
                    'menu_link'   => apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'seating-chart' ) ),
                ],
            ] );
        }
    }

    SDWeddingDirectory_Seating_Chart_Menu_Filter::get_instance();
}
