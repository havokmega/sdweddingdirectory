<?php
/**
 * Single Website Template
 *
 * Thin wrapper that delegates rendering to the plugin via action hook.
 * Note: plugin handler may not exist yet.
 */

get_header();
?>

<div class="container section">
    <?php do_action( 'sdweddingdirectory/website/detail-page' ); ?>
</div>

<?php
get_footer();
