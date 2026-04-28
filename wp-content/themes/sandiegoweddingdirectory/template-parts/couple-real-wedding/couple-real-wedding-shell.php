<?php
/**
 * Couple Real Wedding Editor — Shell
 *
 * Spec s14: vertical tabs (About / Wedding Info / Hire Vendors) + content.
 *
 * $args:
 *   - active_tab (string)
 *   - post_id    (int)
 */

$active_tab = $args['active_tab'] ?? 'about';
$post_id    = (int) ( $args['post_id'] ?? 0 );

$tabs = [
    'about'         => __( 'About',         'sandiegoweddingdirectory' ),
    'wedding-info'  => __( 'Wedding Info',  'sandiegoweddingdirectory' ),
    'hire-vendors'  => __( 'Hire Vendors',  'sandiegoweddingdirectory' ),
];

$base_url = home_url( '/couple-dashboard/real-wedding/' );

// Load existing real-wedding draft, if any.
$rw_title       = get_post_meta( $post_id, 'sdwd_rw_title',       true );
$rw_description = get_post_meta( $post_id, 'sdwd_rw_description', true );
$rw_tags        = get_post_meta( $post_id, 'sdwd_rw_tags',        true );
$rw_cover       = get_post_meta( $post_id, 'sdwd_rw_cover_id',    true );

if ( ! is_array( $rw_tags ) ) { $rw_tags = []; }
?>

<div class="cd-card cd-real-wedding">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Real Wedding Submission', 'sandiegoweddingdirectory' ); ?></h1>
    </div>

    <div class="cd-tabs cd-tabs--vertical">
        <nav class="cd-tabs__list">
            <?php foreach ( $tabs as $slug => $label ) :
                $url       = add_query_arg( 'tab', $slug, $base_url );
                $is_active = ( $slug === $active_tab );
            ?>
                <a href="<?php echo esc_url( $url ); ?>" class="cd-tabs__tab<?php echo $is_active ? ' cd-tabs__tab--active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="cd-tabs__content">
            <?php if ( $active_tab === 'wedding-info' ) : ?>
                <p class="cd-section__soon"><?php esc_html_e( 'Pulls from your Profile → Wedding Info. Editing comes next.', 'sandiegoweddingdirectory' ); ?></p>
            <?php elseif ( $active_tab === 'hire-vendors' ) : ?>
                <p class="cd-section__soon"><?php esc_html_e( 'Tag the vendors who worked your wedding. Coming soon.', 'sandiegoweddingdirectory' ); ?></p>
            <?php else : /* about */ ?>
                <form class="cd-form" data-cd-form="real-wedding-about">
                    <fieldset class="cd-form__group">
                        <legend class="cd-form__legend"><?php esc_html_e( 'Cover photo', 'sandiegoweddingdirectory' ); ?></legend>
                        <div class="cd-media-upload">
                            <?php if ( $rw_cover ) :
                                $url = wp_get_attachment_image_url( $rw_cover, 'medium' );
                            ?>
                                <img src="<?php echo esc_url( $url ); ?>" alt="" class="cd-media-upload__preview">
                            <?php endif; ?>
                            <button type="button" class="btn btn--ghost" data-cd-media="rw_cover"><?php esc_html_e( 'Choose photo', 'sandiegoweddingdirectory' ); ?></button>
                        </div>
                    </fieldset>

                    <fieldset class="cd-form__group">
                        <legend class="cd-form__legend"><?php esc_html_e( 'About your day', 'sandiegoweddingdirectory' ); ?></legend>
                        <div class="cd-form__field">
                            <label for="rw_title"><?php esc_html_e( 'Title', 'sandiegoweddingdirectory' ); ?></label>
                            <input type="text" id="rw_title" name="rw_title" value="<?php echo esc_attr( $rw_title ); ?>" placeholder="<?php esc_attr_e( 'A bohemian sunset wedding in La Jolla', 'sandiegoweddingdirectory' ); ?>">
                        </div>
                        <div class="cd-form__field">
                            <label for="rw_description"><?php esc_html_e( 'Description', 'sandiegoweddingdirectory' ); ?></label>
                            <textarea id="rw_description" name="rw_description" rows="6"><?php echo esc_textarea( $rw_description ); ?></textarea>
                        </div>
                        <div class="cd-form__field">
                            <label><?php esc_html_e( 'Tags', 'sandiegoweddingdirectory' ); ?></label>
                            <div class="cd-tag-pills" data-cd-tags="rw_tags">
                                <?php foreach ( $rw_tags as $tag ) : ?>
                                    <span class="cd-tag-pill"><?php echo esc_html( $tag ); ?> <button type="button" aria-label="remove">&times;</button></span>
                                <?php endforeach; ?>
                                <input type="text" class="cd-tag-pills__input" placeholder="<?php esc_attr_e( 'Add a tag…', 'sandiegoweddingdirectory' ); ?>">
                            </div>
                        </div>
                    </fieldset>

                    <div class="cd-form__actions">
                        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Draft', 'sandiegoweddingdirectory' ); ?></button>
                    </div>
                    <div class="cd-form__status" aria-live="polite"></div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
