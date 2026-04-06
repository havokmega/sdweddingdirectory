<?php
/**
 * Modified version of wp_bootstrap_navwalker for Multiple dropdown
 * GitHub URI: https://github.com/circlewaves/bootstrap-multilevel-dropdown-menu
 * Author: Max Kostinevich - http://circlewaves.com
 * Version 1.0.0
 * Changelog:
 *	- Changes across the line 126 (see $item_output); 
 *
 * ORIGINAL SOURCE:
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined('ABSPATH') or die("No script kiddies please!");
 
 class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

	private $current_item = null;

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $depth );
		$classes = [ 'dropdown-menu' ];
		$item_classes = [];

		if ( $this->current_item && is_array( $this->current_item->classes ) ) {
			$item_classes = $this->current_item->classes;
		}

		if ( $depth === 0 && in_array( 'sd-mega', $item_classes, true ) ) {
			$classes[] = 'megamenu';
			$classes[] = 'sd-mega-menu';
			if ( in_array( 'sd-mega-planning', $item_classes, true ) ) {
				$classes[] = 'sd-mega-planning';
			}
			if ( in_array( 'sd-mega-venues', $item_classes, true ) ) {
				$classes[] = 'sd-mega-venues';
			}
			if ( in_array( 'sd-mega-vendors', $item_classes, true ) ) {
				$classes[] = 'sd-mega-vendors';
			}
			if ( in_array( 'sd-mega-inspiration', $item_classes, true ) ) {
				$classes[] = 'sd-mega-inspiration';
			}
		}

		if ( $depth >= 1 && in_array( 'sd-mega-group', $item_classes, true ) ) {
			$classes[] = 'sd-mega-group-menu';
			if ( in_array( 'sd-mega-icons', $item_classes, true ) ) {
				$classes[] = 'sd-mega-icons-menu';
			}
			if ( in_array( 'sd-mega-more', $item_classes, true ) ) {
				$classes[] = 'sd-mega-more-menu';
			}
			if ( in_array( 'sd-mega-links', $item_classes, true ) ) {
				$classes[] = 'sd-mega-links-menu';
			}
			if ( in_array( 'sd-mega-cards', $item_classes, true ) ) {
				$classes[] = 'sd-mega-cards-menu';
			}
		}

		$output .= "\n$indent<ul role=\"menu\" class=\"" . esc_attr( implode( ' ', array_filter( $classes ) ) ) . "\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$this->current_item = $item;

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
			$header_classes = empty( $item->classes ) ? [] : (array) $item->classes;
			$header_classes = array_map( 'sanitize_html_class', array_filter( $header_classes ) );
			$header_class_names = $header_classes ? ' ' . esc_attr( implode( ' ', $header_classes ) ) : '';
			$output .= $indent . '<li role="presentation" class="dropdown-header' . $header_class_names . '">' . esc_html( $item->title );
		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? [] : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if ( $args->has_children )
				$class_names .= ' dropdown';

			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = [];
			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			$has_icon = in_array( 'sd-menu-icon', $classes, true );
			$icon_class = '';
			if ( $has_icon ) {
				foreach ( $classes as $class ) {
					if ( strpos( $class, 'sdweddingdirectory-' ) === 0 ) {
						$icon_class = $class;
						break;
					}
				}
			}

			$is_card = in_array( 'sd-mega-card', $classes, true );

			// If item has_children add atts to a.
			if ( $args->has_children && $depth === 0 ) {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				$atts['class'] = 'nav-link';
				$atts['aria-haspopup'] = 'true';
			} else {
				if( $depth === 0 ){
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
					$atts['class'] = 'nav-link';
				}else{
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
					$atts['class'] = $is_card ? 'sd-mega-card-link' : 'dropdown-item';
				}
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';

			if ( $is_card ) {
				$card_image = '';
				if ( ! empty( $item->attr_title ) && filter_var( $item->attr_title, FILTER_VALIDATE_URL ) ) {
					$card_image = $item->attr_title;
				}
				if ( $card_image ) {
					$item_output .= '<span class="sd-mega-card-media"><img loading="lazy" src="' . esc_url( $card_image ) . '" alt="' . esc_attr( $item->title ) . '" /></span>';
				}
				$item_output .= '<span class="sd-mega-card-title">' . esc_html( $item->title ) . '</span>';
				if ( ! empty( $item->description ) ) {
					$item_output .= '<span class="sd-mega-card-text">' . esc_html( $item->description ) . '</span>';
				}
			} else {
				if ( $icon_class ) {
					$item_output .= '<span class="menu-icon"><i class="' . esc_attr( $icon_class ) . '"></i></span>';
				}
				$item_output .= '<span class="menu-text">' . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after . '</span>';
			}

			$item_output .= '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			printf( '%s', $fb_output );
		}
	}
}
