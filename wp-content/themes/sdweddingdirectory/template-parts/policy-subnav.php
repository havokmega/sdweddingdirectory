<?php
/**
 *  Policy Sub-Navigation
 *  ---------------------
 *  Horizontal tab bar linking Privacy Policy, CA Privacy, Terms of Use, FAQ.
 *  Active tab gets a teal underline accent.
 */

$policy_tabs = [
    'privacy-policy' => 'Privacy Policy',
    'ca-privacy'     => 'CA Privacy',
    'terms-of-use'   => 'Terms of Use',
    'faqs'           => 'FAQ',
];

$current_slug = '';
if ( is_page() ) {
    $current_slug = get_post_field( 'post_name', get_queried_object_id() );
}
?>

<nav class="sd-policy-subnav" aria-label="<?php esc_attr_e( 'Policy navigation', 'sdweddingdirectory' ); ?>">
    <div class="container">
        <ul class="sd-policy-subnav__list">
            <?php foreach ( $policy_tabs as $slug => $label ) :
                $url       = home_url( '/' . $slug . '/' );
                $is_active = ( $slug === $current_slug );
            ?>
                <li class="sd-policy-subnav__item<?php echo $is_active ? ' sd-policy-subnav__item--active' : ''; ?>">
                    <?php if ( $is_active ) : ?>
                        <span class="sd-policy-subnav__link sd-policy-subnav__link--active"><?php echo esc_html( $label ); ?></span>
                    <?php else : ?>
                        <a class="sd-policy-subnav__link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
