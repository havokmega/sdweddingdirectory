<?php
/**
 * Vendor Business Hours — Shell
 *
 * Spec s3: per-day open/closed/24h with open and close times.
 *
 * $args:
 *   - hours (array keyed by day with [ open, close, closed, twentyfour ])
 */

$hours = $args['hours'] ?? [];
if ( ! is_array( $hours ) ) { $hours = []; }

$days = [
    'monday'    => __( 'Monday',    'sandiegoweddingdirectory' ),
    'tuesday'   => __( 'Tuesday',   'sandiegoweddingdirectory' ),
    'wednesday' => __( 'Wednesday', 'sandiegoweddingdirectory' ),
    'thursday'  => __( 'Thursday',  'sandiegoweddingdirectory' ),
    'friday'    => __( 'Friday',    'sandiegoweddingdirectory' ),
    'saturday'  => __( 'Saturday',  'sandiegoweddingdirectory' ),
    'sunday'    => __( 'Sunday',    'sandiegoweddingdirectory' ),
];
?>

<div class="cd-card vd-hours">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Business Hours', 'sandiegoweddingdirectory' ); ?></h1>
    </div>

    <form class="cd-form" data-cd-form="hours">
        <div class="vd-hours__grid">
            <?php foreach ( $days as $key => $label ) :
                $row = $hours[ $key ] ?? [ 'open' => '09:00', 'close' => '17:00', 'closed' => false, 'twentyfour' => false ];
                $closed     = ! empty( $row['closed'] );
                $is_24      = ! empty( $row['twentyfour'] );
            ?>
                <div class="vd-hours__row" data-vd-day="<?php echo esc_attr( $key ); ?>">
                    <span class="vd-hours__day"><?php echo esc_html( $label ); ?></span>
                    <label class="vd-hours__toggle">
                        <input type="checkbox" name="hours[<?php echo esc_attr( $key ); ?>][closed]" value="1" <?php checked( $closed ); ?>>
                        <span><?php esc_html_e( 'Closed', 'sandiegoweddingdirectory' ); ?></span>
                    </label>
                    <label class="vd-hours__toggle">
                        <input type="checkbox" name="hours[<?php echo esc_attr( $key ); ?>][twentyfour]" value="1" <?php checked( $is_24 ); ?>>
                        <span><?php esc_html_e( '24 hours', 'sandiegoweddingdirectory' ); ?></span>
                    </label>
                    <label class="vd-hours__time">
                        <span class="screen-reader-text"><?php esc_html_e( 'Open', 'sandiegoweddingdirectory' ); ?></span>
                        <input type="time" name="hours[<?php echo esc_attr( $key ); ?>][open]" value="<?php echo esc_attr( $row['open'] ?? '' ); ?>" <?php echo $closed || $is_24 ? 'disabled' : ''; ?>>
                    </label>
                    <span class="vd-hours__sep">–</span>
                    <label class="vd-hours__time">
                        <span class="screen-reader-text"><?php esc_html_e( 'Close', 'sandiegoweddingdirectory' ); ?></span>
                        <input type="time" name="hours[<?php echo esc_attr( $key ); ?>][close]" value="<?php echo esc_attr( $row['close'] ?? '' ); ?>" <?php echo $closed || $is_24 ? 'disabled' : ''; ?>>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cd-form__actions">
            <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Save Hours', 'sandiegoweddingdirectory' ); ?></button>
        </div>
        <div class="cd-form__status" aria-live="polite"></div>
    </form>
</div>
