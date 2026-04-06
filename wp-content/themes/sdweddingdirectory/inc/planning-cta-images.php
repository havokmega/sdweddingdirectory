<?php
/**
 * Replace UI-mockup cards inside planning child-page sections
 * with the CTA images stored in the theme.
 *
 * Image mapping per section:
 *   .sdwd-tool-hero-media          → cta-1_400x400.jpg
 *   first  .sdwd-tool-split-media  → cta-2_440x330.jpg
 *   second .sdwd-tool-split-media  → cta-3_400x210.jpg
 */

add_filter( 'the_content', 'sdwd_planning_cta_images', 20 );

function sdwd_planning_cta_images( $content ) {

    if ( is_admin() || wp_doing_ajax() ) {
        return $content;
    }

    if ( ! is_page() || empty( $content ) ) {
        return $content;
    }

    $page_id   = get_queried_object_id();
    $parent_id = absint( wp_get_post_parent_id( $page_id ) );

    if ( $parent_id !== 4180 ) {
        return $content;
    }

    $slug = get_post_field( 'post_name', $page_id );

    if ( empty( $slug ) ) {
        return $content;
    }

    // Map page slugs to image folder names.
    $slug_to_folder = [
        'wedding-checklist'     => 'checklist',
        'wedding-budget'        => 'budget-calculator',
        'wedding-guest-list'    => 'guest-management',
        'wedding-seating-chart' => 'seating-chart',
        'vendor-manager'        => 'vendor-manager',
        'wedding-website'       => 'wedding-website',
    ];

    $folder = isset( $slug_to_folder[ $slug ] ) ? $slug_to_folder[ $slug ] : $slug;
    $base   = '/assets/images/planning/' . $folder . '/';
    $dir  = get_template_directory() . $base;

    if ( ! is_dir( $dir ) ) {
        return $content;
    }

    $uri   = get_template_directory_uri() . $base;
    $title = esc_attr( get_the_title( $page_id ) );
    $cta_images = [
        [ 'file' => 'cta-1_400x400.jpg', 'width' => 400, 'height' => 400 ],
        [ 'file' => 'cta-2_440x330.jpg', 'width' => 440, 'height' => 330 ],
        [ 'file' => 'cta-3_400x210.jpg', 'width' => 400, 'height' => 210 ],
    ];

    // Use DOMDocument for reliable nested-div replacement.
    libxml_use_internal_errors( true );

    $doc = new DOMDocument();
    $doc->loadHTML(
        '<html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );

    libxml_clear_errors();

    $xpath = new DOMXPath( $doc );

    // Collect target containers: hero-media first, then split-media divs.
    $targets = [];

    $hero_nodes = $xpath->query( '//div[contains(@class,"sdwd-tool-hero-media")]' );
    foreach ( $hero_nodes as $node ) {
        $targets[] = $node;
    }

    $split_nodes = $xpath->query( '//div[contains(@class,"sdwd-tool-split-media")]' );
    foreach ( $split_nodes as $node ) {
        $targets[] = $node;
    }

    // Replace each target's children with the corresponding CTA image.
    foreach ( $targets as $index => $target ) {
        if ( $index >= count( $cta_images ) ) {
            break;
        }

        $cta = $cta_images[ $index ];

        // Remove all child nodes.
        while ( $target->hasChildNodes() ) {
            $target->removeChild( $target->firstChild );
        }

        // Create the img element with exact dimensions.
        $img = $doc->createElement( 'img' );
        $img->setAttribute( 'loading', 'lazy' );
        $img->setAttribute( 'src', esc_url( $uri . $cta['file'] ) );
        $img->setAttribute( 'alt', $title );
        $img->setAttribute( 'width', $cta['width'] );
        $img->setAttribute( 'height', $cta['height'] );
        $img->setAttribute( 'style', 'max-width:100%;height:auto;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.10);' );

        $target->appendChild( $img );
    }

    // Extract body content.
    $body = $doc->getElementsByTagName( 'body' )->item( 0 );

    if ( ! $body ) {
        return $content;
    }

    $output = '';
    foreach ( $body->childNodes as $child ) {
        $output .= $doc->saveHTML( $child );
    }

    return $output;
}
