<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Event_Party_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Event_Party_Section extends SDWeddingDirectory_Couple_Website_Section{

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
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'event_party' ]      =   [
                                                'scroll'        =>      '40',

                                                'name'          =>      esc_attr__( 'Event', 'sdweddingdirectory-couple-website' ),

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
            <div class="wide-tb-120">

                <div class="container">

                    <?php parent:: heading_section( 'couple_event_heading', 'couple_event_description' ); ?>

                    <div class="row">

                        <?php

                            $_event     =   parent:: get_website_meta( 'couple_event' );

                            if( parent:: _is_array( $_event ) ){

                                foreach( $_event as $key => $value ){

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value );

                                    /**
                                     *  Load Event
                                     *  ----------
                                     */
                                    printf('<div class="col-md-6">
                                                <div class="d-none sdweddingdirectory_website_events_map_data" id="%8$s">
                                                    <input autocomplete="off" type="hidden" class="latitude" value="%6$s" />
                                                    <input autocomplete="off" type="hidden" class="longitude" value="%7$s" />
                                                    <input autocomplete="off" type="hidden" class="icon" value="%5$s" />
                                                    <input autocomplete="off" type="hidden" class="title" value="%1$s" />
                                                    <input autocomplete="off" type="hidden" class="image" value="%9$s" />
                                                </div>
                                                <div class="when-where-box">
                                                    <div class="place-icon"><i class="%5$s"></i></div>
                                                    <h3>%1$s</h3>
                                                    <p>%2$s</p>
                                                    <div class="img">
                                                        <div class="place-info"> %3$s </div>
                                                        <img src="%4$s" alt="%1$s">
                                                    </div>
                                                </div>
                                            </div>', 

                                            /**
                                             *  1. Event Name
                                             *  -------------
                                             */
                                            esc_attr( $name ),

                                            /**
                                             *  2. Event Content
                                             *  ----------------
                                             */
                                            esc_attr( $content ),

                                            /**
                                             *  3. Event Date
                                             *  -------------
                                             */
                                            esc_attr(

                                                date( 'l - d F Y',

                                                    strtotime(
                                                    
                                                        $date
                                                    )
                                                ),
                                            ),

                                            /**
                                             *  4. Event Content
                                             *  ----------------
                                             */
                                            parent:: media_id_to_get_src( [

                                                'media_id'  =>  $image, 

                                                'size'      =>  esc_attr( 'sdweddingdirectory_img_515x255' ) 

                                            ] ),

                                            /**
                                             *  5. Have icon ?
                                             *  --------------
                                             */
                                            parent:: _have_data( $icon )

                                            ?   esc_attr( $icon )

                                            :   esc_attr( 'sdweddingdirectory-hanging-heart' ),

                                            /**
                                             *  6. Latitude
                                             *  -----------
                                             */
                                            $latitude,

                                            /**
                                             *  7. Longitude
                                             *  ------------
                                             */
                                            $longitude,

                                            /**
                                             *  8. ID
                                             *  -----
                                             */
                                            esc_attr( parent:: _rand() ),

                                            /**
                                             *  9. Event Image
                                             *  --------------
                                             */
                                            parent:: media_id_to_get_src( [

                                                'media_id'  =>  $image, 

                                            ] )
                                    );
                                }
                            }

                        ?>
                    </div>

                </div>

            </div>

            <div id="map_extended" class="location-map-wrap"></div>

            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Event_Party_Section::get_instance();
}