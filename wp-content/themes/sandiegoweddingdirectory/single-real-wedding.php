<?php
/**
 * Single Real Wedding Template
 *
 * Thin wrapper that delegates rendering to the plugin via action hook.
 */

get_header();
?>

<div class="container section">
    <?php do_action( 'sdweddingdirectory/real-wedding/detail-page' ); ?>
</div>

<?php
get_footer();
