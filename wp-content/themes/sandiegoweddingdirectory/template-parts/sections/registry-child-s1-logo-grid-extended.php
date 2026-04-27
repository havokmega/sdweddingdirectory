<?php
/**
 * Registry Child: Extended retailer grid.
 *
 * Reuses the parent's featured retailer cards for the first row.
 * The "View more" state shows compact logo-only cards below.
 */

$retailers = [
    [ 'name' => 'Amazon',           'url' => 'https://www.amazon.com/wedding/home',                       'logo' => 'amazon.svg' ],
    [ 'name' => 'Target',           'url' => 'https://www.target.com/gift-registry/create-wedding-registry', 'logo' => 'target.svg' ],
    [ 'name' => 'Joy',              'url' => 'https://www.travelersjoy.com/register/',                    'logo' => 'travelers-joy.svg' ],
    [ 'name' => 'Crate & Barrel',   'url' => 'https://www.crateandbarrel.com/gift-registry/create/step1', 'logo' => 'CrateandBarrelLogo.png' ],
    [ 'name' => "Macy's",           'url' => 'https://www.macys.com/registry',                            'logo' => 'macys.svg' ],
    [ 'name' => 'Anthropologie',    'url' => 'https://www.anthropologie.com/gift-registry?q=registry',   'logo' => 'anthropology.svg' ],
    [ 'name' => 'Belk',             'url' => 'https://www.belk.com/registry-guide/',                      'logo' => 'belk.svg' ],
    [ 'name' => "Bloomingdale's",   'url' => 'https://www.bloomingdales.com/registry',                    'logo' => 'bloomingdales.svg' ],
    [ 'name' => "Dillard's",        'url' => 'https://www.dillards.com/registry',                'logo' => 'dillards.svg' ],
    [ 'name' => 'CB2',              'url' => 'https://www.cb2.com/wedding-gift-registry/',       'logo' => 'cb2.svg' ],
    [ 'name' => 'Sur La Table',     'url' => 'https://www.surlatable.com/registry',               'logo' => 'sur-la-table.svg' ],
];

$initial         = 4;
$theme_uri       = get_template_directory_uri();
$placeholder_img = $theme_uri . '/assets/images/pages/target.png';
?>
<section class="registry-child-s1-logo-grid-extended section" aria-label="<?php esc_attr_e( 'All retailers', 'sandiegoweddingdirectory' ); ?>">
    <div class="container">
        <div class="registry-s2-logo-grid__header">
            <h2 class="registry-s2-logo-grid__heading"><?php esc_html_e( 'All retailers', 'sandiegoweddingdirectory' ); ?></h2>
            <p class="registry-s2-logo-grid__desc"><?php esc_html_e( 'Browse every retailer that pairs with your SD Wedding Directory registry.', 'sandiegoweddingdirectory' ); ?></p>
        </div>

        <ul class="registry-s2-logo-grid__list" data-initial="<?php echo esc_attr( $initial ); ?>">
            <?php foreach ( $retailers as $i => $retailer ) :
                $is_compact = $i >= $initial;
                $hidden     = $i >= $initial;
                $logo_src   = $theme_uri . '/assets/images/logo/' . $retailer['logo'];
                $card_class = 'registry-s2-logo-grid__card';
                $logo_class = 'registry-s2-logo-grid__logo';

                if ( $is_compact ) {
                    $card_class .= ' registry-child-s1-logo-grid-extended__card--logo-only';
                }

                if ( 'Crate & Barrel' === $retailer['name'] ) {
                    $logo_class .= ' registry-s2-logo-grid__logo--crateandbarrel';
                }
                ?>
                <li class="registry-s2-logo-grid__item<?php echo $hidden ? ' is-hidden' : ''; ?>"<?php echo $hidden ? ' aria-hidden="true"' : ''; ?>>
                    <a class="<?php echo esc_attr( $card_class ); ?>" href="<?php echo esc_url( $retailer['url'] ); ?>" target="_blank" rel="noopener nofollow">
                        <?php if ( ! $is_compact ) : ?>
                            <span class="registry-s2-logo-grid__media">
                                <img class="registry-s2-logo-grid__image" src="<?php echo esc_url( $placeholder_img ); ?>" alt="" loading="lazy">
                            </span>
                        <?php endif; ?>
                        <span class="registry-s2-logo-grid__logo-zone">
                            <img class="<?php echo esc_attr( $logo_class ); ?>" src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( $retailer['name'] ); ?>" loading="lazy">
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ( count( $retailers ) > $initial ) : ?>
            <div class="registry-child-s1-logo-grid-extended__more">
                <button class="registry-child-s1-logo-grid-extended__toggle" type="button" data-expanded="false">
                    <span class="registry-child-s1-logo-grid-extended__toggle-label-show"><?php esc_html_e( 'View more', 'sandiegoweddingdirectory' ); ?></span>
                    <span class="registry-child-s1-logo-grid-extended__toggle-label-hide"><?php esc_html_e( 'View less', 'sandiegoweddingdirectory' ); ?></span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var btn = document.querySelector('.registry-child-s1-logo-grid-extended__toggle');
    if (!btn) return;
    var list = document.querySelector('.registry-child-s1-logo-grid-extended .registry-s2-logo-grid__list');
    if (!list) return;
    var initial = parseInt(list.getAttribute('data-initial'), 10) || 4;

    btn.addEventListener('click', function () {
        var expanded = btn.getAttribute('data-expanded') === 'true';
        var items = list.querySelectorAll('.registry-s2-logo-grid__item');
        items.forEach(function (item, i) {
            if (i < initial) return;
            if (expanded) {
                item.classList.add('is-hidden');
                item.setAttribute('aria-hidden', 'true');
            } else {
                item.classList.remove('is-hidden');
                item.removeAttribute('aria-hidden');
            }
        });
        btn.setAttribute('data-expanded', expanded ? 'false' : 'true');
    });
})();
</script>
