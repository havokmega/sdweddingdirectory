<?php
/**
 * Couple Profile — s3 Wedding Info
 *
 * Wedding date + the 5 attributes shown in the dashboard glance strip
 * (Color / Season / Style / Designer / Honeymoon).
 *
 * $args:
 *   - post_id (int)
 */

$post_id = (int) ( $args['post_id'] ?? 0 );

$wedding_date  = get_post_meta( $post_id, 'sdwd_wedding_date', true );
$color         = get_post_meta( $post_id, 'sdwd_wedding_color', true );
$season        = get_post_meta( $post_id, 'sdwd_wedding_season', true );
$style         = get_post_meta( $post_id, 'sdwd_wedding_style', true );
$attire        = get_post_meta( $post_id, 'sdwd_wedding_attire', true );
$honeymoon     = get_post_meta( $post_id, 'sdwd_wedding_honeymoon', true );
?>

<form class="cd-form" data-cd-form="wedding-info">
    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Date', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_wedding_date"><?php esc_html_e( 'Wedding Date', 'sandiegoweddingdirectory' ); ?></label>
                <input type="date" id="sdwd_wedding_date" name="sdwd_wedding_date" value="<?php echo esc_attr( $wedding_date ); ?>">
            </div>
        </div>
    </fieldset>

    <fieldset class="cd-form__group">
        <legend class="cd-form__legend"><?php esc_html_e( 'Wedding Details', 'sandiegoweddingdirectory' ); ?></legend>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_wedding_color"><?php esc_html_e( 'Color', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_wedding_color" name="sdwd_wedding_color" value="<?php echo esc_attr( $color ); ?>" placeholder="Gold, Blush, Navy…">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_wedding_season"><?php esc_html_e( 'Season', 'sandiegoweddingdirectory' ); ?></label>
                <select id="sdwd_wedding_season" name="sdwd_wedding_season">
                    <option value=""><?php esc_html_e( 'Choose…', 'sandiegoweddingdirectory' ); ?></option>
                    <?php foreach ( [ 'Spring', 'Summer', 'Fall', 'Winter' ] as $opt ) : ?>
                        <option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $season, $opt ); ?>><?php echo esc_html( $opt ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_wedding_style"><?php esc_html_e( 'Style', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_wedding_style" name="sdwd_wedding_style" value="<?php echo esc_attr( $style ); ?>" placeholder="Bohemian, Classic, Modern…">
            </div>
            <div class="cd-form__field">
                <label for="sdwd_wedding_attire"><?php esc_html_e( "Designer's Name", 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_wedding_attire" name="sdwd_wedding_attire" value="<?php echo esc_attr( $attire ); ?>">
            </div>
        </div>
        <div class="cd-form__row">
            <div class="cd-form__field">
                <label for="sdwd_wedding_honeymoon"><?php esc_html_e( 'Honeymoon', 'sandiegoweddingdirectory' ); ?></label>
                <input type="text" id="sdwd_wedding_honeymoon" name="sdwd_wedding_honeymoon" value="<?php echo esc_attr( $honeymoon ); ?>">
            </div>
        </div>
    </fieldset>

    <div class="cd-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Changes', 'sandiegoweddingdirectory' ); ?></button>
    </div>
    <div class="cd-form__status" aria-live="polite"></div>
</form>
