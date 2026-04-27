<?php
/**
 * Registry: Logo Grid — top 4 retailer logo cards.
 */

$retailers = [
    [ 'name' => 'Amazon',         'url' => 'https://www.amazon.com/wedding/home',                       'logo' => 'amazon.svg'            ],
    [ 'name' => 'Target',         'url' => 'https://www.target.com/gift-registry/create-wedding-registry', 'logo' => 'target.svg'            ],
    [ 'name' => 'Joy',            'url' => 'https://www.travelersjoy.com/register/',                    'logo' => 'travelers-joy.svg'     ],
    [ 'name' => 'Crate & Barrel', 'url' => 'https://www.crateandbarrel.com/gift-registry/create/step1', 'logo' => 'CrateandBarrelLogo.png' ],
];

$theme_uri      = get_template_directory_uri();
$placeholder_img = $theme_uri . '/assets/images/pages/target.png';
?>
<section class="registry-s2-logo-grid section" aria-label="<?php esc_attr_e( 'Featured retailers', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="registry-s2-logo-grid__header">
            <h2 class="registry-s2-logo-grid__heading"><?php esc_html_e( 'Featured retailers', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="registry-s2-logo-grid__desc"><?php esc_html_e( 'Start with one of the top picks couples are linking to right now.', 'sandiegoweddingdirectory' ); ?></p>
        </div>

        <ul class="registry-s2-logo-grid__list">
            <?php foreach ( $retailers as $retailer ) :
                $logo_src   = $theme_uri . '/assets/images/logo/' . $retailer['logo'];
                $logo_class = 'registry-s2-logo-grid__logo';

                if ( 'Crate & Barrel' === $retailer['name'] ) {
                    $logo_class .= ' registry-s2-logo-grid__logo--crateandbarrel';
                }
                ?>
                <li class="registry-s2-logo-grid__item">
                    <a class="registry-s2-logo-grid__card" href="<?php echo esc_url( $retailer['url'] ); ?>" target="_blank" rel="noopener nofollow">
                        <span class="registry-s2-logo-grid__media">
                            <img class="registry-s2-logo-grid__image" src="<?php echo esc_url( $placeholder_img ); ?>" alt="" loading="lazy">
                        </span>
                        <span class="registry-s2-logo-grid__logo-zone">
                            <img class="<?php echo esc_attr( $logo_class ); ?>" src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( $retailer['name'] ); ?>" loading="lazy">
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="registry-s2-logo-grid__cta">
            <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/wedding-registry/retail-registries/' ) ); ?>"><?php esc_html_e( 'All Retailers', 'sandiegoweddingdirectory' ); ?></a>
        </div>
    </div>
</section>
