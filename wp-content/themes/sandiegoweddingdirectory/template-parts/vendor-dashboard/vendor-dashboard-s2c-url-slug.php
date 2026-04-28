<?php
/**
 * Vendor Dashboard — Section 2: Public URL / slug editor
 *
 * Lets the vendor change the slug portion of their public profile URL,
 * e.g. /vendor/top-notch-entertainment/ → /vendor/topnotchdj/
 *
 * $args:
 *   - current_slug (string) current post_name
 *   - profile_url  (string) current full profile URL (for preview)
 *   - base_url     (string) everything before the slug, e.g. "https://site.com/vendor/"
 */

$current_slug = $args['current_slug'] ?? '';
$profile_url  = $args['profile_url']  ?? '';
$base_url     = $args['base_url']     ?? '';
?>

<section class="vd-card vd-slug">
    <div class="vd-card__head">
        <h2 class="vd-card__title"><?php esc_html_e( 'Your Public URL', 'sandiegoweddingdirectory' ); ?></h2>
    </div>

    <p class="vd-slug__help">
        <?php esc_html_e( 'Change the last part of your profile URL to something shorter or easier to remember.', 'sandiegoweddingdirectory' ); ?>
    </p>

    <form id="sdwd-slug-form" class="vd-slug__form">
        <div class="vd-slug__row">
            <span class="vd-slug__prefix"><?php echo esc_html( $base_url ); ?></span>
            <input
                type="text"
                id="sdwd_post_name"
                name="sdwd_post_name"
                class="vd-slug__input"
                value="<?php echo esc_attr( $current_slug ); ?>"
                pattern="[a-z0-9-]+"
                maxlength="60"
                autocomplete="off"
                required>
            <span class="vd-slug__suffix">/</span>
        </div>

        <p class="vd-slug__hint">
            <?php esc_html_e( 'Lowercase letters, numbers, and hyphens only.', 'sandiegoweddingdirectory' ); ?>
        </p>

        <div class="vd-slug__actions">
            <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save URL', 'sandiegoweddingdirectory' ); ?></button>
            <?php if ( $profile_url ) : ?>
                <a href="<?php echo esc_url( $profile_url ); ?>" class="vd-slug__preview" target="_blank" rel="noopener">
                    <?php esc_html_e( 'View current page', 'sandiegoweddingdirectory' ); ?> &rarr;
                </a>
            <?php endif; ?>
        </div>

        <div id="sdwd-slug-status" class="dash-status vd-slug__status" aria-live="polite"></div>
    </form>
</section>
