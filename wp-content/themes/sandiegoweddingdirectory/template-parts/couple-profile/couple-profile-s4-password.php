<?php
/**
 * Couple Profile — s4 Password
 *
 * Old password + new + confirm. AJAX handler in sdwd-couple plugin verifies
 * old password before updating.
 */
?>

<form class="cd-form" data-cd-form="password">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Change password', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="cd_old_password"><?php esc_html_e( 'Current password', 'sandiegoweddingdirectory' ); ?></label>
                <input type="password" id="cd_old_password" name="old_password" autocomplete="current-password">
            </div>
        </div>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="cd_new_password"><?php esc_html_e( 'New password', 'sandiegoweddingdirectory' ); ?></label>
                <input type="password" id="cd_new_password" name="new_password" autocomplete="new-password">
            </div>
            <div class="cd-form__field">
                <label for="cd_confirm_password"><?php esc_html_e( 'Confirm new password', 'sandiegoweddingdirectory' ); ?></label>
                <input type="password" id="cd_confirm_password" name="confirm_password" autocomplete="new-password">
            </div>
        </div>
        <p class="cd-form__hint"><?php esc_html_e( 'Minimum 8 characters.', 'sandiegoweddingdirectory' ); ?></p>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Update password', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
