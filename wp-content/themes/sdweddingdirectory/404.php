<?php
/**
 *  ------------------------------------
 *  SDWeddingDirectory - 404 Error Page Template
 *  ------------------------------------
 */

/**
 *  SDWeddingDirectory - Header
 *  -------------------
 */
get_header(); ?>

    <!-- Error Page Start -->
    <div class="main-content content wide-tb-90">

        <div class="container">

            <div class="row">

                <div class="col-lg-7 mx-auto col-md-8">

                    <div class="text-center">
                    <?php

                        /**
                         *  1. 404 Error Content
                         *  --------------------
                         */
                        do_action( 'sdweddingdirectory/404-error/content', [

                            'layout'        =>      absint( '1' )

                        ] );

                    ?>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- Error Page End -->

<?php

/**
 *  SDWeddingDirectory - Footer
 *  -------------------
 */
get_footer();
