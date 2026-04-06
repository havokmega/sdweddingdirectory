<?php
/**
 * SDWeddingDirectory - Structured Data
 * ------------------------------------
 */
if ( ! class_exists( 'SDWeddingDirectory_Structured_Data' ) && class_exists( 'SDWeddingDirectory' ) ) {

	class SDWeddingDirectory_Structured_Data extends SDWeddingDirectory {

		private static $instance;

		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {

			add_action( 'wp_head', [ $this, 'output_schema' ], 40 );
		}

		public static function output_schema() {

			if ( is_admin() || wp_doing_ajax() || is_feed() || is_404() ) {

				return;
			}

			$schemas = [];

			if ( is_front_page() || is_home() ) {

				$schemas[] = self::organization_schema();
			}

			$breadcrumb = self::breadcrumb_schema();

			if ( ! empty( $breadcrumb ) ) {

				$schemas[] = $breadcrumb;
			}

			if ( is_singular( 'venue' ) || is_singular( 'vendor' ) ) {

				$local_business = self::local_business_schema( absint( get_queried_object_id() ) );

				if ( ! empty( $local_business ) ) {

					$schemas[] = $local_business;
				}
			}

			if ( is_singular( 'post' ) ) {

				$article = self::article_schema( absint( get_queried_object_id() ) );

				if ( ! empty( $article ) ) {

					$schemas[] = $article;
				}
			}

			foreach ( $schemas as $schema ) {

				printf(
					'<script type="application/ld+json">%s</script>' . "\n",
					wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
				);
			}
		}

		public static function organization_schema() {

		return [
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'name'     => 'SD Wedding Directory',
			'url'      => home_url( '/' ),
			'logo'     => sdweddingdirectory_logo_uri(),
		];
		}

		public static function breadcrumb_schema() {

			$crumbs = [
				[
					'name' => esc_html__( 'Home', 'sdweddingdirectory' ),
					'url'  => home_url( '/' ),
				],
			];

			if ( is_front_page() ) {

				return self::format_breadcrumb_schema( $crumbs );
			}

			if ( is_home() ) {

				$blog_page_id = absint( get_option( 'page_for_posts' ) );

				if ( $blog_page_id ) {

					$crumbs[] = [
						'name' => get_the_title( $blog_page_id ),
						'url'  => get_permalink( $blog_page_id ),
					];
				}

				return self::format_breadcrumb_schema( $crumbs );
			}

			if ( is_page() ) {

				$page_id   = absint( get_queried_object_id() );
				$ancestors = array_reverse( get_post_ancestors( $page_id ) );

				foreach ( $ancestors as $ancestor_id ) {

					$crumbs[] = [
						'name' => get_the_title( $ancestor_id ),
						'url'  => get_permalink( $ancestor_id ),
					];
				}

				$crumbs[] = [
					'name' => get_the_title( $page_id ),
					'url'  => get_permalink( $page_id ),
				];

				return self::format_breadcrumb_schema( $crumbs );
			}

			if ( is_singular() ) {

				$post_id   = absint( get_queried_object_id() );
				$post_type = get_post_type( $post_id );

				if ( $post_type === 'venue' ) {

					$crumbs[] = [
						'name' => esc_html__( 'Wedding Venues', 'sdweddingdirectory' ),
						'url'  => home_url( '/venues/' ),
					];

				} elseif ( $post_type === 'vendor' ) {

					$crumbs[] = [
						'name' => esc_html__( 'Wedding Vendors', 'sdweddingdirectory' ),
						'url'  => home_url( '/vendors/' ),
					];

				} elseif ( $post_type === 'post' ) {

					$blog_page_id = absint( get_option( 'page_for_posts' ) );

					if ( $blog_page_id ) {

						$crumbs[] = [
							'name' => get_the_title( $blog_page_id ),
							'url'  => get_permalink( $blog_page_id ),
						];
					}

				} else {

					$post_type_object = get_post_type_object( $post_type );
					$archive_link     = get_post_type_archive_link( $post_type );

					if ( $post_type_object && ! empty( $archive_link ) ) {

						$crumbs[] = [
							'name' => $post_type_object->labels->name,
							'url'  => $archive_link,
						];
					}
				}

				$crumbs[] = [
					'name' => get_the_title( $post_id ),
					'url'  => get_permalink( $post_id ),
				];

				return self::format_breadcrumb_schema( $crumbs );
			}

			if ( is_tax() || is_category() || is_tag() ) {

				$term = get_queried_object();

				if ( ! $term || is_wp_error( $term ) ) {

					return [];
				}

				$taxonomy = isset( $term->taxonomy ) ? $term->taxonomy : '';

				if ( in_array( $taxonomy, [ 'venue-type', 'venue-location', 'listing-location' ], true ) ) {

					$crumbs[] = [
						'name' => esc_html__( 'Wedding Venues', 'sdweddingdirectory' ),
						'url'  => home_url( '/venues/' ),
					];

				} elseif ( in_array( $taxonomy, [ 'vendor-category', 'vendor-location' ], true ) ) {

					$crumbs[] = [
						'name' => esc_html__( 'Wedding Vendors', 'sdweddingdirectory' ),
						'url'  => home_url( '/vendors/' ),
					];

				} elseif ( in_array( $taxonomy, [ 'category', 'post_tag' ], true ) ) {

					$blog_page_id = absint( get_option( 'page_for_posts' ) );

					if ( $blog_page_id ) {

						$crumbs[] = [
							'name' => get_the_title( $blog_page_id ),
							'url'  => get_permalink( $blog_page_id ),
						];
					}
				}

				$term_link = get_term_link( $term );

				if ( ! is_wp_error( $term_link ) ) {

					$crumbs[] = [
						'name' => single_term_title( '', false ),
						'url'  => $term_link,
					];
				}

				return self::format_breadcrumb_schema( $crumbs );
			}

			if ( is_post_type_archive() ) {

				$post_type = get_query_var( 'post_type' );

				if ( is_array( $post_type ) ) {

					$post_type = reset( $post_type );
				}

				if ( empty( $post_type ) ) {

					$post_type = get_post_type();
				}

				$title = post_type_archive_title( '', false );
				$link  = get_post_type_archive_link( $post_type );

				if ( $title && $link ) {

					$crumbs[] = [
						'name' => $title,
						'url'  => $link,
					];
				}

				return self::format_breadcrumb_schema( $crumbs );
			}

			if ( is_search() ) {

				$crumbs[] = [
					'name' => sprintf(
						/* translators: %s: search query text */
						esc_html__( 'Search results for "%s"', 'sdweddingdirectory' ),
						get_search_query()
					),
					'url'  => home_url( add_query_arg( null, null ) ),
				];

				return self::format_breadcrumb_schema( $crumbs );
			}

			return self::format_breadcrumb_schema( $crumbs );
		}

		public static function format_breadcrumb_schema( $crumbs = [] ) {

			if ( ! self::_is_array( $crumbs ) ) {

				return [];
			}

			$item_list = [];

			foreach ( $crumbs as $index => $crumb ) {

				if ( empty( $crumb['name'] ) || empty( $crumb['url'] ) ) {

					continue;
				}

				$item_list[] = [
					'@type'    => 'ListItem',
					'position' => absint( $index + 1 ),
					'name'     => wp_strip_all_tags( $crumb['name'] ),
					'item'     => esc_url( $crumb['url'] ),
				];
			}

			if ( ! self::_is_array( $item_list ) ) {

				return [];
			}

			return [
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $item_list,
			];
		}

		public static function local_business_schema( $post_id = 0 ) {

			$post_id = absint( $post_id );

			if ( empty( $post_id ) ) {

				return [];
			}

			$schema = [
				'@context' => 'https://schema.org',
				'@type'    => 'LocalBusiness',
				'name'     => get_the_title( $post_id ),
				'url'      => get_permalink( $post_id ),
				'address'  => [
					'@type'           => 'PostalAddress',
					'addressLocality' => self::location_name( $post_id ),
					'addressRegion'   => 'CA',
				],
			];

			$phone = self::business_phone( $post_id );

			if ( ! empty( $phone ) ) {

				$schema['telephone'] = $phone;
			}

			$aggregate_rating = self::aggregate_rating( $post_id );

			if ( self::_is_array( $aggregate_rating ) ) {

				$schema['aggregateRating'] = $aggregate_rating;
			}

			return $schema;
		}

		public static function location_name( $post_id = 0 ) {

			$post_id = absint( $post_id );

			$post_type = get_post_type( $post_id );

			$taxonomies = ( $post_type === 'vendor' )
				? [ 'vendor-location', 'listing-location', 'venue-location' ]
				: [ 'venue-location', 'listing-location' ];

			foreach ( $taxonomies as $taxonomy ) {

				if ( taxonomy_exists( $taxonomy ) ) {

					$terms = wp_get_post_terms( $post_id, $taxonomy );

					if ( self::_is_array( $terms ) ) {

						return $terms[0]->name;
					}
				}
			}

			$meta_keys = [ 'city_name', 'company_city', 'city', 'location', 'company_location' ];

			foreach ( $meta_keys as $meta_key ) {

				$value = get_post_meta( $post_id, sanitize_key( $meta_key ), true );

				if ( ! empty( $value ) ) {

					return wp_strip_all_tags( $value );
				}
			}

			return 'San Diego';
		}

		public static function business_phone( $post_id = 0 ) {

			$post_id = absint( $post_id );

			$meta_keys = [ 'company_contact', 'phone', 'contact_no', 'contact_number' ];

			foreach ( $meta_keys as $meta_key ) {

				$value = get_post_meta( $post_id, sanitize_key( $meta_key ), true );

				if ( ! empty( $value ) ) {

					return preg_replace( '/[^\d\+]/', '', $value );
				}
			}

			$vendor_id = absint( get_post_meta( $post_id, sanitize_key( 'vendor_id' ), true ) );

			if ( ! empty( $vendor_id ) ) {

				$value = get_post_meta( $vendor_id, sanitize_key( 'company_contact' ), true );

				if ( ! empty( $value ) ) {

					return preg_replace( '/[^\d\+]/', '', $value );
				}
			}

			return '';
		}

		public static function aggregate_rating( $post_id = 0 ) {

			$post_id = absint( $post_id );

			if ( empty( $post_id ) ) {

				return [];
			}

			$post_type = get_post_type( $post_id );
			$args      = ( $post_type === 'vendor' ) ? [ 'vendor_id' => $post_id ] : [ 'venue_id' => $post_id ];

			$review_count = absint( apply_filters( 'sdweddingdirectory/rating/found', '', $args ) );
			$avg_raw      = apply_filters( 'sdweddingdirectory/rating/average', '', $args );
			$rating_value = self::extract_numeric_value( $avg_raw );

			if ( $review_count < 1 || $rating_value <= 0 ) {

				return [];
			}

			return [
				'@type'       => 'AggregateRating',
				'ratingValue' => number_format( (float) $rating_value, 1, '.', '' ),
				'reviewCount' => absint( $review_count ),
			];
		}

		public static function extract_numeric_value( $value = '' ) {

			if ( is_numeric( $value ) ) {

				return (float) $value;
			}

			$value = wp_strip_all_tags( (string) $value );
			$value = trim( str_replace( ',', '.', $value ) );

			if ( preg_match( '/([0-9]+(?:\.[0-9]+)?)/', $value, $matches ) ) {

				return (float) $matches[1];
			}

			return 0;
		}

		public static function article_schema( $post_id = 0 ) {

			$post_id = absint( $post_id );

			if ( empty( $post_id ) ) {

				return [];
			}

			$author_id    = absint( get_post_field( 'post_author', $post_id ) );
			$author_name  = get_the_author_meta( 'display_name', $author_id );
			$thumbnail    = get_the_post_thumbnail_url( $post_id, 'full' );
			$schema       = [
				'@context'          => 'https://schema.org',
				'@type'             => 'Article',
				'headline'          => wp_strip_all_tags( get_the_title( $post_id ) ),
				'datePublished'     => get_post_time( 'c', true, $post_id ),
				'dateModified'      => get_post_modified_time( 'c', true, $post_id ),
				'mainEntityOfPage'  => get_permalink( $post_id ),
				'author'            => [
					'@type' => 'Person',
					'name'  => $author_name ? $author_name : get_bloginfo( 'name' ),
				],
				'publisher'         => [
					'@type' => 'Organization',
					'name'  => 'SD Wedding Directory',
					'logo'  => [
						'@type' => 'ImageObject',
						'url'   => get_theme_file_uri( 'assets/images/logo/logo_dark.svg' ),
					],
				],
			];

			if ( ! empty( $thumbnail ) ) {

				$schema['image'] = [ $thumbnail ];
			}

			return $schema;
		}
	}

	SDWeddingDirectory_Structured_Data::get_instance();
}
