<?php
/**
 * Vendor Quote Requests — Shell
 *
 * Spec s4: inbox of leads from couples.
 *
 * $args:
 *   - quotes (array of [ id, couple, event_date, message, email, phone, status, submitted ])
 */

$quotes = $args['quotes'] ?? [];
if ( ! is_array( $quotes ) ) { $quotes = []; }
$active_id = isset( $_GET['q'] ) ? sanitize_key( $_GET['q'] ) : '';

$counts = [ 'new' => 0, 'replied' => 0 ];
foreach ( $quotes as $q ) {
    $s = $q['status'] ?? 'new';
    if ( isset( $counts[ $s ] ) ) { $counts[ $s ]++; }
}

$active = null;
foreach ( $quotes as $q ) {
    if ( ( $q['id'] ?? '' ) === $active_id ) { $active = $q; break; }
}
if ( ! $active && ! empty( $quotes ) ) { $active = $quotes[0]; }
?>

<div class="cd-card vd-quotes">
    <div class="cd-card__head">
        <h1 class="cd-card__title"><?php esc_html_e( 'Quote Requests', 'sandiegoweddingdirectory' ); ?></h1>
        <span class="vd-quotes__count">
            <?php
            /* translators: 1: new count, 2: replied count */
            printf( esc_html__( '%1$d new · %2$d replied', 'sandiegoweddingdirectory' ), $counts['new'], $counts['replied'] );
            ?>
        </span>
    </div>

    <?php if ( empty( $quotes ) ) : ?>
        <div class="cd-card__empty">
            <span class="cd-card__empty-title"><?php esc_html_e( 'No quote requests yet', 'sandiegoweddingdirectory' ); ?></span>
            <?php esc_html_e( 'When couples reach out from your profile, their messages land here.', 'sandiegoweddingdirectory' ); ?>
        </div>
    <?php else : ?>
        <div class="vd-quotes__layout">
            <ul class="vd-quotes__list">
                <?php foreach ( $quotes as $q ) :
                    $is_active = ( $active && ( $q['id'] ?? '' ) === ( $active['id'] ?? '' ) );
                    $url = add_query_arg( 'q', $q['id'] ?? '', remove_query_arg( 'q' ) );
                ?>
                    <li class="vd-quotes__item<?php echo $is_active ? ' vd-quotes__item--active' : ''; ?>">
                        <a href="<?php echo esc_url( $url ); ?>" class="vd-quotes__item-link">
                            <span class="vd-quotes__item-couple"><?php echo esc_html( $q['couple'] ?? '' ); ?></span>
                            <span class="vd-quotes__item-meta">
                                <?php
                                if ( ! empty( $q['event_date'] ) ) {
                                    echo esc_html( date_i18n( 'M j, Y', strtotime( $q['event_date'] ) ) );
                                }
                                ?>
                            </span>
                            <?php if ( ( $q['status'] ?? '' ) === 'new' ) : ?>
                                <span class="vd-quotes__item-badge"><?php esc_html_e( 'New', 'sandiegoweddingdirectory' ); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="vd-quotes__detail">
                <?php if ( $active ) : ?>
                    <header class="vd-quotes__detail-head">
                        <h2 class="vd-quotes__detail-name"><?php echo esc_html( $active['couple'] ?? '' ); ?></h2>
                        <p class="vd-quotes__detail-meta">
                            <?php if ( ! empty( $active['event_date'] ) ) : ?>
                                <span><strong><?php esc_html_e( 'Event date:', 'sandiegoweddingdirectory' ); ?></strong> <?php echo esc_html( date_i18n( 'F j, Y', strtotime( $active['event_date'] ) ) ); ?></span>
                            <?php endif; ?>
                            <?php if ( ! empty( $active['email'] ) ) : ?>
                                <span><strong><?php esc_html_e( 'Email:', 'sandiegoweddingdirectory' ); ?></strong> <a href="mailto:<?php echo esc_attr( $active['email'] ); ?>"><?php echo esc_html( $active['email'] ); ?></a></span>
                            <?php endif; ?>
                            <?php if ( ! empty( $active['phone'] ) ) : ?>
                                <span><strong><?php esc_html_e( 'Phone:', 'sandiegoweddingdirectory' ); ?></strong> <?php echo esc_html( $active['phone'] ); ?></span>
                            <?php endif; ?>
                        </p>
                    </header>
                    <blockquote class="vd-quotes__detail-msg"><?php echo nl2br( esc_html( $active['message'] ?? '' ) ); ?></blockquote>

                    <form class="cd-form" data-cd-form="quote-reply">
                        <input type="hidden" name="quote_id" value="<?php echo esc_attr( $active['id'] ?? '' ); ?>">
                        <div class="cd-form__field">
                            <label for="vd_reply"><?php esc_html_e( 'Reply', 'sandiegoweddingdirectory' ); ?></label>
                            <textarea id="vd_reply" name="reply" rows="5" placeholder="<?php esc_attr_e( 'Write your reply…', 'sandiegoweddingdirectory' ); ?>"></textarea>
                        </div>
                        <div class="cd-form__actions">
                            <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Send Reply', 'sandiegoweddingdirectory' ); ?></button>
                            <button type="button" class="btn btn--ghost" data-vd-quote-mark-read><?php esc_html_e( 'Mark as Read', 'sandiegoweddingdirectory' ); ?></button>
                        </div>
                        <div class="cd-form__status" aria-live="polite"></div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
