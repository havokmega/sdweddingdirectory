<?php
/**
 * Couple Profile — s5 Social
 *
 * Repeater of platform + URL pairs.
 *
 * $args:
 *   - user_id (int)
 */

$user_id = (int) ( $args['user_id'] ?? get_current_user_id() );
$social  = get_user_meta( $user_id, 'sdwd_social_links', true );
if ( ! is_array( $social ) || empty( $social ) ) {
    $social = [ [ 'platform' => '', 'url' => '' ] ];
}

$platforms = [ 'instagram', 'facebook', 'twitter', 'tiktok', 'youtube', 'pinterest', 'website' ];
?>

<form class="cd-form" data-cd-form="social">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Social links', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-repeater" data-cd-repeater="social">
            <?php foreach ( $social as $i => $row ) : ?>
                <div class="cd-repeater__row">
                    <div class="cd-form__field cd-form__field--select">
                        <label class="screen-reader-text" for="cd_social_platform_<?php echo (int) $i; ?>"><?php esc_html_e( 'Platform', 'sandiegoweddingdirectory' ); ?></label>
                        <select id="cd_social_platform_<?php echo (int) $i; ?>" name="social[<?php echo (int) $i; ?>][platform]">
                            <option value=""><?php esc_html_e( 'Platform', 'sandiegoweddingdirectory' ); ?></option>
                            <?php foreach ( $platforms as $p ) : ?>
                                <option value="<?php echo esc_attr( $p ); ?>" <?php selected( $row['platform'] ?? '', $p ); ?>><?php echo esc_html( ucfirst( $p ) ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="cd-form__field cd-form__field--url">
                        <label class="screen-reader-text" for="cd_social_url_<?php echo (int) $i; ?>"><?php esc_html_e( 'URL', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="url" id="cd_social_url_<?php echo (int) $i; ?>" name="social[<?php echo (int) $i; ?>][url]" value="<?php echo esc_attr( $row['url'] ?? '' ); ?>" placeholder="https://…">
                    </div>
                    <button type="button" class="cd-repeater__remove" aria-label="<?php esc_attr_e( 'Remove', 'sandiegoweddingdirectory' ); ?>">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="cd-repeater__add" data-cd-add="social">+ <?php esc_html_e( 'Add another', 'sandiegoweddingdirectory' ); ?></button>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
