<?php
/**
 * Couple My Reviews — Shell
 *
 * Spec s16: vendor list left + review editor right.
 *
 * $args:
 *   - selected_vendor (int post ID)
 *   - user_id (int)
 */

$selected_vendor = (int) ( $args['selected_vendor'] ?? 0 );
$user_id         = (int) ( $args['user_id'] ?? get_current_user_id() );

// MVP: pull vendors marked as "hired" from the user's vendor team.
// Real implementation joins to actual vendor post IDs once the team UI links them.
$vendor_team = get_user_meta( $user_id, 'sdwd_vendor_team', true );
if ( ! is_array( $vendor_team ) ) { $vendor_team = []; }

$reviewable = array_values( array_filter( $vendor_team, fn( $r ) => ! empty( $r['hired'] ) ) );

$category_meta = [
    'cake'           => __( 'Cake',           'sandiegoweddingdirectory' ),
    'florist'        => __( 'Florist',        'sandiegoweddingdirectory' ),
    'music'          => __( 'Music',          'sandiegoweddingdirectory' ),
    'photographer'   => __( 'Photographer',   'sandiegoweddingdirectory' ),
    'videographer'   => __( 'Videographer',   'sandiegoweddingdirectory' ),
    'venue'          => __( 'Venue',          'sandiegoweddingdirectory' ),
    'transportation' => __( 'Transportation', 'sandiegoweddingdirectory' ),
];
?>

<div class="cd-card cd-reviews">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'My Reviews', 'sandiegoweddingdirectory' ); ?></h1>
    </div>

    <div class="cd-reviews__layout">
        <aside class="cd-reviews__list">
            <?php if ( empty( $reviewable ) ) : ?>
                <p class="cd-section__soon"><?php esc_html_e( 'Once you mark a vendor as hired, they will show up here for review.', 'sandiegoweddingdirectory' ); ?></p>
            <?php else : ?>
                <ul class="cd-reviews__items">
                    <?php foreach ( $reviewable as $row ) :
                        $slug  = $row['category'] ?? '';
                        $label = $category_meta[ $slug ] ?? ucfirst( $slug );
                    ?>
                        <li class="cd-reviews__item">
                            <button type="button" class="cd-reviews__item-btn" data-cd-review-target="<?php echo esc_attr( $slug ); ?>">
                                <span class="cd-reviews__item-name"><?php echo esc_html( $label ); ?></span>
                                <span class="cd-reviews__item-cta"><?php esc_html_e( 'Write review', 'sandiegoweddingdirectory' ); ?> &rsaquo;</span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>

        <div class="cd-reviews__editor">
            <h2 class="cd-reviews__editor-title"><?php esc_html_e( 'Write a Review', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="cd-reviews__editor-hint"><?php esc_html_e( 'Choose a vendor on the left to start.', 'sandiegoweddingdirectory' ); ?></p>

            <form class="cd-form cd-review-form" data-cd-form="review" hidden>
                <div class="cd-rating-input" role="radiogroup" aria-label="<?php esc_attr_e( 'Rating', 'sandiegoweddingdirectory' ); ?>">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <label class="cd-rating-input__star">
                            <input type="radio" name="rating" value="<?php echo $i; ?>">
                            <span aria-hidden="true">&#9733;</span>
                        </label>
                    <?php endfor; ?>
                </div>
                <div class="cd-form__field">
                    <label for="cd_review_title"><?php esc_html_e( 'Title', 'sandiegoweddingdirectory' ); ?></label>
                    <input type="text" id="cd_review_title" name="title">
                </div>
                <div class="cd-form__field">
                    <label for="cd_review_body"><?php esc_html_e( 'Your review', 'sandiegoweddingdirectory' ); ?></label>
                    <textarea id="cd_review_body" name="body" rows="6"></textarea>
                </div>
                <div class="cd-form__actions">
                    <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Submit Review', 'sandiegoweddingdirectory' ); ?></button>
                </div>
                <div class="cd-form__status" aria-live="polite"></div>
            </form>
        </div>
    </div>
</div>
