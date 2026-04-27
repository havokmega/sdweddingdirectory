<?php
/**
 * Cost: Dark full-bleed hero — centered title + inline search.
 */
?>
<section class="cost-s1-dark-hero" aria-label="<?php esc_attr_e( 'Wedding cost search', 'sandiegoweddingdirectory' ); ?>">
    <div class="container cost-s1-dark-hero__inner">
        <h1 class="cost-s1-dark-hero__title"><?php esc_html_e( 'Real San Diego wedding costs, all in one place', 'sandiegoweddingdirectory' ); ?></h1>
        <p class="cost-s1-dark-hero__desc"><?php esc_html_e( 'Compare average prices across every wedding category in San Diego — sourced from local vendors and real couples.', 'sandiegoweddingdirectory' ); ?></p>

        <form class="cost-s1-dark-hero__search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
            <label class="screen-reader-text" for="cost-search"><?php esc_html_e( 'Search wedding categories', 'sandiegoweddingdirectory' ); ?></label>
            <span class="cost-s1-dark-hero__search-icon icon-search" aria-hidden="true"></span>
            <input id="cost-search" class="cost-s1-dark-hero__search-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search categories — photographer, catering, florist...', 'sandiegoweddingdirectory' ); ?>" autocomplete="off">
            <button class="btn btn--primary cost-s1-dark-hero__search-submit" type="submit"><?php esc_html_e( 'Search', 'sandiegoweddingdirectory' ); ?></button>
        </form>
    </div>
</section>
