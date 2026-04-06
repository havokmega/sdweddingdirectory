<?php
/**
 *  ------------------------------
 *  SDWeddingDirectory - Flaticon - Object
 *  ------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Font_Family_Flaticon' ) && class_exists( 'SDWeddingDirectory_Icon_Manager' ) ){

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Flaticon - Object
     *  ------------------------------
     */
    class SDWeddingDirectory_Font_Family_Flaticon extends SDWeddingDirectory_Icon_Manager{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Flaticon Icon Filter
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/font-family/flaticon', function( $args = [] ){

                return  array_merge( $args, self:: icon_collection() );

            }, absint( '10' ) );

            /**
             *  Flaticon Font
             *  -------------
             */
            add_filter( 'sdweddingdirectory_icons_set', function(  $args = [] ){

                return  array_merge(

                            /**
                             *  1. Have Any Another Data ?
                             *  --------------------------
                             */
                            $args,

                            /**
                             *  Collecton of Flaticon Icons
                             *  ---------------------------
                             */
                            apply_filters( 'sdweddingdirectory/font-family/flaticon', [] )
                        );

            }, absint( '20' ), absint( '1' ) );
        }

        /**
         *  Icon Collection
         *  ---------------
         */
        public static function icon_collection(){

            return  array(

                'flaticon-001-gift-1'   =>  'flaticon-001-gift-1',
                'flaticon-002-dove' =>  'flaticon-002-dove',
                'flaticon-003-cupcake'  =>  'flaticon-003-cupcake',
                'flaticon-004-love-1'   =>  'flaticon-004-love-1',
                'flaticon-005-gramophone'   =>  'flaticon-005-gramophone',
                'flaticon-006-video-camera' =>  'flaticon-006-video-camera',
                'flaticon-007-ring' =>  'flaticon-007-ring',
                'flaticon-008-wedding-dress'    =>  'flaticon-008-wedding-dress',
                'flaticon-009-calendar-1'   =>  'flaticon-009-calendar-1',
                'flaticon-010-gender-symbol'    =>  'flaticon-010-gender-symbol',
                'flaticon-011-wedding-car'  =>  'flaticon-011-wedding-car',
                'flaticon-012-bell' =>  'flaticon-012-bell',
                'flaticon-013-money'    =>  'flaticon-013-money',
                'flaticon-014-lock' =>  'flaticon-014-lock',
                'flaticon-015-love-key' =>  'flaticon-015-love-key',
                'flaticon-016-gift' =>  'flaticon-016-gift',
                'flaticon-017-wedding-ring' =>  'flaticon-017-wedding-ring',
                'flaticon-018-wedding-1'    =>  'flaticon-018-wedding-1',
                'flaticon-019-wedding-location' =>  'flaticon-019-wedding-location',
                'flaticon-020-home' =>  'flaticon-020-home',
                'flaticon-021-wedding-invitation'   =>  'flaticon-021-wedding-invitation',
                'flaticon-022-church'   =>  'flaticon-022-church',
                'flaticon-023-wedding-invitation'   =>  'flaticon-023-wedding-invitation',
                'flaticon-024-wine-1'   =>  'flaticon-024-wine-1',
                'flaticon-025-wedding-cake' =>  'flaticon-025-wedding-cake',
                'flaticon-026-wine' =>  'flaticon-026-wine',
                'flaticon-027-calendar' =>  'flaticon-027-calendar',
                'flaticon-028-candle'   =>  'flaticon-028-candle',
                'flaticon-029-bible'    =>  'flaticon-029-bible',
                'flaticon-030-dish' =>  'flaticon-030-dish',
                'flaticon-031-speech-bubble'    =>  'flaticon-031-speech-bubble',
                'flaticon-032-shopping-bag' =>  'flaticon-032-shopping-bag',
                'flaticon-033-bouquet'  =>  'flaticon-033-bouquet',
                'flaticon-034-rings'    =>  'flaticon-034-rings',
                'flaticon-035-balloons' =>  'flaticon-035-balloons',
                'flaticon-036-flower'   =>  'flaticon-036-flower',
                'flaticon-037-lips' =>  'flaticon-037-lips',
                'flaticon-038-camera'   =>  'flaticon-038-camera',
                'flaticon-039-double-bed'   =>  'flaticon-039-double-bed',
                'flaticon-040-necklace' =>  'flaticon-040-necklace',
                'flaticon-041-ticket'   =>  'flaticon-041-ticket',
                'flaticon-042-wedding-suit' =>  'flaticon-042-wedding-suit',
                'flaticon-043-pastor'   =>  'flaticon-043-pastor',
                'flaticon-044-groom'    =>  'flaticon-044-groom',
                'flaticon-045-bride'    =>  'flaticon-045-bride',
                'flaticon-046-wedding'  =>  'flaticon-046-wedding',
                'flaticon-047-wedding-invitation'   =>  'flaticon-047-wedding-invitation',
                'flaticon-048-just-married' =>  'flaticon-048-just-married',
                'flaticon-049-love' =>  'flaticon-049-love',
                'flaticon-050-wedding-couple'   =>  'flaticon-050-wedding-couple',
            );
        }
    }

    /**
     *  ------------------------------
     *  SDWeddingDirectory - Flaticon - Object
     *  ------------------------------
     */
    SDWeddingDirectory_Font_Family_Flaticon::get_instance();
}