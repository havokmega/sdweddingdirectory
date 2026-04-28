<?php
/**
 * Vendor My Reviews — Shell
 *
 * Spec s5: vendor reads reviews and can reply.
 *
 * $args:
 *   - reviews (array of WP_Comment with type='review')
 */

$reviews = $args['reviews'] ?? [];
$count   = is_array( $reviews ) || $reviews instanceof Countable ? count( $reviews ) : 0;
$avg     = 0;
if ( $count > 0 ) {
    $sum = 0;
    foreach ( $reviews as $r ) {
        $sum += (float) get_comment_meta( $r->comment_ID, 'sdwd_rating', true );
    }
    $avg = $sum / $count;
}
?>

<div class="cd-card vd-reviews">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'My Reviews', 'sandiegoweddingdirectory' ); ?></h1>
        <span class="vd-reviews__summary">
            <?php if ( $count > 0 ) : ?>
                <strong><?php echo esc_html( number_format( $avg, 1 ) ); ?></strong>
                <span class="vd-reviews__stars" aria-hidden="true">
                    <?php
                    $full = (int) floor( $avg );
                    for ( $i = 1; $i <= 5; $i++ ) {
                        echo $i <= $full ? '★' : '☆';
                    }
                    ?>
                </span>
                <span>(<?php echo (int) $count; ?>)</span>
            <?php else : ?>
                <?php esc_html_e( 'No reviews yet', 'sandiegoweddingdirectory' ); ?>
            <?php endif; ?>
        </span>
    </div>

    <?php if ( $count === 0 ) : ?>
        <div class="cd-card__empty">
            <span class="cd-card__empty-title"><?php esc_html_e( 'No reviews yet', 'sandiegoweddingdirectory' ); ?></span>
            <?php esc_html_e( 'After your wedding work, couples can leave reviews on your profile and they will appear here.', 'sandiegoweddingdirectory' ); ?>
        </div>
    <?php else : ?>
        <ul class="vd-reviews__list">
            <?php foreach ( $reviews as $r ) :
                $rating  = (float) get_comment_meta( $r->comment_ID, 'sdwd_rating', true );
                $title   = get_comment_meta( $r->comment_ID, 'sdwd_review_title', true );
                $reply   = get_comment_meta( $r->comment_ID, 'sdwd_vendor_reply', true );
            ?>
                <li class="c-review-card">
                    <header class="c-review-card__head">
                        <span class="c-review-card__author"><?php echo esc_html( $r->comment_author ); ?></span>
                        <span class="c-review-card__stars" aria-label="<?php
                            /* translators: %s: rating value */
                            printf( esc_attr__( '%s out of 5', 'sandiegoweddingdirectory' ), esc_attr( number_format( $rating, 1 ) ) );
                        ?>">
                            <?php
                            $full = (int) floor( $rating );
                            for ( $i = 1; $i <= 5; $i++ ) {
                                echo $i <= $full ? '★' : '☆';
                            }
                            ?>
                        </span>
                        <span class="c-review-card__date"><?php echo esc_html( get_comment_date( '', $r ) ); ?></span>
                    </header>
                    <?php if ( $title ) : ?>
                        <h3 class="c-review-card__title"><?php echo esc_html( $title ); ?></h3>
                    <?php endif; ?>
                    <p class="c-review-card__body"><?php echo esc_html( $r->comment_content ); ?></p>

                    <?php if ( $reply ) : ?>
                        <div class="c-review-card__reply">
                            <strong><?php esc_html_e( 'Your reply:', 'sandiegoweddingdirectory' ); ?></strong>
                            <p><?php echo esc_html( $reply ); ?></p>
                        </div>
                    <?php else : ?>
                        <details class="c-review-card__reply-form">
                            <summary><?php esc_html_e( 'Reply', 'sandiegoweddingdirectory' ); ?></summary>
                            <form class="cd-form" data-cd-form="vendor-reply">
                                <input type="hidden" name="comment_id" value="<?php echo (int) $r->comment_ID; ?>">
                                <textarea name="reply" rows="3" placeholder="<?php esc_attr_e( 'Thanks for the review…', 'sandiegoweddingdirectory' ); ?>"></textarea>
                                <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Post Reply', 'sandiegoweddingdirectory' ); ?></button>
                                <div class="cd-form__status" aria-live="polite"></div>
                            </form>
                        </details>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
