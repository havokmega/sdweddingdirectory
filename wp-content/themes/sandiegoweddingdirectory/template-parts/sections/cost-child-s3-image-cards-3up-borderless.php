<?php
/**
 * Cost Child: 3-up image cards, borderless.
 */

$cards = [
    [
        'image' => 'cost-image-blank.png',
        'title' => __( 'Average price', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'sandiegoweddingdirectory' ),
    ],
    [
        'image' => 'cost-image-blank.png',
        'title' => __( 'Typical range', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Sed do eiusmod tempor incididunt ut labore et dolore magna.', 'sandiegoweddingdirectory' ),
    ],
    [
        'image' => 'cost-image-blank.png',
        'title' => __( 'What\'s included', 'sandiegoweddingdirectory' ),
        'desc'  => __( 'Ut enim ad minim veniam, quis nostrud exercitation ullamco.', 'sandiegoweddingdirectory' ),
    ],
];

$image_base = get_template_directory_uri() . '/assets/images/pages/';
?>
<section class="cost-child-s3-image-cards-3up-borderless section" aria-label="<?php esc_attr_e( 'Cost breakdown', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="cost-child-s3-image-cards-3up-borderless__header">
            <h2 class="cost-child-s3-image-cards-3up-borderless__heading"><?php esc_html_e( 'At a glance', 'sandiegoweddingdirectory' ); ?></h2>
        </div>
        <ul class="cost-child-s3-image-cards-3up-borderless__list">
            <?php foreach ( $cards as $card ) : ?>
                <li class="cost-child-s3-image-cards-3up-borderless__item">
                    <div class="cost-child-s3-image-cards-3up-borderless__media">
                        <img class="cost-child-s3-image-cards-3up-borderless__image" src="<?php echo esc_url( $image_base . $card['image'] ); ?>" alt="<?php echo esc_attr( $card['title'] ); ?>" loading="lazy">
                    </div>
                    <h3 class="cost-child-s3-image-cards-3up-borderless__title"><?php echo esc_html( $card['title'] ); ?></h3>
                    <p class="cost-child-s3-image-cards-3up-borderless__desc"><?php echo esc_html( $card['desc'] ); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
