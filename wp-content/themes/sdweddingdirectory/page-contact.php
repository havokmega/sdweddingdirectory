<?php
/**
 *  SDWeddingDirectory - Contact Page
 *  --------------------------------
 */
get_header();
?>

<main id="content" class="site-content">
    <?php if ( have_posts() ) { ?>
        <?php while ( have_posts() ) { the_post(); ?>
            <div class="main-content content wide-tb-90">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <h3 class="txt-default fw-7">Get In Touch</h3>
                                <p>We'd love to hear from you. Reach out to us using the information below.</p>
                            </div>
                        </div>
                        <div class="col-lg-7 mx-auto col-md-8">
                            <div class="sd-contact-info text-center" style="padding: 2rem 0;">
                                <p>
                                    <strong>Email:</strong>
                                    <a href="mailto:info@sdweddingdirectory.com">info@sdweddingdirectory.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</main><!-- #content area end -->

<?php
get_footer();
