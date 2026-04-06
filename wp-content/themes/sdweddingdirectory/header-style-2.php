<?php
/**
 *   -------------------------------
 *   SDWeddingDirectory - Header Version Two
 *   -------------------------------  
 */
?><header itemtype="https://schema.org/WPHeader" itemscope="itemscope" id="masthead">

    <!-- Main Navigation Start -->
    <div class="header-version-two">
        <nav class="header-anim navbar navbar-expand-xl">
            <div class="container text-nowrap bdr-nav px-0">

                <?php

                    /**
                     *    Load SDWeddingDirectory - Header Data
                     *    -----------------------------
                     */
                    do_action( 'sdweddingdirectory_header_version', array(

                        'layout'        =>      absint( '2' )

                    ) );

                ?>

            </div>
        </nav>
    </div>
    <!-- Main Navigation End -->

</header><!-- #masthead -->
