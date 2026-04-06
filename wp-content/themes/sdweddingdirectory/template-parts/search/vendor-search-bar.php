<?php
/**
 * Reusable vendor search bar (category + button), matching the home dual-mode vendor row.
 *
 * Depends on CSS handles: bootstrap, global-style, sdweddingdirectory-custom-theme-style, sdweddingdirectory-parent-style.
 * Depends on JS handles: sdweddingdirectory-theme-script, sdweddingdirectory-core-script, sdweddingdirectory-dropdown-script.
 */

$vendor_search_args = isset( $args ) && is_array( $args ) ? $args : [];

$vendor_search_parent_base = '';

if ( class_exists( 'SDWeddingDirectory_Loader' ) && method_exists( 'SDWeddingDirectory_Loader', '_rand' ) ) {
	$vendor_search_parent_base = esc_attr( SDWeddingDirectory_Loader::_rand() );
} else {
	$vendor_search_parent_base = sanitize_title( 'sdweddingdirectory_' . wp_generate_password( 10, false, false ) );
}

$vendor_search_parent_id = $vendor_search_parent_base . '_vendor';

$vendor_search_button_text = ! empty( $vendor_search_args['search_button_text'] )
	? sanitize_text_field( $vendor_search_args['search_button_text'] )
	: esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' );

$vendor_search_form_action = home_url( '/vendors/' );

// Query available vendor categories inside this template so the dropdown input is self-contained.
$vendor_category_term_ids = get_terms(
	[
		'taxonomy'   => esc_attr( 'vendor-category' ),
		'hide_empty' => false,
		'fields'     => 'ids',
	]
);

$vendor_category_term_ids = ( ! is_wp_error( $vendor_category_term_ids ) && is_array( $vendor_category_term_ids ) )
	? array_map( 'absint', $vendor_category_term_ids )
	: [];

$vendor_category_input_args = [
	'before_input'  => '<div class="col-12 col-md-6">',
	'after_input'   => '</div>',
	'placeholder'   => esc_attr__( 'Search vendor category or name', 'sdweddingdirectory-shortcodes' ),
	'post_type'     => esc_attr( 'vendor' ),
	'taxonomy'      => esc_attr( 'vendor-category' ),
	'parent_id'     => $vendor_search_parent_id,
	'allowed_terms' => $vendor_category_term_ids,
];

$vendor_hidden_input_args = [
	'id' => $vendor_search_parent_id,
];

$vendor_category_input_html = '';
$vendor_hidden_input_html   = '';

if ( class_exists( 'SDWeddingDirectory_Category_Dropdown_Script' ) && method_exists( 'SDWeddingDirectory_Category_Dropdown_Script', 'input_category_field' ) ) {
	$vendor_category_input_html = SDWeddingDirectory_Category_Dropdown_Script::input_category_field( $vendor_category_input_args );
} elseif ( has_filter( 'sdweddingdirectory/input-category' ) ) {
	$vendor_category_input_html = apply_filters( 'sdweddingdirectory/input-category', $vendor_category_input_args );
}

if ( class_exists( 'SDWeddingDirectory_Dropdown_Query_String_Hidden_Input' ) && method_exists( 'SDWeddingDirectory_Dropdown_Query_String_Hidden_Input', 'query_inputs' ) ) {
	$vendor_hidden_input_html = SDWeddingDirectory_Dropdown_Query_String_Hidden_Input::query_inputs( $vendor_hidden_input_args );
} elseif ( has_filter( 'sdweddingdirectory/find-venue/hidden-input' ) ) {
	$vendor_hidden_input_html = apply_filters( 'sdweddingdirectory/find-venue/hidden-input', $vendor_hidden_input_args );
}
?>
<form class="sdweddingdirectory-result-page" action="<?php echo esc_url( $vendor_search_form_action ); ?>">
	<div class="slider-form rounded">
		<div class="row align-items-center form-bg gx-1">
			<div class="col-md-10">
				<div class="sd-vendor-search-fields">
					<div class="row sdweddingdirectory-dropdown-parent gx-1" id="<?php echo esc_attr( $vendor_search_parent_id ); ?>">
						<?php echo $vendor_category_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="d-grid">
					<button type="submit" class="btn btn-default text-nowrap btn-block">
						<?php echo esc_html( $vendor_search_button_text ); ?>
					</button>
				</div>
				<?php echo $vendor_hidden_input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</form>
