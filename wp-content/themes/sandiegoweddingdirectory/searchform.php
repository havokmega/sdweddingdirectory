<?php
/**
 * Search Form
 */
?>
<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="search-field"><?php esc_html_e( 'Search for:', 'sandiegoweddingdirectory' ); ?></label>
    <input class="search-form__input" id="search-field" type="search" name="s" placeholder="<?php esc_attr_e( 'Search...', 'sandiegoweddingdirectory' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
    <button class="search-form__submit btn btn--primary" type="submit">
        <span class="icon-search" aria-hidden="true"></span>
        <span class="screen-reader-text"><?php esc_html_e( 'Search', 'sandiegoweddingdirectory' ); ?></span>
    </button>
</form>
