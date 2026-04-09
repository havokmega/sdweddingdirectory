<?php
/**
 * Template Name: Couple Dashboard
 */

if ( ! is_user_logged_in() ) {
    wp_redirect( home_url( '/' ) );
    exit;
}

$user    = wp_get_current_user();
$post_id = get_user_meta( $user->ID, 'sdwd_post_id', true );

if ( ! $post_id ) {
    wp_redirect( home_url( '/' ) );
    exit;
}

$email        = get_post_meta( $post_id, 'sdwd_email', true );
$phone        = get_post_meta( $post_id, 'sdwd_phone', true );
$wedding_date = get_post_meta( $post_id, 'sdwd_wedding_date', true );
$social       = get_post_meta( $post_id, 'sdwd_social', true );

if ( ! is_array( $social ) || empty( $social ) ) { $social = [ [ 'label' => '', 'url' => '' ] ]; }

get_header();
?>

<div class="dashboard-wrapper">
    <div class="container">
        <h1 class="dash__title"><?php esc_html_e( 'My Dashboard', 'sdweddingdirectory' ); ?></h1>
        <div id="sdwd-dashboard-status" class="dash-status"></div>

        <form id="sdwd-dashboard-form" class="dash-form">

            <?php // --- Personal Info --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Personal Info', 'sdweddingdirectory' ); ?></legend>
                <div class="dash-row">
                    <div class="dash-field">
                        <label for="first_name"><?php esc_html_e( 'First Name', 'sdweddingdirectory' ); ?></label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $user->first_name ); ?>">
                    </div>
                    <div class="dash-field">
                        <label for="last_name"><?php esc_html_e( 'Last Name', 'sdweddingdirectory' ); ?></label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $user->last_name ); ?>">
                    </div>
                </div>
            </fieldset>

            <?php // --- Contact Info --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Contact Info', 'sdweddingdirectory' ); ?></legend>
                <div class="dash-row">
                    <div class="dash-field">
                        <label for="sdwd_email"><?php esc_html_e( 'Email', 'sdweddingdirectory' ); ?></label>
                        <input type="email" id="sdwd_email" name="sdwd_email" value="<?php echo esc_attr( $email ); ?>">
                    </div>
                    <div class="dash-field">
                        <label for="sdwd_phone"><?php esc_html_e( 'Phone', 'sdweddingdirectory' ); ?></label>
                        <input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $phone ); ?>">
                    </div>
                </div>
            </fieldset>

            <?php // --- Wedding Details --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Wedding Details', 'sdweddingdirectory' ); ?></legend>
                <div class="dash-field">
                    <label for="sdwd_wedding_date"><?php esc_html_e( 'Wedding Date', 'sdweddingdirectory' ); ?></label>
                    <input type="date" id="sdwd_wedding_date" name="sdwd_wedding_date" value="<?php echo esc_attr( $wedding_date ); ?>">
                </div>
            </fieldset>

            <?php // --- Social Media --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Social Media', 'sdweddingdirectory' ); ?></legend>
                <div class="dash-social__items">
                    <?php foreach ( $social as $i => $row ) : ?>
                        <div class="dash-social__row dash-row">
                            <div class="dash-field">
                                <input type="text" name="sdwd_social[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $row['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. Instagram', 'sdweddingdirectory' ); ?>">
                            </div>
                            <div class="dash-field">
                                <input type="url" name="sdwd_social[<?php echo $i; ?>][url]" value="<?php echo esc_attr( $row['url'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'https://', 'sdweddingdirectory' ); ?>">
                            </div>
                            <button type="button" class="btn btn--outline dash-social__remove">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn--outline dash-social__add"><?php esc_html_e( '+ Add Social Link', 'sdweddingdirectory' ); ?></button>
            </fieldset>

            <?php // --- Password --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Change Password', 'sdweddingdirectory' ); ?></legend>
                <div class="dash-field">
                    <label for="sdwd_new_password"><?php esc_html_e( 'New Password', 'sdweddingdirectory' ); ?></label>
                    <input type="password" id="sdwd_new_password" name="sdwd_new_password" autocomplete="new-password">
                    <p class="dash-field__hint"><?php esc_html_e( 'Leave blank to keep current password.', 'sdweddingdirectory' ); ?></p>
                </div>
            </fieldset>

            <button type="submit" class="btn btn--primary dash-submit"><?php esc_html_e( 'Save Changes', 'sdweddingdirectory' ); ?></button>
        </form>
    </div>
</div>

<?php get_footer(); ?>
