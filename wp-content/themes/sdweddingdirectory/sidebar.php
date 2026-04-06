<?php
/**
 *   SDWeddingDirectory - The sidebar containing the main widget area
 *   --------------------------------------------------------
 */

$sidebar   =   esc_attr( SDWeddingDirectory_Grid_Managment:: sdweddingdirectory_sidebar() );

/**
 *  Have Data ?
 *  -----------
 */
if( SDWeddingDirectory:: _have_data( $sidebar ) ){  ?>

    <aside itemtype="https://schema.org/WPSideBar" itemscope="itemscope" <?php do_action( 'sdweddingdirectory_get_sidebar_class' ); ?> id="secondary">
        <?php 

            /**
             *  Load Sidebar Widget
             *  -------------------
             */
            dynamic_sidebar( 

                /**
                 *  Widget ID
                 *  ---------
                 */
                esc_attr( $sidebar )
            ); 

        ?>
    </aside>
    <?php
}