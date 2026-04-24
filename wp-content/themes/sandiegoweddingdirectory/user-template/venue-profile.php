<?php
/**
 * Template Name: Venue Profile (edit form)
 *
 * Assign this template to a `/venue-dashboard/profile/` page so venues
 * can edit their business info. The `/venue-dashboard/` landing uses
 * user-template/venue-dashboard.php (overview only).
 *
 * Parallel of vendor-profile.php with two venue-specific additions:
 * address + capacity fields (P2-PARITY-03).
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

$post     = get_post( $post_id );
$company  = get_post_meta( $post_id, 'sdwd_company_name', true );
$email    = get_post_meta( $post_id, 'sdwd_email', true );
$phone    = get_post_meta( $post_id, 'sdwd_phone', true );
$website  = get_post_meta( $post_id, 'sdwd_company_website', true );
$social   = get_post_meta( $post_id, 'sdwd_social', true );
$hours    = get_post_meta( $post_id, 'sdwd_hours', true );
$pricing  = get_post_meta( $post_id, 'sdwd_pricing', true );
$street   = get_post_meta( $post_id, 'sdwd_street_address', true );
$city     = get_post_meta( $post_id, 'sdwd_city', true );
$zip      = get_post_meta( $post_id, 'sdwd_zip_code', true );
$capacity = get_post_meta( $post_id, 'sdwd_capacity', true );
$desc     = $post->post_content ?? '';

if ( ! is_array( $social ) || empty( $social ) )  { $social  = [ [ 'label' => '', 'url' => '' ] ]; }
if ( ! is_array( $hours ) )   { $hours   = []; }
if ( ! is_array( $pricing ) || empty( $pricing ) ) { $pricing = [ [ 'name' => '', 'price' => '', 'features' => [] ] ]; }

$days = [
    'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday',
    'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday',
];

get_header();
?>

<div class="dashboard-wrapper">
    <div class="container">
        <h1 class="dash__title"><?php esc_html_e( 'Edit Your Venue Profile', 'sandiegoweddingdirectory' ); ?></h1>
        <div id="sdwd-dashboard-status" class="dash-status"></div>

        <form id="sdwd-dashboard-form" class="dash-form">

            <?php // --- Personal Info --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Personal Info', 'sandiegoweddingdirectory' ); ?></legend>
                <div class="dash-row">
                    <div class="dash-field">
                        <label for="first_name"><?php esc_html_e( 'First Name', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $user->first_name ); ?>">
                    </div>
                    <div class="dash-field">
                        <label for="last_name"><?php esc_html_e( 'Last Name', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $user->last_name ); ?>">
                    </div>
                </div>
            </fieldset>

            <?php // --- Venue Info --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Venue Info', 'sandiegoweddingdirectory' ); ?></legend>
                <div class="dash-field">
                    <label for="sdwd_company_name"><?php esc_html_e( 'Venue Name', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="sdwd_company_name" name="sdwd_company_name" value="<?php echo esc_attr( $company ); ?>">
                </div>
                <div class="dash-row">
                    <div class="dash-field">
                        <label for="sdwd_email"><?php esc_html_e( 'Email', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="email" id="sdwd_email" name="sdwd_email" value="<?php echo esc_attr( $email ); ?>">
                    </div>
                    <div class="dash-field">
                        <label for="sdwd_phone"><?php esc_html_e( 'Phone', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $phone ); ?>">
                    </div>
                </div>
                <div class="dash-field">
                    <label for="sdwd_company_website"><?php esc_html_e( 'Website', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="url" id="sdwd_company_website" name="sdwd_company_website" value="<?php echo esc_attr( $website ); ?>">
                </div>
            </fieldset>

            <?php // --- Address + Capacity (venue-specific, P2-PARITY-03) --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Location & Capacity', 'sandiegoweddingdirectory' ); ?></legend>
                <div class="dash-field">
                    <label for="sdwd_street_address"><?php esc_html_e( 'Street Address', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="sdwd_street_address" name="sdwd_street_address" value="<?php echo esc_attr( $street ); ?>">
                </div>
                <div class="dash-row">
                    <div class="dash-field">
                        <label for="sdwd_city"><?php esc_html_e( 'City', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="text" id="sdwd_city" name="sdwd_city" value="<?php echo esc_attr( $city ); ?>">
                    </div>
                    <div class="dash-field">
                        <label for="sdwd_zip_code"><?php esc_html_e( 'ZIP Code', 'sandiegoweddingdirectory' ); ?></label>
                        <input type="text" id="sdwd_zip_code" name="sdwd_zip_code" value="<?php echo esc_attr( $zip ); ?>" inputmode="numeric" pattern="[0-9]{5}(-[0-9]{4})?">
                    </div>
                </div>
                <div class="dash-field">
                    <label for="sdwd_capacity"><?php esc_html_e( 'Max Capacity (guests)', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="number" id="sdwd_capacity" name="sdwd_capacity" value="<?php echo esc_attr( $capacity ); ?>" min="0" step="1" placeholder="<?php esc_attr_e( 'e.g. 200', 'sandiegoweddingdirectory' ); ?>">
                </div>
            </fieldset>

            <?php // --- Description --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Description', 'sandiegoweddingdirectory' ); ?></legend>
                <div class="dash-field">
                    <textarea id="sdwd_description" name="sdwd_description" rows="8"><?php echo esc_textarea( $desc ); ?></textarea>
                </div>
            </fieldset>

            <?php // --- Social Media --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Social Media', 'sandiegoweddingdirectory' ); ?></legend>
                <div class="dash-social__items">
                    <?php foreach ( $social as $i => $row ) : ?>
                        <div class="dash-social__row dash-row">
                            <div class="dash-field">
                                <input type="text" name="sdwd_social[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $row['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. Instagram', 'sandiegoweddingdirectory' ); ?>">
                            </div>
                            <div class="dash-field">
                                <input type="url" name="sdwd_social[<?php echo $i; ?>][url]" value="<?php echo esc_attr( $row['url'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'https://', 'sandiegoweddingdirectory' ); ?>">
                            </div>
                            <button type="button" class="btn btn--outline dash-social__remove">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn--outline dash-social__add"><?php esc_html_e( '+ Add Social Link', 'sandiegoweddingdirectory' ); ?></button>
            </fieldset>

            <?php // --- Business Hours --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Business Hours', 'sandiegoweddingdirectory' ); ?></legend>
                <?php foreach ( $days as $key => $label ) :
                    $d = $hours[ $key ] ?? [];
                ?>
                    <div class="dash-hours-row">
                        <span class="dash-hours-row__day"><?php echo esc_html( $label ); ?></span>
                        <input type="time" name="sdwd_hours[<?php echo esc_attr( $key ); ?>][open]" value="<?php echo esc_attr( $d['open'] ?? '' ); ?>">
                        <span>&ndash;</span>
                        <input type="time" name="sdwd_hours[<?php echo esc_attr( $key ); ?>][close]" value="<?php echo esc_attr( $d['close'] ?? '' ); ?>">
                        <label class="dash-hours-row__closed">
                            <input type="checkbox" name="sdwd_hours[<?php echo esc_attr( $key ); ?>][closed]" value="1" <?php checked( ! empty( $d['closed'] ) ); ?>>
                            <?php esc_html_e( 'Closed', 'sandiegoweddingdirectory' ); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </fieldset>

            <?php // --- Pricing --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Pricing Tiers', 'sandiegoweddingdirectory' ); ?></legend>
                <?php foreach ( $pricing as $t => $tier ) : ?>
                    <div class="dash-tier">
                        <h4><?php printf( esc_html__( 'Tier %d', 'sandiegoweddingdirectory' ), $t + 1 ); ?></h4>
                        <div class="dash-row">
                            <div class="dash-field">
                                <label><?php esc_html_e( 'Package Name', 'sandiegoweddingdirectory' ); ?></label>
                                <input type="text" name="sdwd_pricing[<?php echo $t; ?>][name]" value="<?php echo esc_attr( $tier['name'] ?? '' ); ?>">
                            </div>
                            <div class="dash-field">
                                <label><?php esc_html_e( 'Price', 'sandiegoweddingdirectory' ); ?></label>
                                <input type="text" name="sdwd_pricing[<?php echo $t; ?>][price]" value="<?php echo esc_attr( $tier['price'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. $500', 'sandiegoweddingdirectory' ); ?>">
                            </div>
                        </div>
                        <div class="dash-field">
                            <label><?php esc_html_e( 'Features', 'sandiegoweddingdirectory' ); ?></label>
                            <?php
                            $features = $tier['features'] ?? [];
                            if ( empty( $features ) ) { $features = [ '' ]; }
                            foreach ( $features as $f ) : ?>
                                <input type="text" name="sdwd_pricing[<?php echo $t; ?>][features][]" value="<?php echo esc_attr( $f ); ?>" placeholder="<?php esc_attr_e( 'Feature included', 'sandiegoweddingdirectory' ); ?>" class="dash-tier__feature">
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </fieldset>

            <?php // --- Password --- ?>
            <fieldset class="dash-section">
                <legend class="dash-section__heading"><?php esc_html_e( 'Change Password', 'sandiegoweddingdirectory' ); ?></legend>
                <div class="dash-field">
                    <label for="sdwd_new_password"><?php esc_html_e( 'New Password', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="password" id="sdwd_new_password" name="sdwd_new_password" autocomplete="new-password">
                    <p class="dash-field__hint"><?php esc_html_e( 'Leave blank to keep current password.', 'sandiegoweddingdirectory' ); ?></p>
                </div>
            </fieldset>

            <button type="submit" class="btn btn--primary dash-submit"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
        </form>
    </div>
</div>

<?php get_footer(); ?>
