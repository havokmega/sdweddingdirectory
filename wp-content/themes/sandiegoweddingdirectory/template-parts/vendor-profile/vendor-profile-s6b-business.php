<?php
/**
 * Vendor Profile — s6b Business Profile
 *
 * Vendors: business name, website, public email, public phone, city, description.
 * Venues:  vendor fields PLUS street address, city, state, zip, lat/lng.
 * Hard rule from spec: vendors NEVER input full address. Venues always do.
 *
 * $args:
 *   - post_id (int)
 *   - is_venue (bool)
 */

$post_id  = (int) ( $args['post_id'] ?? 0 );
$is_venue = ! empty( $args['is_venue'] );
$post     = get_post( $post_id );

$company    = get_post_meta( $post_id, 'sdwd_company_name',    true );
$website    = get_post_meta( $post_id, 'sdwd_company_website', true );
$pub_email  = get_post_meta( $post_id, 'sdwd_email',           true );
$pub_phone  = get_post_meta( $post_id, 'sdwd_phone',           true );
$city       = get_post_meta( $post_id, 'sdwd_city',            true );
$street     = get_post_meta( $post_id, 'sdwd_street_address',  true );
$zip        = get_post_meta( $post_id, 'sdwd_zip_code',        true );
$capacity   = get_post_meta( $post_id, 'sdwd_capacity',        true );
$lat        = get_post_meta( $post_id, 'sdwd_lat',             true );
$lng        = get_post_meta( $post_id, 'sdwd_lng',             true );
$response   = get_post_meta( $post_id, 'sdwd_response_time',   true );
?>

<form class="cd-form" data-cd-form="profile">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Business basics', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_company_name"><?php esc_html_e( 'Business Name', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_company_name" name="sdwd_company_name" value="<?php echo esc_attr( $company ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_company_website"><?php esc_html_e( 'Website', 'sandiegoweddingdirectory' ); ?></label>
                <input type="url" id="sdwd_company_website" name="sdwd_company_website" value="<?php echo esc_attr( $website ); ?>">
            </div>
        </div>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_email"><?php esc_html_e( 'Public Email', 'sandiegoweddingdirectory' ); ?></label>
                <input type="email" id="sdwd_email" name="sdwd_email" value="<?php echo esc_attr( $pub_email ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_phone"><?php esc_html_e( 'Public Phone', 'sandiegoweddingdirectory' ); ?></label>
                <input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $pub_phone ); ?>">
            </div>
        </div>
    </fieldset>

    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Location', 'sandiegoweddingdirectory' ); ?></legend>
        <?php if ( ! $is_venue ) : ?>
            <div class="cd-form__row">
                <div class="cd-form__field">
                    <label for="sdwd_city"><?php esc_html_e( 'City', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="sdwd_city" name="sdwd_city" value="<?php echo esc_attr( $city ); ?>">
                    <p class="cd-form__hint"><?php esc_html_e( 'Vendors serve all of San Diego County. We only show the city for context, never a full address.', 'sandiegoweddingdirectory' ); ?></p>
                </div>
            </div>
        <?php else : ?>
            <div class="cd-form__row">
                <div class="cd-form__field">
                    <label for="sdwd_street_address"><?php esc_html_e( 'Street Address', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="sdwd_street_address" name="sdwd_street_address" value="<?php echo esc_attr( $street ); ?>">
                </div>
                <div class="cd-form__field">
                    <label for="sdwd_city"><?php esc_html_e( 'City', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="sdwd_city" name="sdwd_city" value="<?php echo esc_attr( $city ); ?>">
                </div>
            </div>
            <div class="cd-form__row">
                <div class="cd-form__field">
                    <label for="sdwd_zip_code"><?php esc_html_e( 'ZIP', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="sdwd_zip_code" name="sdwd_zip_code" value="<?php echo esc_attr( $zip ); ?>">
                </div>
                <div class="cd-form__field">
                    <label for="sdwd_capacity"><?php esc_html_e( 'Guest Capacity', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="number" id="sdwd_capacity" name="sdwd_capacity" min="0" value="<?php echo esc_attr( $capacity ); ?>">
                </div>
            </div>
            <div class="cd-form__row">
                <div class="cd-form__field">
                    <label for="sdwd_lat"><?php esc_html_e( 'Latitude', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="number" step="any" id="sdwd_lat" name="sdwd_lat" value="<?php echo esc_attr( $lat ); ?>">
                </div>
                <div class="cd-form__field">
                    <label for="sdwd_lng"><?php esc_html_e( 'Longitude', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="number" step="any" id="sdwd_lng" name="sdwd_lng" value="<?php echo esc_attr( $lng ); ?>">
                </div>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'About', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__field">
            <label for="sdwd_response_time"><?php esc_html_e( 'Typical response time', 'sandiegoweddingdirectory' ); ?></label>
            <input type="text" id="sdwd_response_time" name="sdwd_response_time" value="<?php echo esc_attr( $response ); ?>" placeholder="< 24 hours">
        </div>
        <div class="cd-form__field">
            <label for="sdwd_description"><?php esc_html_e( 'Description', 'sandiegoweddingdirectory' ); ?></label>
            <textarea id="sdwd_description" name="sdwd_description" rows="6"><?php echo esc_textarea( $post ? $post->post_content : '' ); ?></textarea>
        </div>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
