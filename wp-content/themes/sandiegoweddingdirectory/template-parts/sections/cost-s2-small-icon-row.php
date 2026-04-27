<?php
/**
 * Cost: 3-up small icon row with centered text.
 */

$items = [
    [
        'icon'  => 'icon-location',
        'title' => __( 'San Diego specific', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'sandiegoweddingdirectory' ),
    ],
    [
        'icon'  => 'icon-money-stack',
        'title' => __( 'Real vendor pricing', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'sandiegoweddingdirectory' ),
    ],
    [
        'icon'  => 'icon-calendar-heart',
        'title' => __( 'Updated regularly', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', 'sandiegoweddingdirectory' ),
    ],
];
?>
<section class="cost-s2-small-icon-row section" aria-label="<?php esc_attr_e( 'Why our cost data', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <ul class="cost-s2-small-icon-row__list">
            <?php foreach ( $items as $item ) : ?>
                <li class="cost-s2-small-icon-row__item">
                    <span class="cost-s2-small-icon-row__icon <?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></span>
                    <h3 class="cost-s2-small-icon-row__title"><?php echo esc_html( $item['title'] ); ?></h3>
                    <p class="cost-s2-small-icon-row__desc"><?php echo esc_html( $item['desc'] ); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
