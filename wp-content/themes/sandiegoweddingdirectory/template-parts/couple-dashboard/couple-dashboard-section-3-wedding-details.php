<?php
/**
 * Couple Dashboard — Section 3: Wedding Details
 *
 * Inline form for editing the main wedding data. Submits via the existing
 * `sdwd_save_dashboard` AJAX handler (SDWD Core plugin).
 *
 * $args:
 *   - first_name   (string)
 *   - last_name    (string)
 *   - email        (string)
 *   - phone        (string)
 *   - wedding_date (string ISO yyyy-mm-dd)
 */

$first_name   = $args['first_name']   ?? '';
$last_name    = $args['last_name']    ?? '';
$email        = $args['email']        ?? '';
$phone        = $args['phone']        ?? '';
$wedding_date = $args['wedding_date'] ?? '';
?>

<section class="cd-card cd-wedding-details">
    <div class="cd-card__head">
        <h2 class="cd-card__title"><?php esc_html_e( 'Wedding Details', 'sdweddingdirectory' ); ?></h2>
    </div>

    <form id="sdwd-dashboard-form" class="cd-form">
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="first_name"><?php esc_html_e( 'First Name', 'sdweddingdirectory' ); ?></label>
                <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $first_name ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="last_name"><?php esc_html_e( 'Last Name', 'sdweddingdirectory' ); ?></label>
                <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $last_name ); ?>">
            </div>
        </div>

        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_email"><?php esc_html_e( 'Email', 'sdweddingdirectory' ); ?></label>
                <input type="email" id="sdwd_email" name="sdwd_email" value="<?php echo esc_attr( $email ); ?>">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_phone"><?php esc_html_e( 'Phone', 'sdweddingdirectory' ); ?></label>
                <input type="tel" id="sdwd_phone" name="sdwd_phone" value="<?php echo esc_attr( $phone ); ?>">
            </div>
        </div>

        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_wedding_date"><?php esc_html_e( 'Wedding Date', 'sdweddingdirectory' ); ?></label>
                <input type="date" id="sdwd_wedding_date" name="sdwd_wedding_date" value="<?php echo esc_attr( $wedding_date ); ?>">
            </div>
        </div>

        <div class="cd-form__actions">
            <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sdweddingdirectory' ); ?></button>
        </div>

        <div id="sdwd-dashboard-status" class="dash-status cd-form__status" aria-live="polite"></div>
    </form>
</section>
