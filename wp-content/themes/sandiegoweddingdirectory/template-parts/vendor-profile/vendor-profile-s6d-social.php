<?php
/**
 * Vendor Profile — s6d Social
 *
 * Repeater of [ label, url ] saved as sdwd_social on the vendor post.
 *
 * $args:
 *   - post_id (int)
 */

$post_id = (int) ( $args['post_id'] ?? 0 );
$social  = get_post_meta( $post_id, 'sdwd_social', true );
if ( ! is_array( $social ) || empty( $social ) ) {
    $social = [ [ 'label' => '', 'url' => '' ] ];
}

$platforms = [ 'instagram', 'facebook', 'twitter', 'tiktok', 'youtube', 'pinterest', 'linkedin', 'website' ];
?>

<form class="cd-form" data-cd-form="profile">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Social links', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-repeater" data-cd-repeater="sdwd_social">
            <?php foreach ( $social as $i => $row ) : ?>
                <div class="cd-repeater__row">
                    <div class="cd-form__field cd-form__field--select">
                        <label class="screen-reader-text" for="sdwd_social_label_<?php echo (int) $i; ?>"><?php esc_html_e( 'Platform', 'sandiegoweddingdirectory' ); ?></label>
                        <select id="sdwd_social_label_<?php echo (int) $i; ?>" name="sdwd_social[<?php echo (int) $i; ?>][label]">
                            <option value=""><?php esc_html_e( 'Platform', 'sandiegoweddingdirectory' ); ?></option>
                            <?php foreach ( $platforms as $p ) : ?>
                                <option value="<?php echo esc_attr( $p ); ?>" <?php selected( $row['label'] ?? '', $p ); ?>><?php echo esc_html( ucfirst( $p ) ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="cd-form__field cd-form__field--url">
                        <label class="screen-reader-text" for="sdwd_social_url_<?php echo (int) $i; ?>"><?php esc_html_e( 'URL', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="url" id="sdwd_social_url_<?php echo (int) $i; ?>" name="sdwd_social[<?php echo (int) $i; ?>][url]" value="<?php echo esc_attr( $row['url'] ?? '' ); ?>" placeholder="https://…">
                    </div>
                    <button type="button" class="cd-repeater__remove" aria-label="<?php esc_attr_e( 'Remove', 'sandiegoweddingdirectory' ); ?>">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="cd-repeater__add" data-cd-add="sdwd_social">+ <?php esc_html_e( 'Add another', 'sandiegoweddingdirectory' ); ?></button>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
