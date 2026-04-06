<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Header_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Header_Section extends SDWeddingDirectory_Couple_Website_Section{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {
          
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Wedding Website - Section 
             *  -------------------------
             */
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'header' ]      =     [
                                                'scroll'        =>      '40',

                                                'name'          =>      esc_attr__( 'Header', 'sdweddingdirectory-couple-website' ),

                                                'member'        =>      [ __CLASS__, 'member' ],

                                                'menu'          =>      false
                                            ];
            return      $args;
        }

        /**
         *  Couple About Us
         *  ---------------
         */
        public static function member(){

            ?>
            <!-- Header -->
            <header itemtype="https://schema.org/WPHeader" itemscope="itemscope" id="masthead">

                <div class="website-template-header-version-one">

                    <!-- Navigation -->
                    <nav class="navbar navbar-expand-md fixed-top header-anim" id="mainNav">
                        <div class="logo-wrap">
                            <?php

                            SDWeddingDirectory_Header:: sdweddingdirectory_brand( array(

                                'layout'          =>  absint( '3' ),

                            ) );

                            ?>
                            <!-- Toggle Button Start -->
                            <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <!-- Toggle Button End -->
                        </div>

                        <?php 

                            /**
                             *  Wedding Website Section
                             *  -----------------------
                             */
                            $wedding_website_section     =   apply_filters( 'sdweddingdirectory/wedding-website/layout-1', [] );

                            /**
                             *  Is Array ?
                             *  ----------
                             */
                            if( parent:: _is_array( $wedding_website_section ) ){

                                ?>
                                <div class="container">

                                    <div class="collapse navbar-collapse justify-content-md-center" id="navbarResponsive">

                                        <ul class="navbar-nav"><?php

                                        foreach( $wedding_website_section as $key => $value ){

                                            /**
                                             *  Extract
                                             *  -------
                                             */
                                            extract( $value );

                                            /**
                                             *  If Header Disply Menu ?
                                             *  -----------------------
                                             */
                                            if( $menu ){

                                                /**
                                                 *  Write Menu
                                                 *  ----------
                                                 */
                                                printf( '<li class="nav-item">
                                                            <a class="nav-link js-scroll-trigger" data-scroll="%3$s" href="#%1$s">%2$s</a>
                                                        </li>', 

                                                        /**
                                                         *  1. ID
                                                         *  -----
                                                         */
                                                        esc_attr( $key ),
                                                        
                                                        /**
                                                         *  2. Name
                                                         *  -------
                                                         */
                                                        esc_attr( $name ),

                                                        /**
                                                         *  3. Scroll
                                                         *  ---------
                                                         */
                                                        absint( $scroll )
                                                );
                                            }
                                        }

                                        ?>

                                        </ul>
                                    </div>
                                </div>

                                <?php
                            }
                        ?>
                    </nav>

                </div>

            </header>
            <!-- /Header -->
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Header_Section::get_instance();
}