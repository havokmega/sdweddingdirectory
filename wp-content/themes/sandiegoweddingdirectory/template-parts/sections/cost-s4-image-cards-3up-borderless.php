<?php
/**
 * Cost: 3-up image cards — image + centered text, borderless.
 */

$cards = [
    [
        'image' => 'cost-image-blank.png',
        'title' => __( 'Average wedding budget', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/cost/' ),
    ],
    [
        'image' => 'cost-image-blank.png',
        'title' => __( 'How to save money', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/cost/' ),
    ],
    [
        'image' => 'cost-image-blank.png',
        'title' => __( 'What drives price up', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', 'sandiegoweddingdirectory' ),
        'url'   => home_url( '/cost/' ),
    ],
];

$image_base = get_template_directory_uri() . '/assets/images/pages/';
?>
<section class="cost-s4-image-cards-3up-borderless section" aria-label="<?php esc_attr_e( 'Cost guides', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="cost-s4-image-cards-3up-borderless__header">
            <h2 class="cost-s4-image-cards-3up-borderless__heading"><?php esc_html_e( 'Plan your spending with confidence', 'sandiegoweddingdirectory' ); ?></h2>
        </div>

        <ul class="cost-s4-image-cards-3up-borderless__list">
            <?php foreach ( $cards as $card ) : ?>
                <li class="cost-s4-image-cards-3up-borderless__item">
                    <a class="cost-s4-image-cards-3up-borderless__card" href="<?php echo esc_url( $card['url'] ); ?>">
                        <div class="cost-s4-image-cards-3up-borderless__media">
                            <img class="cost-s4-image-cards-3up-borderless__image" src="<?php echo esc_url( $image_base . $card['image'] ); ?>" alt="<?php echo esc_attr( $card['title'] ); ?>" loading="lazy">
                        </div>
                        <h3 class="cost-s4-image-cards-3up-borderless__title"><?php echo esc_html( $card['title'] ); ?></h3>
                        <p class="cost-s4-image-cards-3up-borderless__desc"><?php echo esc_html( $card['desc'] ); ?></p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
