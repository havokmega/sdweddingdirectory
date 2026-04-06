<?php
/**
 *  Term Meta Helpers
 *  -----------------
 *  Drop-in replacements for ACF's get_field() / update_field() when
 *  reading taxonomy term meta. All data was originally stored by ACF
 *  in the standard wp_termmeta table, so get_term_meta() returns the
 *  same raw values.
 *
 *  These functions let the site work identically with or without ACF.
 */
defined( 'ABSPATH' ) || exit;

/**
 *  Get a single term meta value.
 *  Replaces: get_field( $field, $taxonomy . '_' . $term_id )
 *
 *  @param  string     $field    Meta key name.
 *  @param  int        $term_id  Term ID (integer, NOT the ACF "taxonomy_id" string).
 *  @return mixed                Meta value or empty string.
 */
if ( ! function_exists( 'sdwd_get_term_field' ) ) {
    function sdwd_get_term_field( $field, $term_id ) {
        return get_term_meta( absint( $term_id ), $field, true );
    }
}

/**
 *  Get an ACF-style repeater stored as flattened term meta.
 *
 *  ACF stores repeaters as:
 *      {field}       = row count (int)
 *      {field}_0_{sub} = value
 *      {field}_1_{sub} = value
 *      …
 *
 *  This function reconstructs the array that get_field() would return:
 *      [ ['label' => '…', 'value' => '…'], … ]
 *
 *  @param  string   $field       Meta key prefix (e.g. 'vendor_pricing_options').
 *  @param  int      $term_id     Term ID.
 *  @param  array    $sub_fields  Sub-field names (default: ['label', 'value']).
 *  @return array                 Array of associative rows, or empty array.
 */
if ( ! function_exists( 'sdwd_get_term_repeater' ) ) {
    function sdwd_get_term_repeater( $field, $term_id, $sub_fields = [ 'label', 'value' ] ) {

        $term_id = absint( $term_id );
        $count   = (int) get_term_meta( $term_id, $field, true );

        if ( $count < 1 ) {
            return [];
        }

        $rows = [];

        for ( $i = 0; $i < $count; $i++ ) {

            $row = [];

            foreach ( $sub_fields as $sub ) {
                $row[ $sub ] = get_term_meta( $term_id, "{$field}_{$i}_{$sub}", true );
            }

            $rows[] = $row;
        }

        return $rows;
    }
}

/**
 *  Update a single term meta value.
 *  Replaces: update_field( $field, $value, $taxonomy . '_' . $term_id )
 *
 *  @param  string  $field    Meta key name.
 *  @param  mixed   $value    New value.
 *  @param  int     $term_id  Term ID.
 *  @return int|bool          Meta ID on success, false on failure.
 */
if ( ! function_exists( 'sdwd_update_term_field' ) ) {
    function sdwd_update_term_field( $field, $value, $term_id ) {
        return update_term_meta( absint( $term_id ), $field, $value );
    }
}
