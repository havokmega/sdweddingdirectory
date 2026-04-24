<?php
/**
 * SDWeddingDirectory v2 navigation walker.
 */

defined( 'ABSPATH' ) || exit;

class SDSDWeddingDirectoryectory_Navwalker extends Walker_Nav_Menu {

	/**
	 * Menu item currently being rendered.
	 *
	 * @var WP_Post|null
	 */
	private $current_item = null;

	/**
	 * Cached child state keyed by menu item ID.
	 *
	 * @var array<int, bool>
	 */
	private $child_state = [];

	/**
	 * Map legacy icon classes to the v2 icon font.
	 *
	 * @param array $classes Item classes.
	 * @return string
	 */
	private function get_icon_class( $classes ) {
		$icon_map = [
			'sdweddingdirectory-budget'         => 'icon-budget',
			'sdweddingdirectory-camera-alt'     => 'icon-camera-alt',
			'sdweddingdirectory-checklist'      => 'icon-checklist',
			'sdweddingdirectory-church'         => 'icon-church',
			'sdweddingdirectory-fashion'        => 'icon-fashion',
			'sdweddingdirectory-flowers'        => 'icon-flowers',
			'sdweddingdirectory-guestlist'      => 'icon-guestlist',
			'sdweddingdirectory-heart-envelope' => 'icon-heart-envelope',
			'sdweddingdirectory-music'          => 'icon-music',
			'sdweddingdirectory-reviews'        => 'icon-four-side-table-1',
			'sdweddingdirectory-vendor-manager' => 'icon-vendor-manager',
			'sdweddingdirectory-videographer'   => 'icon-videographer',
			'sdweddingdirectory-website'        => 'icon-website',
			'sdweddingdirectory-wine'           => 'icon-wine',
		];

		if ( ! in_array( 'sd-menu-icon', (array) $classes, true ) ) {
			return '';
		}

		foreach ( (array) $classes as $class ) {
			if ( 0 !== strpos( $class, 'sdweddingdirectory-' ) ) {
				continue;
			}

			if ( isset( $icon_map[ $class ] ) ) {
				return $icon_map[ $class ];
			}

			$icon_slug = sanitize_html_class( str_replace( 'sdweddingdirectory-', '', $class ) );
			if ( $icon_slug ) {
				return 'icon-' . $icon_slug;
			}
		}

		return '';
	}

	/**
	 * Get sd-mega modifier classes used by existing CSS.
	 *
	 * @param array $classes Item classes.
	 * @return array
	 */
	private function get_mega_classes( $classes ) {
		$mega_classes = [];

		foreach ( (array) $classes as $class ) {
			if ( 0 !== strpos( $class, 'sd-mega' ) ) {
				continue;
			}

			$mega_classes[] = sanitize_html_class( $class );
		}

		return array_values( array_unique( array_filter( $mega_classes ) ) );
	}

	/**
	 * Build an attribute string.
	 *
	 * @param array $attributes Link attributes.
	 * @return string
	 */
	private function render_attributes( $attributes ) {
		$output = '';

		foreach ( $attributes as $attribute => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}

			$value   = ( 'href' === $attribute ) ? esc_url( $value ) : esc_attr( $value );
			$output .= sprintf( ' %s="%s"', $attribute, $value );
		}

		return $output;
	}

	/**
	 * Determine whether the current item has children.
	 *
	 * @param WP_Post $item Menu item.
	 * @return bool
	 */
	private function item_has_children( $item ) {
		return ! empty( $this->child_state[ $item->ID ] );
	}

	/**
	 * Render the standard link label.
	 *
	 * @param WP_Post $item Menu item.
	 * @param string  $icon_class Icon class.
	 * @param string  $text_class Text class.
	 * @param object  $args Walker args.
	 * @return string
	 */
	private function render_link_content( $item, $icon_class, $text_class, $args ) {
		$content = '';

		if ( $icon_class ) {
			$content .= sprintf(
				'<span class="menu-icon header-nav__icon" aria-hidden="true"><span class="%s"></span></span>',
				esc_attr( $icon_class )
			);
		}

		$content .= sprintf(
			'<span class="%1$s">%2$s</span>',
			esc_attr( $text_class ),
			$args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after
		);

		return $content;
	}

	/**
	 * Render a leaf item, including optional image/description content.
	 *
	 * @param WP_Post $item Menu item.
	 * @param array   $attributes Link attributes.
	 * @param string  $icon_class Icon class.
	 * @param object  $args Walker args.
	 * @return string
	 */
	private function render_leaf_item( $item, $attributes, $icon_class, $args ) {
		$image_url   = '';
		$has_summary = ! empty( $item->description );

		if ( ! empty( $item->attr_title ) && filter_var( $item->attr_title, FILTER_VALIDATE_URL ) ) {
			$image_url = $item->attr_title;
		}

		$is_card = $image_url || $has_summary;
		$classes = $is_card
			? [ 'mega-menu__link', 'mega-menu__link--card', 'header-nav__submenu-link', 'sd-mega-card-link' ]
			: [ 'mega-menu__link', 'header-nav__submenu-link' ];

		$attributes['class'] = implode( ' ', $classes );

		$output  = '<a' . $this->render_attributes( $attributes ) . '>';

		if ( $image_url ) {
			$output .= sprintf(
				'<span class="mega-menu__media sd-mega-card-media"><img loading="lazy" src="%1$s" alt="%2$s"></span>',
				esc_url( $image_url ),
				esc_attr( $item->title )
			);
		}

		if ( $is_card ) {
			$output .= sprintf(
				'<span class="mega-menu__title sd-mega-card-title">%s</span>',
				apply_filters( 'the_title', $item->title, $item->ID )
			);

			if ( $has_summary ) {
				$output .= sprintf(
					'<span class="mega-menu__description sd-mega-card-text">%s</span>',
					esc_html( $item->description )
				);
			}
		} else {
			$output .= $this->render_link_content( $item, $icon_class, 'menu-text header-nav__text', $args );
		}

		$output .= '</a>';

		return $output;
	}

	/**
	 * Open submenu markup.
	 *
	 * @param string $output Output buffer.
	 * @param int    $depth Current depth.
	 * @param array  $args Walker args.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent       = str_repeat( "\t", $depth );
		$item         = $this->current_item;
		$item_classes = $item && is_array( $item->classes ) ? $item->classes : [];
		$submenu_id   = $item ? 'submenu-' . $item->ID : '';

		if ( 0 === $depth && in_array( 'sd-mega', $item_classes, true ) ) {
			$list_classes = [
				'mega-menu',
				'header-nav__panel',
				'sd-mega-menu',
			];

			foreach ( $this->get_mega_classes( $item_classes ) as $class ) {
				if ( 'sd-mega' === $class ) {
					continue;
				}

				$list_classes[] = $class;
			}

			$output .= "\n$indent<ul id=\"" . esc_attr( $submenu_id ) . '" class="' . esc_attr( implode( ' ', array_unique( $list_classes ) ) ) . "\" hidden>\n";
			return;
		}

		$list_classes = [ 'header-nav__submenu' ];

		if ( $item && in_array( 'sd-mega-group', $item_classes, true ) ) {
			$list_classes[] = 'header-nav__group-menu';
			$list_classes[] = 'sd-mega-group-menu';

			foreach ( $this->get_mega_classes( $item_classes ) as $class ) {
				if ( 'sd-mega' === $class || 'sd-mega-group' === $class ) {
					continue;
				}

				$list_classes[] = $class . '-menu';
			}
		}

		$output .= "\n$indent<ul id=\"" . esc_attr( $submenu_id ) . '" class="' . esc_attr( implode( ' ', array_unique( $list_classes ) ) ) . "\">\n";
	}

	/**
	 * Close submenu markup.
	 *
	 * @param string $output Output buffer.
	 * @param int    $depth Current depth.
	 * @param array  $args Walker args.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start a menu item element.
	 *
	 * @param string  $output Output buffer.
	 * @param WP_Post $item Menu item.
	 * @param int     $depth Current depth.
	 * @param array   $args Walker args.
	 * @param int     $id Item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$this->current_item = $item;
		$indent             = $depth ? str_repeat( "\t", $depth ) : '';
		$classes            = empty( $item->classes ) ? [] : (array) $item->classes;
		$has_children       = $this->item_has_children( $item );
		$is_mega_parent     = 0 === $depth && in_array( 'sd-mega', $classes, true ) && $has_children;
		$is_group           = $depth > 0 && in_array( 'sd-mega-group', $classes, true ) && $has_children;
		$is_heading         = $depth > 0 && (
			in_array( 'sd-mega-heading', $classes, true ) ||
			in_array( 'sd-mega-subheading', $classes, true ) ||
			( ! $has_children && empty( $item->url ) )
		);

		$item_classes = [ 'nav__item', 'header-nav__item', 'menu-item-' . $item->ID ];

		if ( $has_children ) {
			$item_classes[] = 'has-children';
		}

		if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true ) ) {
			$item_classes[] = 'is-current';
		}

		if ( $is_mega_parent ) {
			$item_classes[] = 'header-nav__item--mega';
			$item_classes[] = 'sd-mega';
		}

		if ( $is_group ) {
			$item_classes[] = 'header-nav__group';
			$item_classes[] = 'sd-mega-group';
		}

		foreach ( $this->get_mega_classes( $classes ) as $class ) {
			if ( 'sd-mega' === $class && ! $is_mega_parent ) {
				continue;
			}

			if ( 'sd-mega-group' === $class && ! $is_group ) {
				continue;
			}

			$item_classes[] = $class;
		}

		$item_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$output .= $indent . '<li id="' . esc_attr( $item_id ) . '" class="' . esc_attr( implode( ' ', array_unique( $item_classes ) ) ) . '">';

		if ( $is_heading ) {
			$heading_classes = [ 'mega-menu__heading', 'header-nav__heading' ];

			if ( in_array( 'sd-mega-subheading', $classes, true ) ) {
				$heading_classes[] = 'mega-menu__heading--sub';
			}

			$output .= sprintf(
				'<span class="%1$s">%2$s</span>',
				esc_attr( implode( ' ', $heading_classes ) ),
				apply_filters( 'the_title', $item->title, $item->ID )
			);

			return;
		}

		if ( $is_group ) {
			return;
		}

		$attributes = [
			'title'  => ! empty( $item->attr_title ) && ! filter_var( $item->attr_title, FILTER_VALIDATE_URL ) ? $item->attr_title : '',
			'target' => ! empty( $item->target ) ? $item->target : '',
			'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
			'href'   => ! empty( $item->url ) ? $item->url : '',
		];

		$link_classes = [];

		if ( 0 === $depth ) {
			$link_classes[] = 'nav__link';
			$link_classes[] = 'header-nav__link';
		}

		if ( $has_children ) {
			$link_classes[]             = 'has-panel';
			$attributes['aria-haspopup'] = 'true';
			$attributes['aria-expanded'] = 'false';
			$attributes['aria-controls'] = 'submenu-' . $item->ID;
		}

		$icon_class = $this->get_icon_class( $classes );

		if ( $depth > 0 && ! $has_children ) {
			$attributes = apply_filters( 'nav_menu_link_attributes', $attributes, $item, $args );
			$output .= $this->render_leaf_item( $item, $attributes, $icon_class, $args );
			return;
		}

		if ( $depth > 0 ) {
			$link_classes[] = 'mega-menu__link';
			$link_classes[] = 'header-nav__submenu-link';
		}

		$attributes['class'] = implode( ' ', array_filter( $link_classes ) );
		$attributes          = apply_filters( 'nav_menu_link_attributes', $attributes, $item, $args );

		$item_output  = $args->before;
		$item_output .= '<a' . $this->render_attributes( $attributes ) . '>';
		$item_output .= $this->render_link_content( $item, $icon_class, 'menu-text header-nav__text', $args );
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Close a menu item element.
	 *
	 * @param string  $output Output buffer.
	 * @param WP_Post $item Current item.
	 * @param int     $depth Current depth.
	 * @param array   $args Walker args.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		$output .= "</li>\n";
	}

	/**
	 * Set explicit child state instead of relying on $args->has_children.
	 *
	 * @param object $element Current element.
	 * @param array  $children_elements Child elements.
	 * @param int    $max_depth Max depth.
	 * @param int    $depth Current depth.
	 * @param array  $args Walker args.
	 * @param string $output Output buffer.
	 * @return void
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];
		$item_id  = $element->$id_field;

		$this->child_state[ $item_id ] = ! empty( $children_elements[ $item_id ] );
		$element->sdwd_has_children = $this->child_state[ $item_id ];

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Fallback menu.
	 *
	 * @param array $args Walker args.
	 * @return void
	 */
	public static function fallback( $args ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$container       = ! empty( $args['container'] ) ? $args['container'] : false;
		$container_id    = ! empty( $args['container_id'] ) ? $args['container_id'] : '';
		$container_class = ! empty( $args['container_class'] ) ? $args['container_class'] : '';
		$menu_id         = ! empty( $args['menu_id'] ) ? $args['menu_id'] : '';
		$menu_class      = ! empty( $args['menu_class'] ) ? $args['menu_class'] : '';

		$output = '';

		if ( $container ) {
			$output .= '<' . tag_escape( $container );

			if ( $container_id ) {
				$output .= ' id="' . esc_attr( $container_id ) . '"';
			}

			if ( $container_class ) {
				$output .= ' class="' . esc_attr( $container_class ) . '"';
			}

			$output .= '>';
		}

		$output .= '<ul';

		if ( $menu_id ) {
			$output .= ' id="' . esc_attr( $menu_id ) . '"';
		}

		if ( $menu_class ) {
			$output .= ' class="' . esc_attr( $menu_class ) . '"';
		}

		$output .= '>';
		$output .= '<li class="nav__item header-nav__item"><a class="nav__link header-nav__link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">';
		$output .= esc_html__( 'Add a menu', 'sandiegoweddingdirectory' );
		$output .= '</a></li>';
		$output .= '</ul>';

		if ( $container ) {
			$output .= '</' . tag_escape( $container ) . '>';
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
