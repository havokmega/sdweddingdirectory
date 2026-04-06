<?php
/**
 *  SDWeddingDirectory - Seating Chart Database
 *  ------------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Seating_Chart_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ) {

    class SDWeddingDirectory_Seating_Chart_Database extends SDWeddingDirectory_Config {

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Prevent inheriting SDWeddingDirectory_Config constructor hooks.
         */
        public function __construct() {}

        public static function _file_version( $file = '' ) {

            if ( empty( $file ) ) {
                return esc_attr( SDWEDDINGDIRECTORY_SEATING_CHART_VERSION );
            }

            return esc_attr( SDWEDDINGDIRECTORY_SEATING_CHART_VERSION ) . '.' . absint( filemtime( $file ) );
        }

        public static function meta_key() {

            return sanitize_key( 'sdweddingdirectory_seating_chart_data' );
        }

        public static function can_manage_chart() {

            return parent::is_couple() && ! empty( parent::post_id() );
        }

        public static function chart_data() {

            if ( ! self::can_manage_chart() ) {
                return [ 'tables' => [], 'updated_at' => '' ];
            }

            $data = get_post_meta( absint( parent::post_id() ), self::meta_key(), true );

            return self::normalize_chart_data( is_array( $data ) ? $data : [] );
        }

        public static function save_chart_data( $data = [] ) {

            if ( ! self::can_manage_chart() ) {
                return false;
            }

            $data = self::normalize_chart_data( is_array( $data ) ? $data : [] );

            update_post_meta( absint( parent::post_id() ), self::meta_key(), $data );

            return $data;
        }

        public static function guest_pool() {

            $guests = [];

            if ( ! self::can_manage_chart() ) {
                return $guests;
            }

            $guest_list = get_post_meta( absint( parent::post_id() ), sanitize_key( 'guest_list_data' ), true );

            if ( ! parent::_is_array( $guest_list ) ) {
                return $guests;
            }

            foreach ( $guest_list as $index => $guest ) {

                if ( ! parent::_is_array( (array) $guest ) ) {
                    continue;
                }

                $guest_id = isset( $guest['guest_unique_id'] ) && $guest['guest_unique_id'] !== ''
                    ? sanitize_text_field( (string) $guest['guest_unique_id'] )
                    : sanitize_text_field( (string) $index );

                $first_name = isset( $guest['first_name'] ) ? sanitize_text_field( $guest['first_name'] ) : '';
                $last_name  = isset( $guest['last_name'] ) ? sanitize_text_field( $guest['last_name'] ) : '';

                $label = trim( $first_name . ' ' . $last_name );

                if ( $label === '' ) {
                    $label = sprintf( esc_html__( 'Guest #%1$s', 'sdweddingdirectory' ), absint( $index + 1 ) );
                }

                $guests[] = [
                    'id'    => $guest_id,
                    'label' => $label,
                ];
            }

            return $guests;
        }

        private static function derive_rectangular_sides( $target_seats = 8 ) {

            $target = max( 4, min( 30, absint( $target_seats ) ) );
            $best   = [
                'short_side_seats' => 1,
                'long_side_seats'  => 3,
                'seats'            => 8,
                'diff'             => PHP_INT_MAX,
            ];

            for ( $short = 1; $short <= 15; $short++ ) {
                for ( $long = 1; $long <= 15; $long++ ) {

                    $total = 2 * ( $short + $long );

                    if ( $total > 30 ) {
                        continue;
                    }

                    $diff = abs( $total - $target );

                    if ( $diff < $best['diff'] || ( $diff === $best['diff'] && $long > $best['long_side_seats'] ) ) {
                        $best = [
                            'short_side_seats' => $short,
                            'long_side_seats'  => $long,
                            'seats'            => $total,
                            'diff'             => $diff,
                        ];
                    }
                }
            }

            return [
                'short_side_seats' => absint( $best['short_side_seats'] ),
                'long_side_seats'  => absint( $best['long_side_seats'] ),
                'seats'            => absint( $best['seats'] ),
            ];
        }

        private static function normalize_rectangular_sides( $short_side = 0, $long_side = 0, $target_seats = 8 ) {

            $short = absint( $short_side );
            $long  = absint( $long_side );

            if ( $short < 1 || $long < 1 ) {
                return self::derive_rectangular_sides( $target_seats );
            }

            $short = max( 1, min( 15, $short ) );
            $long  = max( 1, min( 15, $long ) );

            $total = 2 * ( $short + $long );

            while ( $total > 30 ) {
                if ( $long >= $short && $long > 1 ) {
                    $long--;
                } elseif ( $short > 1 ) {
                    $short--;
                } else {
                    break;
                }

                $total = 2 * ( $short + $long );
            }

            if ( $total < 4 ) {
                $short = 1;
                $long  = 1;
                $total = 4;
            }

            return [
                'short_side_seats' => absint( $short ),
                'long_side_seats'  => absint( $long ),
                'seats'            => absint( $total ),
            ];
        }

        private static function normalize_square_side( $side = 0, $target_seats = 8 ) {

            $side_seats = absint( $side );

            if ( $side_seats < 1 ) {
                $target_seats = max( 4, min( 30, absint( $target_seats ) ) );
                $side_seats   = max( 1, (int) round( $target_seats / 4 ) );
            }

            $side_seats = max( 1, min( 7, $side_seats ) );

            while ( ( $side_seats * 4 ) > 30 && $side_seats > 1 ) {
                $side_seats--;
            }

            return absint( $side_seats );
        }

        public static function normalize_chart_data( $data = [] ) {

            $normalized = [
                'tables'     => [],
                'layout'     => [
                    'width_feet'  => 80,
                    'height_feet' => 60,
                    'scale'       => 100,
                ],
                'updated_at' => current_time( 'mysql' ),
            ];

            if ( ! parent::_is_array( (array) $data ) ) {
                return $normalized;
            }

            $tables = isset( $data['tables'] ) && is_array( $data['tables'] ) ? $data['tables'] : [];

            $layout_raw = isset( $data['layout'] ) && is_array( $data['layout'] ) ? $data['layout'] : [];

            $normalized['layout'] = [
                'width_feet'  => max( 10, min( 500, absint( isset( $layout_raw['width_feet'] ) ? $layout_raw['width_feet'] : 80 ) ) ),
                'height_feet' => max( 10, min( 500, absint( isset( $layout_raw['height_feet'] ) ? $layout_raw['height_feet'] : 60 ) ) ),
                'scale'       => max( 1, min( 150, absint( isset( $layout_raw['scale'] ) ? $layout_raw['scale'] : 100 ) ) ),
            ];

            foreach ( $tables as $table ) {

                if ( ! is_array( $table ) ) {
                    continue;
                }

                $table_id = isset( $table['id'] ) && $table['id'] !== ''
                    ? sanitize_key( $table['id'] )
                    : sanitize_key( uniqid( 'table_', true ) );

                $shape = isset( $table['shape'] ) && in_array( $table['shape'], [ 'round', 'rectangular', 'square' ], true )
                    ? sanitize_text_field( $table['shape'] )
                    : 'round';

                $requested_seats = isset( $table['seats'] ) ? absint( $table['seats'] ) : 8;
                $requested_seats = max( 1, min( 30, $requested_seats ) );

                $short_side_seats = isset( $table['short_side_seats'] )
                    ? absint( $table['short_side_seats'] )
                    : ( isset( $table['shortSideSeats'] ) ? absint( $table['shortSideSeats'] ) : 0 );

                $long_side_seats = isset( $table['long_side_seats'] )
                    ? absint( $table['long_side_seats'] )
                    : ( isset( $table['longSideSeats'] ) ? absint( $table['longSideSeats'] ) : 0 );

                $square_side_seats = isset( $table['square_side_seats'] )
                    ? absint( $table['square_side_seats'] )
                    : ( isset( $table['squareSideSeats'] ) ? absint( $table['squareSideSeats'] ) : 0 );

                if ( $shape === 'rectangular' ) {
                    $rect_sides       = self::normalize_rectangular_sides( $short_side_seats, $long_side_seats, $requested_seats );
                    $short_side_seats = $rect_sides['short_side_seats'];
                    $long_side_seats  = $rect_sides['long_side_seats'];
                    $seats            = $rect_sides['seats'];
                    $square_side_seats = self::normalize_square_side( $square_side_seats, $seats );
                } elseif ( $shape === 'square' ) {
                    $square_side_seats = self::normalize_square_side( $square_side_seats, $requested_seats );
                    $short_side_seats  = $square_side_seats;
                    $long_side_seats   = $square_side_seats;
                    $seats             = max( 4, min( 30, absint( $square_side_seats * 4 ) ) );
                } else {
                    $seats = max( 1, min( 30, $requested_seats ) );

                    if ( $short_side_seats < 1 || $long_side_seats < 1 ) {
                        $rect_sides       = self::derive_rectangular_sides( $seats );
                        $short_side_seats = $rect_sides['short_side_seats'];
                        $long_side_seats  = $rect_sides['long_side_seats'];
                    } else {
                        $short_side_seats = max( 1, min( 15, $short_side_seats ) );
                        $long_side_seats  = max( 1, min( 15, $long_side_seats ) );
                    }

                    $square_side_seats = self::normalize_square_side( $square_side_seats, $seats );
                }

                $x = isset( $table['x'] ) ? floatval( $table['x'] ) : 24;
                $y = isset( $table['y'] ) ? floatval( $table['y'] ) : 24;

                $assignments = isset( $table['assignments'] ) && is_array( $table['assignments'] ) ? $table['assignments'] : [];

                $normalized_assignments = [];

                for ( $i = 0; $i < $seats; $i++ ) {
                    $normalized_assignments[] = isset( $assignments[ $i ] ) ? sanitize_text_field( (string) $assignments[ $i ] ) : '';
                }

                $normalized['tables'][] = [
                    'id'          => $table_id,
                    'name'        => isset( $table['name'] ) ? sanitize_text_field( $table['name'] ) : '',
                    'shape'       => $shape,
                    'seats'       => $seats,
                    'short_side_seats' => absint( $short_side_seats ),
                    'long_side_seats'  => absint( $long_side_seats ),
                    'square_side_seats' => absint( $square_side_seats ),
                    'x'           => round( $x, 2 ),
                    'y'           => round( $y, 2 ),
                    'assignments' => $normalized_assignments,
                ];
            }

            if ( isset( $data['updated_at'] ) && ! empty( $data['updated_at'] ) ) {
                $normalized['updated_at'] = sanitize_text_field( $data['updated_at'] );
            }

            return $normalized;
        }
    }

    SDWeddingDirectory_Seating_Chart_Database::get_instance();
}
