<?php
/**
 * Couple Wedding Website Editor — Shell
 *
 * Spec s17: vertical section tabs (Header / About Us / Our Story / Countdown /
 * RSVP / Groomsman / Bridesmaids / Thank You / Gallery / When & Where / Footer)
 * + live editable content + preview button + save bar.
 *
 * $args:
 *   - active_section (string)
 *   - post_id        (int)
 */

$active_section = $args['active_section'] ?? 'header';
$post_id        = (int) ( $args['post_id'] ?? 0 );

$sections = [
    'header'      => __( 'Header',         'sandiegoweddingdirectory' ),
    'about'       => __( 'About Us',       'sandiegoweddingdirectory' ),
    'story'       => __( 'Our Story',      'sandiegoweddingdirectory' ),
    'countdown'   => __( 'Countdown',      'sandiegoweddingdirectory' ),
    'rsvp'        => __( 'RSVP',           'sandiegoweddingdirectory' ),
    'groomsman'   => __( 'Groomsman',      'sandiegoweddingdirectory' ),
    'bridesmaids' => __( 'Bridesmaids',    'sandiegoweddingdirectory' ),
    'thankyou'    => __( "Our Thank You's", 'sandiegoweddingdirectory' ),
    'gallery'     => __( 'Gallery',        'sandiegoweddingdirectory' ),
    'when-where'  => __( 'When & Where',   'sandiegoweddingdirectory' ),
    'footer'      => __( 'Footer',         'sandiegoweddingdirectory' ),
];

$base_url = home_url( '/couple-dashboard/website/' );

$website_published_url = get_post_meta( $post_id, 'sdwd_website_url', true );
?>

<div class="cd-card cd-website">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Wedding Website', 'sandiegoweddingdirectory' ); ?></h1>
        <div class="cd-website__head-actions">
            <?php if ( $website_published_url ) : ?>
                <a href="<?php echo esc_url( $website_published_url ); ?>" target="_blank" rel="noopener" class="btn btn--ghost"><?php esc_html_e( 'Preview', 'sandiegoweddingdirectory' ); ?></a>
            <?php endif; ?>
            <button type="button" class="btn btn--primary" data-cd-website-save><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
        </div>
    </div>

    <div class="cd-website__layout">
        <nav class="cd-website__sections" aria-label="<?php esc_attr_e( 'Website sections', 'sandiegoweddingdirectory' ); ?>">
            <?php foreach ( $sections as $slug => $label ) :
                $url       = add_query_arg( 'section', $slug, $base_url );
                $is_active = ( $slug === $active_section );
            ?>
                <a href="<?php echo esc_url( $url ); ?>" class="cd-website__section-tab<?php echo $is_active ? ' cd-website__section-tab--active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="cd-website__editor">
            <h2 class="cd-website__section-title"><?php echo esc_html( $sections[ $active_section ] ?? '' ); ?></h2>
            <p class="cd-section__soon"><?php
                /* translators: %s: section name */
                printf( esc_html__( 'Inline editor for the %s section is coming next. Section data will save to wedding-website meta on the couple post.', 'sandiegoweddingdirectory' ), esc_html( strtolower( $sections[ $active_section ] ?? '' ) ) );
            ?></p>
        </div>
    </div>
</div>
