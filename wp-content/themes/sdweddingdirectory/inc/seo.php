<?php
/**
 * SDWeddingDirectory - SEO Helpers
 * --------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_SEO' ) && class_exists( 'SDWeddingDirectory' ) ) {

    class SDWeddingDirectory_SEO extends SDWeddingDirectory {

        private static $instance;

        public static function get_instance() {

            if ( ! isset( self::$instance ) ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct() {

            add_action( 'wp_head', [ $this, 'robots_meta' ], 1 );

            add_action( 'wp_head', [ $this, 'canonical_tag' ], 2 );

            add_action( 'wp_head', [ $this, 'meta_description' ], 3 );

            add_filter( 'document_title_parts', [ $this, 'document_title_parts' ], 20, 1 );

            add_action( 'template_redirect', [ $this, 'enforce_trailing_slash' ], 20 );

            /**
             * Enable on production with valid SSL.
             *
             * add_action( 'template_redirect', [ $this, 'enforce_https' ], 1 );
             */

            add_filter( 'wp_sitemaps_add_provider', [ $this, 'remove_users_sitemap' ], 10, 2 );

            add_filter( 'wp_sitemaps_post_types', [ $this, 'filter_sitemap_post_types' ], 10, 1 );

            add_filter( 'robots_txt', [ $this, 'robots_txt' ], 10, 2 );
        }

        public static function is_filter_request() {

            $keys = [
                'filter',
                's',
                'cat_id',
                'state_id',
                'region_id',
                'city_id',
                'location',
                'sub-category',
                'price-filter',
                'capacity',
                'venue_setting',
                'venue_amenities',
                'vendor_pricing',
                'vendor_services',
                'vendor_style',
                'vendor_specialties',
                'sort-by',
                'availability',
                'geoloc',
                'pincode',
                'latitude',
                'longitude',
            ];

            foreach ( $keys as $key ) {

                if ( isset( $_GET[ $key ] ) && wp_unslash( $_GET[ $key ] ) !== '' ) {

                    return true;
                }
            }

            if ( ! empty( $_GET ) && is_array( $_GET ) ) {

                $ignore = [
                    'paged',
                    'utm_source',
                    'utm_medium',
                    'utm_campaign',
                    'utm_term',
                    'utm_content',
                    'gclid',
                    'fbclid',
                ];

                foreach ( array_keys( $_GET ) as $query_key ) {

                    if ( ! in_array( $query_key, $ignore, true ) ) {

                        return true;
                    }
                }
            }

            return false;
        }

        public static function is_dashboard_context() {

            return isset( $_GET['dashboard'] ) ||
                   is_page_template( 'user-template/couple-dashboard.php' ) ||
                   is_page_template( 'user-template/vendor-dashboard.php' ) ||
                   is_page( 'couple-dashboard' ) ||
                   is_page( 'vendor-dashboard' );
        }

        public static function robots_meta() {

            if ( self:: is_dashboard_context() ) {

                echo '<meta name="robots" content="noindex, nofollow" />' . "\n";

                return;
            }

            if ( is_search() || self:: is_filter_request() || isset( $_GET['s'] ) ) {

                echo '<meta name="robots" content="noindex, follow" />' . "\n";

                return;
            }

            if ( is_author() || is_tag() ) {

                echo '<meta name="robots" content="noindex, follow" />' . "\n";

                return;
            }
        }

        public static function canonical_tag() {

            if ( is_404() ) {

                return;
            }

            if ( is_singular() ) {

                /**
                 * Avoid duplicate canonical on singular pages if core already prints it.
                 */
                if ( has_action( 'wp_head', 'rel_canonical' ) ) {

                    return;
                }

                printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( get_permalink() ) );

                return;
            }

            if ( is_tax() || is_category() || is_tag() ) {

                $term_link = get_term_link( get_queried_object() );

                if ( ! is_wp_error( $term_link ) ) {

                    printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $term_link ) );
                }

                return;
            }

            if ( is_post_type_archive() ) {

                $post_type = get_query_var( 'post_type' );

                if ( is_array( $post_type ) ) {

                    $post_type = reset( $post_type );
                }

                if ( ! empty( $post_type ) ) {

                    $archive_link = get_post_type_archive_link( $post_type );

                    if ( $archive_link ) {

                        printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $archive_link ) );
                    }
                }

                return;
            }

            if ( is_front_page() || is_home() ) {

                printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( home_url( '/' ) ) );
            }
        }

        public static function document_title_parts( $title_parts = [] ) {

            if ( is_singular( 'venue' ) ) {

                $title_parts['title'] = get_the_title() . ' - Wedding Venue in San Diego';

            } elseif ( is_singular( 'vendor' ) ) {

                $cat      = wp_get_post_terms( get_the_ID(), 'vendor-category', [ 'fields' => 'names' ] );
                $cat_name = ! empty( $cat ) ? $cat[0] : 'Wedding Vendor';

                $title_parts['title'] = get_the_title() . ' - ' . $cat_name . ' in San Diego';

            } elseif ( is_post_type_archive( 'venue' ) || is_page( 'venues' ) ) {

                $title_parts['title'] = 'Wedding Venues in San Diego';

            } elseif ( is_post_type_archive( 'vendor' ) || is_page( 'vendors' ) ) {

                $title_parts['title'] = 'Wedding Vendors in San Diego';

            } elseif ( is_tax( 'venue-type' ) ) {

                $title_parts['title'] = single_term_title( '', false ) . ' Wedding Venues in San Diego';

            } elseif ( is_tax( 'vendor-category' ) ) {

                $title_parts['title'] = single_term_title( '', false ) . ' - San Diego Wedding Vendors';

            } elseif ( is_tax( 'venue-location' ) ) {

                $title_parts['title'] = single_term_title( '', false ) . ' Wedding Venues';
            }

            return $title_parts;
        }

        public static function meta_description() {

            $description = '';

            if ( is_singular() ) {

                if ( has_excerpt() ) {

                    $description = get_the_excerpt();

                } else {

                    $description = wp_strip_all_tags(
                        wp_trim_words(
                            strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ),
                            25
                        )
                    );
                }
            } elseif ( is_tax() || is_category() || is_tag() ) {

                $description = wp_strip_all_tags( term_description() );
                $description = wp_trim_words( $description, 30 );
            }

            if ( empty( $description ) && is_page( 'venues' ) ) {

                $description = 'Browse wedding venues in San Diego County by style, location, and amenities.';
            }

            if ( empty( $description ) && is_page( 'vendors' ) ) {

                $description = 'Find trusted San Diego wedding vendors, compare options, and contact your favorites.';
            }

            if ( ! empty( $description ) ) {

                printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
            }
        }

        public static function enforce_trailing_slash() {

            if ( is_admin() || wp_doing_ajax() || is_feed() || wp_is_json_request() ) {

                return;
            }

            $method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';

            if ( ! in_array( $method, [ 'GET', 'HEAD' ], true ) ) {

                return;
            }

            $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';

            if ( $request_uri === '' ) {

                return;
            }

            $parts = wp_parse_url( $request_uri );
            $path  = isset( $parts['path'] ) ? $parts['path'] : '';

            if ( $path === '' || $path === '/' ) {

                return;
            }

            if ( substr( $path, -1 ) === '/' ) {

                return;
            }

            if ( preg_match( '/\.[a-zA-Z0-9]+$/', $path ) ) {

                return;
            }

            if ( strpos( $path, '/wp-json' ) === 0 ) {

                return;
            }

            $redirect_path = trailingslashit( $path );
            $target        = home_url( $redirect_path );

            if ( isset( $parts['query'] ) && $parts['query'] !== '' ) {

                $target .= '?' . $parts['query'];
            }

            $current = home_url( $request_uri );

            if ( untrailingslashit( $current ) === untrailingslashit( $target ) ) {

                return;
            }

            wp_safe_redirect( $target, 301 );
            exit;
        }

        public static function enforce_https() {

            if ( ! is_ssl() && ! is_admin() ) {

                wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
                exit;
            }
        }

        public static function remove_users_sitemap( $provider, $name ) {

            if ( $name === 'users' ) {

                return false;
            }

            return $provider;
        }

        public static function filter_sitemap_post_types( $post_types = [] ) {

            $excluded = [ 'couple', 'claim-venue', 'venue-review', 'venue-request' ];

            foreach ( $excluded as $post_type ) {

                if ( isset( $post_types[ $post_type ] ) ) {

                    unset( $post_types[ $post_type ] );
                }
            }

            return $post_types;
        }

        public static function robots_txt( $output, $public ) {

            $output  = "User-agent: *\n";
            $output .= "Disallow: /wp-admin/\n";
            $output .= "Disallow: /vendor-dashboard/\n";
            $output .= "Disallow: /couple-dashboard/\n";
            $output .= "Disallow: /*?dashboard=\n";
            $output .= "Disallow: /*?s=\n";
            $output .= "Disallow: /*?filter=\n";
            $output .= "Allow: /wp-admin/admin-ajax.php\n\n";
            $output .= 'Sitemap: ' . home_url( '/wp-sitemap.xml' ) . "\n";

            return $output;
        }
    }

    SDWeddingDirectory_SEO::get_instance();
}
