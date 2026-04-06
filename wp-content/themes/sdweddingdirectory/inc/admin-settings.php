<?php
/**
 * SDWeddingDirectory - Native Admin Settings Pages
 * Replaces OptionTree with WordPress Settings API.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Render a settings page with tabbed navigation.
 */
function sdwd_render_tabbed_settings_page( $page_title, $page_slug, $tabs ) {
	$default_tab = array_key_first( $tabs );
	$active_tab  = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab;
	$active_tab  = isset( $tabs[ $active_tab ] ) ? $active_tab : $default_tab;
	?>
	<div class="wrap">
		<h1><?php echo esc_html( $page_title ); ?></h1>
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $tab_slug => $tab_label ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $tab_slug ) ); ?>"
					class="nav-tab <?php echo $active_tab === $tab_slug ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $tab_label ); ?>
				</a>
			<?php endforeach; ?>
		</h2>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'sdwd_' . $page_slug . '_' . $active_tab );
			do_settings_sections( 'sdwd_' . $page_slug . '_' . $active_tab );
			submit_button( __( 'Save Changes', 'sdweddingdirectory' ), 'sd-btn sd-btn-primary' );
			?>
		</form>
	</div>
	<?php
}

/**
 * Parse JSON-list field values that may arrive as JSON strings.
 */
function sdwd_parse_json_list_value( $value ) {
	if ( is_string( $value ) ) {
		$decoded = json_decode( wp_unslash( $value ), true );
		if ( JSON_ERROR_NONE === json_last_error() && is_array( $decoded ) ) {
			return $decoded;
		}
		return [];
	}

	return is_array( $value ) ? $value : [];
}

/**
 * Recursive fallback sanitizer for array/list settings.
 */
function sdwd_sanitize_array_recursive( $value ) {
	if ( is_array( $value ) ) {
		$clean = [];
		foreach ( $value as $key => $item ) {
			$clean_key          = is_int( $key ) ? $key : sanitize_key( (string) $key );
			$clean[ $clean_key ] = sdwd_sanitize_array_recursive( $item );
		}
		return $clean;
	}

	if ( is_bool( $value ) || is_int( $value ) || is_float( $value ) || null === $value ) {
		return $value;
	}

	return sanitize_text_field( (string) $value );
}

/**
 * Toggle sanitizer compatible with legacy on/off values.
 */
function sdwd_sanitize_on_off( $value ) {
	$value = is_string( $value ) ? strtolower( $value ) : $value;
	return ( 'on' === $value || '1' === $value || 1 === $value || true === $value ) ? 'on' : 'off';
}

/**
 * Color sanitizer that supports both hex and rgba values.
 */
function sdwd_sanitize_color_value( $value ) {
	if ( ! is_string( $value ) ) {
		return '';
	}

	$value = trim( $value );
	if ( '' === $value ) {
		return '';
	}

	if ( 0 === strpos( $value, 'rgba(' ) || 0 === strpos( $value, 'rgb(' ) ) {
		return preg_replace( '/[^0-9rgba(),.\s]/i', '', $value );
	}

	$hex = sanitize_hex_color( $value );
	return $hex ? $hex : sanitize_text_field( $value );
}

/**
 * Link color group sanitizer.
 */
function sdwd_sanitize_link_color( $value ) {
	$value = is_array( $value ) ? $value : [];
	$keys  = [ 'link', 'hover', 'active', 'visited', 'focus', 'bg', 'text', 'border' ];
	$clean = [];

	foreach ( $keys as $key ) {
		$clean[ $key ] = isset( $value[ $key ] ) ? sdwd_sanitize_color_value( $value[ $key ] ) : '';
	}

	return $clean;
}

/**
 * Theme palette sanitizer (css variable => color).
 */
function sdwd_sanitize_theme_palette( $value ) {
	$value = is_array( $value ) ? $value : [];
	$clean = [];

	foreach ( $value as $key => $color ) {
		$css_var = is_string( $key ) ? trim( $key ) : '';
		if ( '' === $css_var ) {
			continue;
		}
		$clean[ $css_var ] = sdwd_sanitize_color_value( $color );
	}

	return $clean;
}

/**
 * Admin email list sanitizer.
 */
function sdwd_sanitize_admin_emails( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		$entry['email_id'] = isset( $item['email_id'] ) ? sanitize_email( $item['email_id'] ) : '';
		$clean[]           = $entry;
	}

	return $clean;
}

/**
 * Email footer social list sanitizer.
 */
function sdwd_sanitize_email_social_media( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		if ( isset( $item['link'] ) ) {
			$entry['link'] = esc_url_raw( $item['link'] );
		}
		if ( isset( $item['image'] ) ) {
			$entry['image'] = esc_url_raw( $item['image'] );
		}

		$clean[] = $entry;
	}

	return $clean;
}

/**
 * Checklist list sanitizer.
 */
function sdwd_sanitize_checklist_defaults( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		if ( isset( $item['todo_title'] ) ) {
			$entry['todo_title'] = sanitize_text_field( $item['todo_title'] );
		}
		if ( isset( $item['todo_overview'] ) ) {
			$entry['todo_overview'] = sanitize_text_field( $item['todo_overview'] );
		}
		if ( isset( $item['todo_period'] ) ) {
			$entry['todo_period'] = sanitize_text_field( $item['todo_period'] );
		}

		$clean[] = $entry;
	}

	return $clean;
}

/**
 * Guest list section sanitizer.
 */
function sdwd_sanitize_guest_list_group( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		if ( isset( $item['group_name'] ) ) {
			$entry['group_name'] = sanitize_text_field( $item['group_name'] );
		}

		$clean[] = $entry;
	}

	return $clean;
}

/**
 * Guest menu list section sanitizer.
 */
function sdwd_sanitize_guest_menu_group( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		if ( isset( $item['menu_list'] ) ) {
			$entry['menu_list'] = sanitize_text_field( $item['menu_list'] );
		}

		$clean[] = $entry;
	}

	return $clean;
}

/**
 * Guest event list section sanitizer.
 */
function sdwd_sanitize_guest_event_group( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		if ( isset( $item['event_list'] ) ) {
			$entry['event_list'] = sanitize_text_field( $item['event_list'] );
		}
		if ( isset( $item['event_icon'] ) ) {
			$entry['event_icon'] = sanitize_text_field( $item['event_icon'] );
		}
		if ( isset( $item['have_meal'] ) ) {
			$entry['have_meal'] = sdwd_sanitize_on_off( $item['have_meal'] );
		}

		$clean[] = $entry;
	}

	return $clean;
}

/**
 * Budget category defaults sanitizer.
 */
function sdwd_sanitize_budget_categories( $value ) {
	$items = sdwd_parse_json_list_value( $value );
	$clean = [];

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$entry = [];
		if ( isset( $item['title'] ) ) {
			$entry['title'] = sanitize_text_field( $item['title'] );
		}
		if ( isset( $item['icon'] ) ) {
			$entry['icon'] = sanitize_text_field( $item['icon'] );
		}
		if ( isset( $item['json'] ) ) {
			if ( is_array( $item['json'] ) ) {
				$entry['json'] = wp_json_encode( sdwd_sanitize_array_recursive( $item['json'] ) );
			} else {
				$entry['json'] = sanitize_text_field( (string) $item['json'] );
			}
		}

		$clean[] = $entry;
	}

	return $clean;
}

/**
 * Map provider choices.
 */
function sdwd_get_map_provider_choices() {
	$providers = apply_filters( 'sdweddingdirectory/map/provider', [] );
	$choices   = [];

	if ( is_array( $providers ) ) {
		foreach ( $providers as $key => $label ) {
			$choices[ sanitize_key( (string) $key ) ] = sanitize_text_field( (string) $label );
		}
	}

	if ( ! isset( $choices['google'] ) ) {
		$choices['google'] = __( 'Google Map', 'sdweddingdirectory' );
	}

	return $choices;
}

/**
 * Admin email selection choices.
 */
function sdwd_get_admin_email_choices() {
	$choices = [];
	$emails  = get_option( 'sdwd_admin_emails', [] );

	if ( is_array( $emails ) ) {
		foreach ( $emails as $index => $row ) {
			if ( is_array( $row ) && ! empty( $row['email_id'] ) ) {
				$choices[ (string) $index ] = sanitize_email( $row['email_id'] );
			}
		}
	}

	if ( empty( $choices ) ) {
		$choices['0'] = sanitize_email( get_bloginfo( 'admin_email' ) );
	}

	return $choices;
}

/**
 * Register a settings field with automatic rendering.
 */
function sdwd_register_field( $option_key, $label, $page, $section, $type = 'text', $args = [] ) {
	$sanitize_callback = isset( $args['sanitize'] ) ? $args['sanitize'] : 'sanitize_text_field';
	$default_value     = $args['default'] ?? '';

	register_setting(
		$page,
		'sdwd_' . $option_key,
		[
			'sanitize_callback' => $sanitize_callback,
			'default'           => $default_value,
		]
	);

	add_settings_field(
		'sdwd_' . $option_key,
		$label,
		function () use ( $option_key, $type, $args ) {
			$value = get_option( 'sdwd_' . $option_key, $args['default'] ?? '' );

			switch ( $type ) {
				case 'textarea':
					printf(
						'<textarea name="sdwd_%s" rows="%s" class="large-text">%s</textarea>',
						esc_attr( $option_key ),
						esc_attr( $args['rows'] ?? 6 ),
						esc_textarea( is_string( $value ) ? $value : '' )
					);
					break;

				case 'color':
					printf(
						'<input type="text" name="sdwd_%s" value="%s" class="sdwd-color-picker" data-default-color="%s" />',
						esc_attr( $option_key ),
						esc_attr( is_string( $value ) ? $value : '' ),
						esc_attr( $args['default'] ?? '#000000' )
					);
					break;

				case 'select':
					$choices = [];
					if ( isset( $args['choices_callback'] ) && is_callable( $args['choices_callback'] ) ) {
						$choices = (array) call_user_func( $args['choices_callback'] );
					} elseif ( isset( $args['choices'] ) && is_array( $args['choices'] ) ) {
						$choices = $args['choices'];
					}

					printf( '<select name="sdwd_%s">', esc_attr( $option_key ) );
					foreach ( $choices as $choice_value => $choice_label ) {
						printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( (string) $choice_value ),
							selected( (string) $value, (string) $choice_value, false ),
							esc_html( $choice_label )
						);
					}
					echo '</select>';
					break;

				case 'upload':
					printf(
						'<input type="text" name="sdwd_%s" value="%s" class="regular-text" /> <button type="button" class="button sdwd-upload-btn">%s</button>',
						esc_attr( $option_key ),
						esc_attr( is_string( $value ) ? $value : '' ),
						esc_html__( 'Upload', 'sdweddingdirectory' )
					);
					break;

				case 'toggle':
					printf( '<input type="hidden" name="sdwd_%s" value="off" />', esc_attr( $option_key ) );
					printf(
						'<label><input type="checkbox" name="sdwd_%s" value="on" %s /> %s</label>',
						esc_attr( $option_key ),
						checked( 'on', sdwd_sanitize_on_off( $value ), false ),
						esc_html__( 'Enabled', 'sdweddingdirectory' )
					);
					break;

				case 'list_json':
					$json_value = '[]';
					if ( is_array( $value ) ) {
						$json_value = wp_json_encode( $value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
					} elseif ( is_string( $value ) && '' !== $value ) {
						$json_value = $value;
					}

					printf(
						'<textarea name="sdwd_%s" rows="%s" class="large-text code">%s</textarea>',
						esc_attr( $option_key ),
						esc_attr( $args['rows'] ?? 12 ),
						esc_textarea( $json_value )
					);
					break;

				case 'link_color':
					$group_fields = isset( $args['fields'] ) && is_array( $args['fields'] )
						? $args['fields']
						: [ 'link', 'hover', 'active', 'visited', 'focus', 'bg', 'text', 'border' ];
					$group_values = is_array( $value ) ? $value : [];

					echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:8px 12px;max-width:760px;">';
					foreach ( $group_fields as $field_key ) {
						$field_val = isset( $group_values[ $field_key ] ) ? $group_values[ $field_key ] : '';
						printf(
							'<label>%1$s<br/><input type="text" name="sdwd_%2$s[%3$s]" value="%4$s" class="sdwd-color-picker" data-default-color="" /></label>',
							esc_html( ucwords( str_replace( '_', ' ', (string) $field_key ) ) ),
							esc_attr( $option_key ),
							esc_attr( $field_key ),
							esc_attr( $field_val )
						);
					}
					echo '</div>';
					break;

				case 'theme_palette':
					$defaults = isset( $args['default'] ) && is_array( $args['default'] ) ? $args['default'] : [];
					$palette  = is_array( $value ) ? array_merge( $defaults, $value ) : $defaults;

					echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:8px 12px;max-width:900px;">';
					foreach ( $palette as $css_var => $color_value ) {
						printf(
							'<label>%1$s<br/><input type="text" name="sdwd_%2$s[%3$s]" value="%4$s" class="sdwd-color-picker" data-default-color="%4$s" /></label>',
							esc_html( (string) $css_var ),
							esc_attr( $option_key ),
							esc_attr( (string) $css_var ),
							esc_attr( (string) $color_value )
						);
					}
					echo '</div>';
					break;

				case 'taxonomy_select':
					$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : 'category';
					$terms    = get_terms(
						[
							'taxonomy'   => $taxonomy,
							'hide_empty' => false,
						]
					);

					printf( '<select name="sdwd_%s">', esc_attr( $option_key ) );
					printf( '<option value="">%s</option>', esc_html__( 'Select', 'sdweddingdirectory' ) );
					if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
						foreach ( $terms as $term ) {
							printf(
								'<option value="%s" %s>%s</option>',
								esc_attr( (string) $term->term_id ),
								selected( (string) $value, (string) $term->term_id, false ),
								esc_html( $term->name )
							);
						}
					}
					echo '</select>';
					break;

				default:
					printf(
						'<input type="text" name="sdwd_%s" value="%s" class="regular-text" />',
						esc_attr( $option_key ),
						esc_attr( is_scalar( $value ) ? (string) $value : '' )
					);
					break;
			}

			if ( ! empty( $args['desc'] ) ) {
				printf( '<p class="description">%s</p>', wp_kses_post( $args['desc'] ) );
			}
		},
		$page,
		$section
	);
}

/**
 * Register style settings fields.
 */
function sdwd_register_style_settings_fields() {
	$page    = 'sdwd_style-settings_colors';
	$section = 'sdwd_style_section';

	add_settings_section( $section, __( 'Color Palette', 'sdweddingdirectory' ), null, $page );

	$palette_default = [
		'--sdweddingdirectory-color-orange'      => '#f48f00',
		'--sdweddingdirectory-color-light-orange' => '#d47c00',
		'--sdweddingdirectory-color-rgba-orange' => 'rgba(244, 143, 1, 0.8)',
		'--sdweddingdirectory-color-cyan'        => '#00aeaf',
		'--sdweddingdirectory-color-dark-cyan'   => '#009091',
		'--sdweddingdirectory-color-light-cyan'  => '#00adae',
		'--sdweddingdirectory-color-rgba-cyan'   => 'rgba(0, 174, 175, 0.9)',
		'--sdweddingdirectory-color-teal'        => '#005b5c',
		'--sdweddingdirectory-color-skin'        => '#fef4e6',
	];

	sdwd_register_field(
		'sdweddingdirectory-color-palette',
		__( 'Theme Color Palette', 'sdweddingdirectory' ),
		$page,
		$section,
		'theme_palette',
		[
			'default'  => $palette_default,
			'sanitize' => 'sdwd_sanitize_theme_palette',
		]
	);

	sdwd_register_field(
		'body_style',
		__( 'Body Style', 'sdweddingdirectory' ),
		$page,
		$section,
		'link_color',
		[
			'fields'   => [ 'bg' ],
			'sanitize' => 'sdwd_sanitize_link_color',
		]
	);

	sdwd_register_field( 'heading_h1', __( 'Heading H1 Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'heading_h2', __( 'Heading H2 Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'heading_h3', __( 'Heading H3 Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'heading_h4', __( 'Heading H4 Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'heading_h5', __( 'Heading H5 Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'heading_h6', __( 'Heading H6 Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'paragraph_p', __( 'Paragraph Color', 'sdweddingdirectory' ), $page, $section, 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );

	sdwd_register_field(
		'primary_button',
		__( 'Primary Button', 'sdweddingdirectory' ),
		$page,
		$section,
		'link_color',
		[
			'fields'   => [ 'bg', 'text', 'border' ],
			'sanitize' => 'sdwd_sanitize_link_color',
		]
	);

	sdwd_register_field(
		'primary_button_hover',
		__( 'Primary Button Hover', 'sdweddingdirectory' ),
		$page,
		$section,
		'link_color',
		[
			'fields'   => [ 'bg', 'text', 'border' ],
			'sanitize' => 'sdwd_sanitize_link_color',
		]
	);

	sdwd_register_field(
		'default_button',
		__( 'Default Button', 'sdweddingdirectory' ),
		$page,
		$section,
		'link_color',
		[
			'fields'   => [ 'bg', 'text', 'border' ],
			'sanitize' => 'sdwd_sanitize_link_color',
		]
	);

	sdwd_register_field(
		'default_button_hover',
		__( 'Default Button Hover', 'sdweddingdirectory' ),
		$page,
		$section,
		'link_color',
		[
			'fields'   => [ 'bg', 'text', 'border' ],
			'sanitize' => 'sdwd_sanitize_link_color',
		]
	);

	sdwd_register_field(
		'anchor_customization',
		__( 'Anchor Link Colors', 'sdweddingdirectory' ),
		$page,
		$section,
		'link_color',
		[
			'fields'   => [ 'link', 'hover', 'active', 'visited', 'focus' ],
			'sanitize' => 'sdwd_sanitize_link_color',
		]
	);
}

/**
 * Register couple tool tab fields.
 */
function sdwd_register_couple_tools_fields() {
	$page = 'sdwd_couple-tools_wishlist';
	add_settings_section( 'sdwd_wishlist_sec', __( 'Wishlist', 'sdweddingdirectory' ), null, $page );
	sdwd_register_field( 'wishlist_icon_bg', __( 'Icon Background', 'sdweddingdirectory' ), $page, 'sdwd_wishlist_sec', 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'wishlist_icon_bg_hover', __( 'Icon Background Hover', 'sdweddingdirectory' ), $page, 'sdwd_wishlist_sec', 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'wishlist_icon_color', __( 'Icon Color', 'sdweddingdirectory' ), $page, 'sdwd_wishlist_sec', 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );
	sdwd_register_field( 'wishlist_icon_color_hover', __( 'Icon Color Hover', 'sdweddingdirectory' ), $page, 'sdwd_wishlist_sec', 'color', [ 'sanitize' => 'sdwd_sanitize_color_value' ] );

	$page = 'sdwd_couple-tools_checklist';
	add_settings_section( 'sdwd_checklist_sec', __( 'Checklist', 'sdweddingdirectory' ), null, $page );
	sdwd_register_field( 'default_checklist_data_switch', __( 'Enable Default Checklist Data', 'sdweddingdirectory' ), $page, 'sdwd_checklist_sec', 'toggle', [ 'default' => 'on', 'sanitize' => 'sdwd_sanitize_on_off' ] );
	sdwd_register_field(
		'admin_create_default_todo_list',
		__( 'Default Checklist Data (JSON)', 'sdweddingdirectory' ),
		$page,
		'sdwd_checklist_sec',
		'list_json',
		[
			'rows'     => 16,
			'sanitize' => 'sdwd_sanitize_checklist_defaults',
			'desc'     => __( 'Edit as JSON array of checklist items.', 'sdweddingdirectory' ),
		]
	);

	$page = 'sdwd_couple-tools_guestlist';
	add_settings_section( 'sdwd_guestlist_sec', __( 'Guest List', 'sdweddingdirectory' ), null, $page );
	sdwd_register_field( 'guest_list_group', __( 'Guest Groups (JSON)', 'sdweddingdirectory' ), $page, 'sdwd_guestlist_sec', 'list_json', [ 'rows' => 12, 'sanitize' => 'sdwd_sanitize_guest_list_group' ] );
	sdwd_register_field( 'guest_list_menu_group', __( 'Menu Groups (JSON)', 'sdweddingdirectory' ), $page, 'sdwd_guestlist_sec', 'list_json', [ 'rows' => 12, 'sanitize' => 'sdwd_sanitize_guest_menu_group' ] );
	sdwd_register_field( 'guest_list_event_group', __( 'Event Groups (JSON)', 'sdweddingdirectory' ), $page, 'sdwd_guestlist_sec', 'list_json', [ 'rows' => 12, 'sanitize' => 'sdwd_sanitize_guest_event_group' ] );

	$page = 'sdwd_couple-tools_budget';
	add_settings_section( 'sdwd_budget_sec', __( 'Budget Calculator', 'sdweddingdirectory' ), null, $page );
	sdwd_register_field( 'sdweddingdirectory_default_budget_data_switch', __( 'Enable Default Budget Data', 'sdweddingdirectory' ), $page, 'sdwd_budget_sec', 'toggle', [ 'default' => 'on', 'sanitize' => 'sdwd_sanitize_on_off' ] );
	sdwd_register_field( 'sdweddingdirectory_default_budget_amount', __( 'Default Budget Amount', 'sdweddingdirectory' ), $page, 'sdwd_budget_sec', 'text', [ 'sanitize' => 'sanitize_text_field' ] );
	sdwd_register_field( 'sdweddingdirectory_budget_category_data', __( 'Budget Categories (JSON)', 'sdweddingdirectory' ), $page, 'sdwd_budget_sec', 'list_json', [ 'rows' => 18, 'sanitize' => 'sdwd_sanitize_budget_categories' ] );
}

/**
 * Register real wedding settings fields.
 */
function sdwd_register_realwedding_settings_fields() {
	$page = 'sdwd_realwedding_general';
	add_settings_section( 'sdwd_rw_sec', __( 'Display Settings', 'sdweddingdirectory' ), null, $page );
	sdwd_register_field(
		'realwedding-dress-category',
		__( 'Dress Category', 'sdweddingdirectory' ),
		$page,
		'sdwd_rw_sec',
		'taxonomy_select',
		[
			'taxonomy' => 'venue-type',
			'sanitize' => 'absint',
			'desc'     => __( 'Select the Dress Category to show on the real wedding page.', 'sdweddingdirectory' ),
		]
	);
}

/**
 * Register venue and map settings fields.
 */
function sdwd_register_venue_settings_fields() {
	$page = 'sdwd_venue-settings_venue';
	add_settings_section( 'sdwd_venue_sec', __( 'Venue Defaults', 'sdweddingdirectory' ), null, $page );

	sdwd_register_field(
		'new_venue_status',
		__( 'Venue Publish Setting', 'sdweddingdirectory' ),
		$page,
		'sdwd_venue_sec',
		'select',
		[
			'choices'  => [
				'0'       => __( 'Admin Verify', 'sdweddingdirectory' ),
				'1'       => __( 'Auto Approved', 'sdweddingdirectory' ),
				'publish' => __( 'Auto Approved (Legacy)', 'sdweddingdirectory' ),
			],
			'default'  => 'publish',
			'sanitize' => 'sanitize_text_field',
		]
	);

	sdwd_register_field(
		'_currencty_possition_',
		__( 'Currency Position', 'sdweddingdirectory' ),
		$page,
		'sdwd_venue_sec',
		'select',
		[
			'choices'  => [
				'left'  => __( 'Left', 'sdweddingdirectory' ),
				'right' => __( 'Right', 'sdweddingdirectory' ),
			],
			'default'  => 'left',
			'sanitize' => 'sanitize_text_field',
		]
	);

	sdwd_register_field(
		'venue_currency_sign',
		__( 'Venue Currency Sign', 'sdweddingdirectory' ),
		$page,
		'sdwd_venue_sec',
		'text',
		[
			'default'  => '$',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'Please insert your venue currency sign.', 'sdweddingdirectory' ),
		]
	);

	sdwd_register_field(
		'venue_single_page_gallery_layout',
		__( 'Venue Single Page Gallery Layout', 'sdweddingdirectory' ),
		$page,
		'sdwd_venue_sec',
		'select',
		[
			'choices'  => [
				'1' => __( 'Full Width Venue Gallery', 'sdweddingdirectory' ),
				'4' => __( 'Four Column Venue Gallery', 'sdweddingdirectory' ),
			],
			'default'  => '4',
			'sanitize' => 'sanitize_text_field',
		]
	);

	sdwd_register_field(
		'request_quote_approval',
		__( 'Request Quote Approval', 'sdweddingdirectory' ),
		$page,
		'sdwd_venue_sec',
		'select',
		[
			'choices'  => [
				'publish' => __( 'Auto Approval', 'sdweddingdirectory' ),
				'pending' => __( 'Manual Approval', 'sdweddingdirectory' ),
			],
			'default'  => 'publish',
			'sanitize' => 'sanitize_text_field',
		]
	);

	$page = 'sdwd_venue-settings_map';
	add_settings_section( 'sdwd_map_sec', __( 'Map Configuration', 'sdweddingdirectory' ), null, $page );

	sdwd_register_field(
		'sdweddingdirectory_map_provider',
		__( 'Map Provider', 'sdweddingdirectory' ),
		$page,
		'sdwd_map_sec',
		'select',
		[
			'choices_callback' => 'sdwd_get_map_provider_choices',
			'default'          => 'google',
			'sanitize'         => 'sanitize_text_field',
		]
	);
	sdwd_register_field( 'sdweddingdirectory_google_maps_api_key', __( 'Google Maps API Key', 'sdweddingdirectory' ), $page, 'sdwd_map_sec', 'text', [ 'sanitize' => 'sanitize_text_field' ] );
	sdwd_register_field( 'map_zoom_level', __( 'Map Zoom Level', 'sdweddingdirectory' ), $page, 'sdwd_map_sec', 'text', [ 'default' => '9', 'sanitize' => 'absint' ] );
	sdwd_register_field( 'sdweddingdirectory_latitude', __( 'Default Latitude', 'sdweddingdirectory' ), $page, 'sdwd_map_sec', 'text', [ 'default' => '32.7157', 'sanitize' => 'sanitize_text_field' ] );
	sdwd_register_field( 'sdweddingdirectory_longitude', __( 'Default Longitude', 'sdweddingdirectory' ), $page, 'sdwd_map_sec', 'text', [ 'default' => '-117.1611', 'sanitize' => 'sanitize_text_field' ] );
}

/**
 * Register admin-email toggle/id fields for one email template slug.
 */
function sdwd_register_admin_email_fields( $page, $section, $slug ) {
	sdwd_register_field(
		'admin-email-' . $slug,
		__( 'Admin Email Enable?', 'sdweddingdirectory' ),
		$page,
		$section,
		'toggle',
		[
			'default'  => 'off',
			'sanitize' => 'sdwd_sanitize_on_off',
		]
	);

	sdwd_register_field(
		'admin-email-id-' . $slug,
		__( 'Admin Receive Email ID', 'sdweddingdirectory' ),
		$page,
		$section,
		'select',
		[
			'choices_callback' => 'sdwd_get_admin_email_choices',
			'default'          => '0',
			'sanitize'         => 'absint',
		]
	);
}

/**
 * Register subject/body fields for one email template slug.
 */
function sdwd_register_email_template_fields( $page, $section, $slug, $subject_label, $subject_default, $body_label, $body_default, $body_rows = 6 ) {
	sdwd_register_field(
		'email-subject-' . $slug,
		$subject_label,
		$page,
		$section,
		'text',
		[
			'default'  => $subject_default,
			'sanitize' => 'sanitize_text_field',
		]
	);

	sdwd_register_field(
		'email-body-' . $slug,
		$body_label,
		$page,
		$section,
		'textarea',
		[
			'default'  => $body_default,
			'rows'     => $body_rows,
			'sanitize' => 'wp_kses_post',
		]
	);

	sdwd_register_admin_email_fields( $page, $section, $slug );
}

/**
 * Register email settings fields.
 */
function sdwd_register_email_settings_fields() {
	$page = 'sdwd_email-settings_general';
	add_settings_section( 'sdwd_email_gen_sec', __( 'Email Template Defaults', 'sdweddingdirectory' ), null, $page );

	sdwd_register_field(
		'admin_emails',
		__( 'Admin Emails (JSON)', 'sdweddingdirectory' ),
		$page,
		'sdwd_email_gen_sec',
		'list_json',
		[
			'rows'     => 10,
			'sanitize' => 'sdwd_sanitize_admin_emails',
			'desc'     => __( 'JSON array with objects using keys like title and email_id.', 'sdweddingdirectory' ),
		]
	);
	sdwd_register_field( 'email_header_image', __( 'Email Header Image', 'sdweddingdirectory' ), $page, 'sdwd_email_gen_sec', 'upload', [ 'sanitize' => 'esc_url_raw' ] );
	sdwd_register_field(
		'email_footer_social_media',
		__( 'Email Footer Social Media (JSON)', 'sdweddingdirectory' ),
		$page,
		'sdwd_email_gen_sec',
		'list_json',
		[
			'rows'     => 12,
			'sanitize' => 'sdwd_sanitize_email_social_media',
		]
	);
	sdwd_register_field( 'sdweddingdirectory_email_footer_content', __( 'Email Footer Content', 'sdweddingdirectory' ), $page, 'sdwd_email_gen_sec', 'text', [ 'sanitize' => 'sanitize_text_field' ] );

	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_gen_sec',
		'forgot-password',
		__( 'Forgot Password Subject', 'sdweddingdirectory' ),
		__( 'Your New Password for {{site_name}}', 'sdweddingdirectory' ),
		__( 'Forgot Password Body', 'sdweddingdirectory' ),
		'<p>Hello {{username}},</p><p>Your new password : {{password}}</p><p><a href="{{user_login_link}}" style="{{primary_button_style}}">Login</a></p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_gen_sec',
		'change-password',
		__( 'Change Password Subject', 'sdweddingdirectory' ),
		__( 'Your Password Changed Successfully for {{site_name}}', 'sdweddingdirectory' ),
		__( 'Change Password Body', 'sdweddingdirectory' ),
		'<p>Hello {{username}},</p><p>Your password has been updated successfully.</p><p><a href="{{user_login_link}}" style="{{primary_button_style}}">Login</a></p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_gen_sec',
		'verify-user',
		__( 'Verify User Subject', 'sdweddingdirectory' ),
		__( 'Please verify your email for {{site_name}}', 'sdweddingdirectory' ),
		__( 'Verify User Body', 'sdweddingdirectory' ),
		'<p>Hello {{username}},</p><p>You&rsquo;re almost ready to start enjoying {{site_name}}.</p><p>Please verify your email to complete your account setup.</p><p><a href="{{user_verify_link}}" style="{{primary_button_style}}">Verify</a></p><p>Alternatively, paste this link in your browser: {{user_verify_link}}</p>',
		6
	);

	$page = 'sdwd_email-settings_admin';
	add_settings_section(
		'sdwd_email_admin_sec',
		__( 'Admin Notifications', 'sdweddingdirectory' ),
		function () {
			echo '<p>' . esc_html__( 'Admin notification toggles are configured per template in the other tabs.', 'sdweddingdirectory' ) . '</p>';
		},
		$page
	);

	$page = 'sdwd_email-settings_couple';
	add_settings_section( 'sdwd_email_couple_sec', __( 'Couple Notifications', 'sdweddingdirectory' ), null, $page );
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_couple_sec',
		'couple-registeration-email',
		__( 'New Couple Registration Subject', 'sdweddingdirectory' ),
		__( 'Welcome to {{site_name}}', 'sdweddingdirectory' ),
		__( 'New Couple Registration Body', 'sdweddingdirectory' ),
		'<p>Hello {{couple_username}},</p><p>Welcome to {{site_name}}!</p><p>Username : {{couple_username}}</p><p>Email : {{couple_email}}</p><p>Password : {{couple_password}}</p><p><a href="{{couple_login_redirect_link_dashboard}}" style="{{primary_button_style}}">Couple Dashboard</a></p><p>Thank you</p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_couple_sec',
		'couple-request-venue',
		__( 'Couple Request Quote Subject', 'sdweddingdirectory' ),
		__( 'Your Request for {{venue_name}} submited', 'sdweddingdirectory' ),
		__( 'Couple Request Quote Body', 'sdweddingdirectory' ),
		'<p>Hello {{couple_username}},</p><p>You have requested quote for {{venue_name}}.</p><p>{{request_quote_form_fields}}</p>',
		6
	);

	$page = 'sdwd_email-settings_vendor';
	add_settings_section( 'sdwd_email_vendor_sec', __( 'Vendor Notifications', 'sdweddingdirectory' ), null, $page );
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_vendor_sec',
		'vendor-registeration-email',
		__( 'New Vendor Registration Subject', 'sdweddingdirectory' ),
		__( 'Welcome to {{site_name}}', 'sdweddingdirectory' ),
		__( 'New Vendor Registration Body', 'sdweddingdirectory' ),
		'<p>Hello {{vendor_username}},</p><p>Welcome to {{site_name}}!</p><p>Username : {{vendor_username}}</p><p>Email : {{vendor_email}}</p><p>Password : {{vendor_password}}</p><p><a href="{{vendor_login_redirect_link_dashboard}}" style="{{primary_button_style}}">Vendor Dashboard</a></p><p>Thank you</p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_vendor_sec',
		'vendor-venue-form-request',
		__( 'Vendor Request Quote Subject', 'sdweddingdirectory' ),
		__( 'Your New Request for {{venue_name}}', 'sdweddingdirectory' ),
		__( 'Vendor Request Quote Body', 'sdweddingdirectory' ),
		'<p>Hello {{vendor_username}},</p><p>You have a new request for {{venue_name}}.</p><p>{{request_quote_form_fields}}</p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_vendor_sec',
		'venue-expired-week',
		__( 'Venue Expired (1 Week) Subject', 'sdweddingdirectory' ),
		__( 'Your Venue will Expired after one week', 'sdweddingdirectory' ),
		__( 'Venue Expired (1 Week) Body', 'sdweddingdirectory' ),
		'<p>Hello {{vendor_username}},</p><p>We are inform you that, as your pricing package {{pricing_plan_name}} expired on {{pricing_plan_expire}}. please select your favorite pricing plan.</p><p><a href="{{vendor_login_redirect_link_pricing}}" style="{{primary_button_style}}">Vendor Pricing Package</a></p><p>Thank you.</p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_vendor_sec',
		'venue-expired-tomorrow',
		__( 'Venue Expired (Tomorrow) Subject', 'sdweddingdirectory' ),
		__( 'Your Venue will Expired Tomorrow', 'sdweddingdirectory' ),
		__( 'Venue Expired (Tomorrow) Body', 'sdweddingdirectory' ),
		'<p>Hello {{vendor_username}},</p><p>Your venues expired tomorrow. Please select your pricing plan to extends your plan.</p><p><a href="{{vendor_login_redirect_link_pricing}}" style="{{primary_button_style}}">Vendor Pricing Package</a></p><p>Thank you.</p>',
		6
	);
	sdwd_register_email_template_fields(
		$page,
		'sdwd_email_vendor_sec',
		'venue-expired-today',
		__( 'Venue Expired (Today) Subject', 'sdweddingdirectory' ),
		__( 'Venue Expired!', 'sdweddingdirectory' ),
		__( 'Venue Expired (Today) Body', 'sdweddingdirectory' ),
		'<p>Hello {{vendor_username}},</p><p>Your venues expired. Please purchase your favorite pricing plan to activate venues.</p><p><a href="{{vendor_login_redirect_link_pricing}}" style="{{primary_button_style}}">Vendor Pricing Package</a></p><p>Thank you.</p>',
		6
	);
}

/**
 * Render style settings page.
 */
function sdwd_render_style_settings_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Site Style Settings', 'sdweddingdirectory' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'sdwd_style-settings_colors' );
			do_settings_sections( 'sdwd_style-settings_colors' );
			submit_button( __( 'Save Changes', 'sdweddingdirectory' ), 'sd-btn sd-btn-primary' );
			?>
		</form>
	</div>
	<?php
}

/**
 * Render couple tools page.
 */
function sdwd_render_couple_tools_page() {
	sdwd_render_tabbed_settings_page(
		__( 'Couple Tools', 'sdweddingdirectory' ),
		'couple-tools',
		[
			'wishlist'  => __( 'Wishlist', 'sdweddingdirectory' ),
			'checklist' => __( 'Checklist', 'sdweddingdirectory' ),
			'guestlist' => __( 'Guest List', 'sdweddingdirectory' ),
			'budget'    => __( 'Budget Calculator', 'sdweddingdirectory' ),
		]
	);
}

/**
 * Render real wedding settings page.
 */
function sdwd_render_realwedding_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Real Wedding Settings', 'sdweddingdirectory' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'sdwd_realwedding_general' );
			do_settings_sections( 'sdwd_realwedding_general' );
			submit_button( __( 'Save Changes', 'sdweddingdirectory' ), 'sd-btn sd-btn-primary' );
			?>
		</form>
	</div>
	<?php
}

/**
 * Render venue settings page.
 */
function sdwd_render_venue_settings_page() {
	sdwd_render_tabbed_settings_page(
		__( 'Venue Settings', 'sdweddingdirectory' ),
		'venue-settings',
		[
			'venue' => __( 'Venue', 'sdweddingdirectory' ),
			'map'   => __( 'Map', 'sdweddingdirectory' ),
		]
	);
}

/**
 * Render email settings page.
 */
function sdwd_render_email_settings_page() {
	sdwd_render_tabbed_settings_page(
		__( 'Email Settings', 'sdweddingdirectory' ),
		'email-settings',
		[
			'general' => __( 'General', 'sdweddingdirectory' ),
			'admin'   => __( 'Admin', 'sdweddingdirectory' ),
			'couple'  => __( 'Couple', 'sdweddingdirectory' ),
			'vendor'  => __( 'Vendor', 'sdweddingdirectory' ),
		]
	);
}

/**
 * Add native menu pages.
 */
function sdwd_register_settings_admin_menu() {
	add_menu_page(
		__( 'Site Style', 'sdweddingdirectory' ),
		__( 'Site Style', 'sdweddingdirectory' ),
		'manage_options',
		'sdwd-style-settings',
		'sdwd_render_style_settings_page',
		'dashicons-art',
		59
	);

	add_submenu_page(
		'edit.php?post_type=couple',
		__( 'Couple Tools', 'sdweddingdirectory' ),
		__( 'Tools', 'sdweddingdirectory' ),
		'manage_options',
		'sdwd-couple-tools',
		'sdwd_render_couple_tools_page'
	);

	add_submenu_page(
		'edit.php?post_type=real-wedding',
		__( 'Real Wedding Settings', 'sdweddingdirectory' ),
		__( 'Settings', 'sdweddingdirectory' ),
		'manage_options',
		'sdwd-realwedding-settings',
		'sdwd_render_realwedding_page'
	);

	add_submenu_page(
		'edit.php?post_type=venue',
		__( 'Venue Settings', 'sdweddingdirectory' ),
		__( 'Settings', 'sdweddingdirectory' ),
		'manage_options',
		'sdwd-venue-settings',
		'sdwd_render_venue_settings_page'
	);

	add_theme_page(
		__( 'Email Settings', 'sdweddingdirectory' ),
		__( 'Email Settings', 'sdweddingdirectory' ),
		'manage_options',
		'sdwd-email-settings',
		'sdwd_render_email_settings_page'
	);
}
add_action( 'admin_menu', 'sdwd_register_settings_admin_menu' );

/**
 * Register all settings pages and fields.
 */
function sdwd_register_settings_pages_fields() {
	sdwd_register_style_settings_fields();
	sdwd_register_couple_tools_fields();
	sdwd_register_realwedding_settings_fields();
	sdwd_register_venue_settings_fields();
	sdwd_register_email_settings_fields();
}
add_action( 'admin_init', 'sdwd_register_settings_pages_fields' );

// Enqueue color picker + media uploader on our settings pages.
add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( false !== strpos( $hook, 'sdwd-' ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_media();
			wp_add_inline_script(
				'wp-color-picker',
				"jQuery(document).ready(function($){
					$('.sdwd-color-picker').wpColorPicker();
					$('.sdwd-upload-btn').on('click', function(e){
						e.preventDefault();
						var input = $(this).prev('input');
						var frame = wp.media({ multiple: false });
						frame.on('select', function(){ input.val(frame.state().get('selection').first().toJSON().url); });
						frame.open();
					});
				});"
			);
		}
	}
);
