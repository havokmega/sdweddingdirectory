<?php
/**
 * Template Name: Wedding Planning
 *
 * Orchestrator for the wedding planning page.
 */

get_header();
?>

<div class="planning">
    <!-- Scroll-triggered sticky CTA bar (hidden until scroll) -->
    <div class="planning-scroll-cta" id="planning-scroll-cta" aria-hidden="true">
        <div class="container planning-scroll-cta__inner">
            <span class="planning-scroll-cta__label"><?php esc_html_e( 'Make planning your wedding simple', 'sandiegoweddingdirectory' ); ?></span>
            <a class="planning-scroll-cta__btn" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>">
                <?php esc_html_e( "Sign Up. It's free!", 'sandiegoweddingdirectory' ); ?>
            </a>
        </div>
    </div>

    <?php
    get_template_part( 'template-parts/planning/planning-hero' );
    get_template_part( 'template-parts/planning/planning-intro' );
    get_template_part( 'template-parts/planning/planning-checklist' );
    get_template_part( 'template-parts/planning/planning-vendor-organizer' );
    get_template_part( 'template-parts/planning/planning-wedding-website' );
    get_template_part( 'template-parts/planning/planning-secondary-intro' );
    get_template_part( 'template-parts/planning/planning-tool-cards' );
    get_template_part( 'template-parts/planning/planning-detailed-copy' );
    get_template_part( 'template-parts/planning/planning-faq' );
    ?>
</div>

<script>
(function () {
    var bar = document.getElementById('planning-scroll-cta');
    if (!bar) return;

    var threshold = 300;
    var visible = false;

    window.addEventListener('scroll', function () {
        var shouldShow = window.scrollY > threshold;
        if (shouldShow === visible) return;
        visible = shouldShow;
        bar.classList.toggle('is-visible', visible);
        bar.setAttribute('aria-hidden', visible ? 'false' : 'true');
    }, { passive: true });
})();
</script>

<?php
get_footer();
