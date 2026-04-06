<?php
/**
 * Why Use SDWeddingDirectory to message vendors/venues.
 *
 * Detects post type to swap "vendors" / "venues" wording automatically.
 */
$sdwd_why_post_type = get_post_type();
$sdwd_why_label     = ( $sdwd_why_post_type === 'venue' ) ? 'venues' : 'vendors';
?>
<div class="sd-why-use wide-tb-60">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <h3 class="fw-bold"><?php echo esc_html( sprintf( 'Why use SDWeddingDirectory to message %s?', $sdwd_why_label ) ); ?></h3>
        <ul class="sd-why-use-list mt-4">
          <li><i class="fa fa-check"></i> <?php echo esc_html( sprintf( 'Messaging our verified %s on SDWeddingDirectory is complimentary, safe, and secure.', $sdwd_why_label ) ); ?></li>
          <li><i class="fa fa-check"></i> <?php echo esc_html( sprintf( 'Effortlessly manage %s communications and planning details in one centralized location.', str_replace( 's', '', $sdwd_why_label ) ) ); ?></li>
          <li><i class="fa fa-check"></i> <?php esc_html_e( 'Our mobile app helps you stay connected anytime, anywhere.', 'sdweddingdirectory' ); ?></li>
          <li><i class="fa fa-check"></i> <?php echo esc_html( sprintf( 'For customized pricing and package information, messaging %s directly is the fastest path.', $sdwd_why_label ) ); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
