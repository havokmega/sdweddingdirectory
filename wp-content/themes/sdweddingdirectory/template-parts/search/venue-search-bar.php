<?php
/**
 * Reusable venue search bar (type + location + button).
 *
 * Depends on CSS handles: bootstrap, global-style, sdweddingdirectory-custom-theme-style, sdweddingdirectory-parent-style.
 * Depends on JS handles: sdweddingdirectory-theme-script, sdweddingdirectory-core-script, sdweddingdirectory-dropdown-script, sdweddingdirectory-search-result.
 */

$venue_search_args = isset( $args ) && is_array( $args ) ? $args : [];

$venue_search_parent_id = '';

if ( class_exists( 'SDWeddingDirectory_Loader' ) && method_exists( 'SDWeddingDirectory_Loader', '_rand' ) ) {
	$venue_search_parent_id = esc_attr( SDWeddingDirectory_Loader::_rand() );
} else {
	$venue_search_parent_id = sanitize_title( 'sdweddingdirectory_' . wp_generate_password( 10, false, false ) );
}

$venue_search_button_text = ! empty( $venue_search_args['search_button_text'] )
	? sanitize_text_field( $venue_search_args['search_button_text'] )
	: esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' );

$venue_location_placeholder = ! empty( $venue_search_args['location_placeholder'] )
	? sanitize_text_field( $venue_search_args['location_placeholder'] )
	: esc_attr__( 'Location', 'sdweddingdirectory-shortcodes' );

$venue_search_form_action = home_url( '/venues/' );

// Query available terms inside this template so the dropdown inputs are self-contained.
$venue_category_term_ids = get_terms(
	[
		'taxonomy'   => esc_attr( 'venue-type' ),
		'hide_empty' => true,
		'fields'     => 'ids',
	]
);

$venue_location_term_ids = get_terms(
	[
		'taxonomy'   => esc_attr( 'venue-location' ),
		'hide_empty' => true,
		'fields'     => 'ids',
	]
);

$venue_category_term_ids = ( ! is_wp_error( $venue_category_term_ids ) && is_array( $venue_category_term_ids ) )
	? array_map( 'absint', $venue_category_term_ids )
	: [];

$venue_location_term_ids = ( ! is_wp_error( $venue_location_term_ids ) && is_array( $venue_location_term_ids ) )
	? array_map( 'absint', $venue_location_term_ids )
	: [];

$venue_category_input_args = [
	'before_input'  => sprintf( '<div class="col-12 col-md-6 %1$s">', '' ),
	'after_input'   => '</div>',
	'placeholder'   => esc_attr__( 'Search by type', 'sdweddingdirectory-shortcodes' ),
	'post_type'     => esc_attr( 'venue' ),
	'taxonomy'      => esc_attr( 'venue-type' ),
	'parent_id'     => $venue_search_parent_id,
	'allowed_terms' => $venue_category_term_ids,
];

$venue_location_input_args = [
	'before_input'  => sprintf( '<div class="col-12 col-md-6 %1$s">', '' ),
	'after_input'   => '</div>',
	'placeholder'   => esc_attr( $venue_location_placeholder ),
	'post_type'     => esc_attr( 'venue' ),
	'taxonomy'      => esc_attr( 'venue-location' ),
	'parent_id'     => $venue_search_parent_id,
	'allowed_terms' => $venue_location_term_ids,
];

$venue_hidden_input_args = [
	'id' => $venue_search_parent_id,
];

$venue_category_input_html = '';
$venue_location_input_html = '';
$venue_hidden_input_html   = '';

if ( class_exists( 'SDWeddingDirectory_Category_Dropdown_Script' ) && method_exists( 'SDWeddingDirectory_Category_Dropdown_Script', 'input_category_field' ) ) {
	$venue_category_input_html = SDWeddingDirectory_Category_Dropdown_Script::input_category_field( $venue_category_input_args );
} elseif ( has_filter( 'sdweddingdirectory/input-category' ) ) {
	$venue_category_input_html = apply_filters( 'sdweddingdirectory/input-category', $venue_category_input_args );
}

if ( class_exists( 'SDWeddingDirectory_Location_Dropdown_Script' ) && method_exists( 'SDWeddingDirectory_Location_Dropdown_Script', 'input_location_field' ) ) {
	$venue_location_input_html = SDWeddingDirectory_Location_Dropdown_Script::input_location_field( $venue_location_input_args );
} elseif ( has_filter( 'sdweddingdirectory/input-location' ) ) {
	$venue_location_input_html = apply_filters( 'sdweddingdirectory/input-location', $venue_location_input_args );
}

if ( class_exists( 'SDWeddingDirectory_Dropdown_Query_String_Hidden_Input' ) && method_exists( 'SDWeddingDirectory_Dropdown_Query_String_Hidden_Input', 'query_inputs' ) ) {
	$venue_hidden_input_html = SDWeddingDirectory_Dropdown_Query_String_Hidden_Input::query_inputs( $venue_hidden_input_args );
} elseif ( has_filter( 'sdweddingdirectory/find-venue/hidden-input' ) ) {
	$venue_hidden_input_html = apply_filters( 'sdweddingdirectory/find-venue/hidden-input', $venue_hidden_input_args );
}
?>
<form class="sdweddingdirectory-result-page" action="<?php echo esc_url( $venue_search_form_action ); ?>">
	<div class="slider-form rounded">
		<div class="row align-items-center form-bg gx-1">
			<div class="col-md-10">
				<div class="sd-venue-search-fields">
					<div class="row sdweddingdirectory-dropdown-parent gx-1" id="<?php echo esc_attr( $venue_search_parent_id ); ?>">
						<?php echo $venue_category_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo $venue_location_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="d-grid">
					<button type="submit" class="btn btn-default text-nowrap btn-block">
						<?php echo esc_html( $venue_search_button_text ); ?>
					</button>
				</div>
				<?php echo $venue_hidden_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</form>
