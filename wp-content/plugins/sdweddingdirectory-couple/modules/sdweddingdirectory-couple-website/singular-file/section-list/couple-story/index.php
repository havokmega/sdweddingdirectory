<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Story_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Story_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'couple_story' ]     =   [
                                                'scroll'        =>      '40',

                                                'name'          =>      esc_attr__( 'Story', 'sdweddingdirectory-couple-website' ),

                                                'member'        =>      [ __CLASS__, 'member' ],

                                                'menu'          =>      true
                                            ];
            return      $args;
        }

        /**
         *  Couple About Us
         *  ---------------
         */
        public static function member(){

            ?>
            <div class="wide-tb-120 pb-0">
                <div class="container broken-layout">

                    <?php parent:: heading_section( 'couple_story_heading', 'couple_story_description' ); ?>

                    <div class="story-timeline">
                        <div class="dot-top"></div>
                        <div class="dot-bottom"></div>

                        <?php

                            $_story     =   parent:: get_website_meta( 'couple_story' );

                            /**
                             *  Have Story ?
                             *  ------------
                             */
                            if( parent:: _is_array( $_story ) ){

                                $_counter   =   absint( '0' );

                                foreach( $_story as $key => $value ){

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value ); $_counter++;

                                    /**
                                     *  Story
                                     *  -----
                                     */
                                    printf( '<div class="row align-items-center">
                                                <div class="col-md-6 %6$s">
                                                    <div class="timeline-box %7$s">
                                                        <div class="date">
                                                            %1$s<span>%2$s</span>
                                                        </div>
                                                        <div class="head-img">
                                                            <img src="%3$s" alt="%4$s">
                                                            <h3>%4$s</h3>
                                                        </div>
                                                        <p>%5$s</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="heart-icon">
                                                        <i class="sdweddingdirectory-ballon-heart"></i>
                                                    </div>
                                                </div>
                                            </div>', 

                                            /**
                                             *  1. Date
                                             *  -------
                                             */
                                            esc_attr( date( 'd', strtotime( $story_date ) ) ),

                                            /**
                                             *  2. Date
                                             *  -------
                                             */
                                            esc_attr( date( 'M', strtotime( $story_date ) ) ),

                                            /**
                                             *  3. Image
                                             *  --------
                                             */
                                            parent:: _have_media( $story_image )

                                            ?       esc_url( parent:: media_id_to_get_src( [ 'media_id' => $story_image ] ) )

                                            :       '',

                                            /**
                                             *  4. Story Title
                                             *  --------------
                                             */
                                            esc_attr( $story_title ),

                                            /**
                                             *  5. Story Content
                                             *  ----------------
                                             */
                                            esc_attr( $story_overview ),

                                            /**
                                             *  6. Is Binary ?
                                             *  --------------
                                             */
                                            $_counter % absint( '2' ) == absint( '0' )

                                            ?   sanitize_html_class( 'order-md-last' )

                                            :   '',

                                            /**
                                             *  7. Is Binary ?
                                             *  --------------
                                             */
                                            $_counter % absint( '2' ) == absint( '0' )

                                            ?   sanitize_html_class( 'right-box' )

                                            :   sanitize_html_class( 'left-box' )
                                    );
                                }
                            }

                        ?>
                    </div>
                    <div class="story-blockquote">
                        <div class="row">
                            <div class="col-lg-9 mx-auto">
                                <?php

                                    printf( '<blockquote>%1$s<footer><cite title="Source Title">%2$s</cite></footer></blockquote>', 

                                        /**
                                         *  1. Content
                                         *  ----------
                                         */
                                        esc_attr( parent:: get_website_meta( 'couple_counter_heading' ) ),
                                        /**
                                         *  2. Name
                                         *  -------
                                         */
                                        esc_attr( parent:: get_website_meta( 'couple_counter_description' ) )
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

                $wedding_date   =   esc_attr( parent:: get_website_meta( 'couple_counter_date' ) );

                $wedding_days   =   esc_attr__( 'Days','sdweddingdirectory-couple-website' );

                $wedding_hours  =   esc_attr__( 'Hours','sdweddingdirectory-couple-website' );

                $wedding_min    =   esc_attr__( 'Minutes','sdweddingdirectory-couple-website' );

                $wedding_sec    =   esc_attr__( 'Seconds', 'sdweddingdirectory-couple-website' );

                $wedding_msg    =   esc_attr__( 'Happily Married 🎉', 'sdweddingdirectory-couple-website' );

                /**
                 *  Header Image
                 *  ------------
                 */
                $just_married   =   parent:: media_id_to_get_src( array(

                                        'media_id'      =>  absint( parent:: get_website_meta( 'couple_counter_image' ) ),

                                        'size'          =>  esc_attr( 'sdweddingdirectory_img_1500x500' ),

                                        'placeholder'   =>  esc_attr( 'web-layout-1-counter' )

                                    ) );
                /**
                 *  CountDown
                 *  ---------
                 */
                printf(    '<div class="bg-countdown bg-dark couple-counter" style="background: url(%8$s) no-repeat center center;">

                                <div class="container">

                                    <ul id="wedding-countdown" 

                                        class="list-unstyled list-inline" 

                                        data-wedding-date  = "%2$s"

                                        data-wedding-days  = "%3$s"

                                        data-wedding-hours = "%4$s"

                                        data-wedding-min   = "%5$s"

                                        data-wedding-sec   = "%6$s"

                                        data-wedding-msg   = "%7$s" >

                                            <li class="list-inline-item">

                                                <span class="days"> %1$s </span> 

                                                <div class="days_text"> %3$s </div>

                                            </li>

                                            <li class="list-inline-item">

                                                <span class="hours"> %1$s </span>

                                                <div class="hours_text"> %4$s </div>

                                            </li>

                                            <li class="list-inline-item">

                                                <span class="minutes">%1$s</span>

                                                <div class="minutes_text"> %5$s </div>

                                            </li>

                                            <li class="list-inline-item">

                                                <span class="seconds"> %1$s </span>

                                                <div class="seconds_text"> %6$s </div>

                                            </li>
                                    </ul>

                                </div>

                            </div>',

                            /**
                             *  1. By Default Time Show *00*
                             *  ----------------------------
                             */
                            esc_attr( '00' ),

                            /**
                             *  2. Wedding : wedding_date
                             *  -------------------------
                             */
                            esc_attr( $wedding_date ),

                            /**
                             *  3. Wedding : wedding_days
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_days ),

                            /**
                             *  4. Wedding : wedding_hour
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_hours ),

                            /**
                             *  5. Wedding : wedding_min 
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_min ),

                            /**
                             *  6. Wedding : wedding_sec 
                             *  -------------------------
                             */                        
                            esc_attr( $wedding_sec ),

                            /**
                             *  7. Wedding : wedding_msg 
                             *  ------------------------
                             */                        
                            esc_attr( $wedding_msg ),

                            /**
                             *  8. Background Image
                             *  -------------------
                             */
                            esc_url( $just_married )
                        );
                ?>

            </div>

            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Story_Section::get_instance();
}