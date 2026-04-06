<?php
/**
 *  -------------------------------
 *  SDWeddingDirectory - ShortCode - Object
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory - ShortCode - Object
     *  -------------------------------
     */
    class SDWeddingDirectory_Shortcode extends SDWeddingDirectory_Config{

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
             *  1. SDWeddingDirectory - Placeholder Filter
             *  ----------------------------------
             */
            add_filter( 'sdweddingdirectory/placeholder', function( $args = [] ){

                /**
                 *  Add Slider Placeholder
                 *  ----------------------
                 */
                return  array_merge(  

                            /**
                             *  Have Args ?
                             *  -----------
                             */
                            $args,

                            /**
                             *  Merge New Args
                             *  --------------
                             */
                            array(

                                [
                                    'name'              =>  esc_attr__( 'ShortCode', 'sdweddingdirectory-shortcodes' ),

                                    'id'                =>  esc_attr( 'shortcode' ),

                                    'placeholder'       =>  apply_filters( 'sdweddingdirectory/placeholder/shortcode', [] )
                                ]
                            )
                        );
            } );

            /**
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  -------------------------------------------
         *   In ShortCode - I added attribute which load
         *   -------------------------------------------
         *   1. Style One = Grid class "col"
         *   -------------------------------------------
         *   2. Style Two = Carousel class ="item"
         *   -------------------------------------------
         */
        public static function _shortcode_style_start( $style = '' ){

            /**
             *   Make sure is not empty!
             *   -----------------------
             */
            if( empty( $style ) ){

                return;
            }

            /**
             *  Style 1 = Grid Management div with class "col"
             *  ----------------------------------------------
             */
            if( $style == '1' ){

                return      '<div class="col">';
            }
            
            /**
             *  Style 2 = Carousel Management div with class "item"
             *  ---------------------------------------------------
             */
            if( $style == '2' ){

                return      '<div class="item">';
            }
        }

        /**
         *  -------------------------------------------
         *   In ShortCode - I added attribute which load
         *   -------------------------------------------
         *   1. Style One = Grid class "col"
         *   -------------------------------------------
         *   2. Style Two = Carousel class ="item"
         *   -------------------------------------------
         */
        public static function _shortcode_style_end( $style = '' ){

            /**
             *   Make sure is not empty!
             *   -----------------------
             */
            if( empty( $style ) ){

                return;
            }

            /**
             *  Style 1 = Grid Management div with class "col"
             *  ----------------------------------------------
             */
            if( $style == '1' ){

                return      '</div>';
            }
            
            /**
             *  Style 2 = Carousel Management div with class "item"
             *  ---------------------------------------------------
             */
            if( $style == '2' ){

                return      '</div>';
            }
        }

        /**
         *  Load Script
         *  -----------
         */
        public static function _shortcode_script(){

            ?>
            <script>

                (function($) {

                    "use strict";

                    /**
                     *  Object Call
                     *  -----------
                     */
                    $(document).ready( function(){  SDWeddingDirectory_Elements.init(); });

                })(jQuery); 

            </script>
            <?php
        }

        /**
         *  Default Array to Shorcode atts [ key=value ]
         *  --------------------------------------------
         */
        public static function _shortcode_atts( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'handler'       =>      [],

                ] ) );

                /**
                 *  In Loop
                 *  -------
                 */
                foreach( $args as $key => $value ){

                    $handler[]    =    $key  . '=' . '"' . $value . '"';
                }

                /**
                 *  Return  : Handler
                 *  -----------------
                 */
                return      implode( ' ', $handler );
            }
        }

        /**
         *  Get Demo Content
         *  ----------------
         */
        public static function _dummy_content( $start_with = '' ){

            /**
             *  List
             *  ----
             */
            $list       =   [];

            $list[]     =   'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin facilisis suscipit augue eu tincidunt. Nullam feugiat dolor nec aliquet posuere. Sed cursus non lorem eget pellentesque. Pellentesque pharetra erat id lectus venenatis ornare. Sed in tortor commodo, vulputate sem ac, tincidunt ipsum. Proin faucibus vehicula ex, sit amet aliquet nulla bibendum sit amet. Quisque id ultricies eros. Suspendisse non velit lacus. Proin quis erat vel tellus faucibus laoreet. Cras quis blandit ligula, ut malesuada velit.';

            $list[]     =   'Pellentesque pharetra leo vel augue luctus auctor. Mauris lacus leo, hendrerit ut justo vitae, posuere ornare erat. Sed a nulla quam. Aliquam ac venenatis lacus, ut ornare felis. Duis in orci luctus, maximus est at, imperdiet enim. Interdum et malesuada fames ac ante ipsum primis in faucibus. Maecenas dapibus libero quis ipsum tincidunt, et vestibulum lorem efficitur. Duis a urna ut magna molestie blandit lobortis in neque. Donec lacinia lectus vel leo porta maximus. Duis dignissim elit venenatis, tempor nisi pharetra';

            $list[]     =   'Vivamus vel porta nisi. Suspendisse bibendum velit sit amet erat porttitor, vitae malesuada leo feugiat. In ac imperdiet orci. Aliquam eget consectetur magna. Maecenas viverra, massa vitae ullamcorper convallis, sapien lorem facilisis quam, in sollicitudin tortor mi et sapien. Sed scelerisque ex eget quam finibus, vulputate tincidunt felis rutrum. Nunc mattis et metus at pulvinar. Suspendisse mollis quis felis eu pharetra. Vivamus dignissim sapien sed lobortis elementum. Nunc in magna ullamcorper, laoreet eros quis, pellentesque dui. Pellentesque id magna faucibus, imperdiet lacus at, consectetur enim. Praesent consequat dolor eu nibh euismod, et ultricies mauris luctus. Fusce at aliquam sapien. Aenean blandit velit augue, vitae ullamcorper urna lobortis suscipit. Nulla quis porta neque, at iaculis leo. ';

            $list[]     =   'Quisque eget turpis eget neque tristique tempor. Aenean eleifend pellentesque nisl. Morbi ante dui, fermentum vel maximus sit amet, finibus nec tortor. Nunc sed ante consectetur, maximus nulla eu, cursus enim. Curabitur nec scelerisque neque, id tincidunt metus. Fusce ultricies ultricies lorem, ac volutpat tellus gravida nec. Morbi cursus non elit vel volutpat. Nam iaculis eu neque a porttitor. Donec finibus mollis turpis nec mollis. Aliquam pellentesque mi et nisl ullamcorper sodales.';

            $list[]     =   'Nulla non nibh vel tellus lacinia tempor quis at nisi. Sed ut mi quis augue tempus porttitor. Praesent ac justo eu velit vulputate tincidunt. Pellentesque felis felis, cursus sed enim sit amet, lobortis cursus est. Quisque ac mattis odio, egestas bibendum enim. Nulla vitae dapibus ligula, tincidunt euismod velit. Nullam eu dapibus libero, et eleifend ipsum. Pellentesque pellentesque hendrerit diam non consectetur. Pellentesque ullamcorper, diam in scelerisque vehicula, augue metus dictum mauris, a eleifend turpis libero in augue. Pellentesque nec purus rutrum nibh ultricies consectetur sed vel sem. Phasellus ac augue tristique, imperdiet lorem vel, tempus enim.';

            $list[]     =   'Vivamus lobortis velit mauris, ac pretium lorem dapibus vel. Quisque sed cursus velit. Fusce nec ipsum tristique, finibus erat sed, sodales leo. Fusce ornare luctus dui quis sagittis. Ut eget lectus a lorem congue sagittis dignissim non mi. Sed mattis, mi eget porttitor ornare, tellus augue auctor nunc, at pulvinar magna ligula et sem. Phasellus diam sapien, tempus a mauris vel, egestas congue nulla. Donec placerat tortor at nisl facilisis faucibus. Sed auctor eget massa a vulputate. Duis dolor est, venenatis sit amet rhoncus sit amet, interdum in ex. Nullam sit amet orci ac ipsum ullamcorper pellentesque ut eu massa. Morbi commodo efficitur ligula vitae blandit.';

            $list[]     =   'Mauris sapien mauris, suscipit at diam in, ullamcorper volutpat dolor. Pellentesque pellentesque finibus ligula, ut pellentesque nulla gravida nec. Proin eu erat augue. Sed sagittis at ante sed facilisis. Maecenas sed ullamcorper lectus, in scelerisque metus. Fusce lectus odio, facilisis quis libero id, pharetra vestibulum elit. In ut metus sapien. Integer fringilla eu ante vitae sagittis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.';

            $list[]     =   'Nunc aliquam mattis suscipit. Fusce sit amet massa erat. Cras molestie, erat eget dapibus convallis, nisi purus fringilla ligula, a dictum erat ipsum sit amet risus. Pellentesque purus quam, dapibus non mollis ac, ornare et nunc. Curabitur a nibh vel eros blandit volutpat eu ut velit. Maecenas semper sit amet dui dignissim tincidunt. Pellentesque vel mollis mi. Vestibulum mauris neque, vulputate eu urna accumsan, ornare gravida ex. Maecenas at semper mauris, vitae volutpat sem. Aliquam pretium convallis mi eget viverra. Donec vel lacus vitae leo vestibulum vestibulum nec vitae dui. Cras quam felis, rutrum vitae luctus varius, placerat sed nisi.';

            $list[]     =   'Aliquam erat volutpat. In pretium, felis at consectetur porta, lacus tellus sollicitudin diam, fermentum condimentum nunc mauris vitae nisi. Praesent nisi mi, rutrum eu enim varius, porta porta augue. Etiam nec facilisis justo. Quisque quis eros nulla. Integer odio lectus, rutrum id dui et, molestie tincidunt metus. Nulla in varius arcu. Maecenas a ullamcorper mi. Duis sodales purus nunc, eu tempor nisl posuere in. Proin dolor dolor, suscipit ac auctor ac, tristique a purus. Vestibulum imperdiet sit amet enim non tempus. Nam at nisl ut felis interdum placerat ut a purus. Nullam rutrum dolor tellus, at vestibulum lorem scelerisque vel. Donec vel ornare ligula.';

            $list[]     =   'Maecenas placerat dapibus sapien eu ultrices. Vestibulum sit amet neque vel libero mattis pretium vel sed tellus. Sed at scelerisque urna. Suspendisse potenti. Donec at ultrices mauris. Curabitur lectus risus, bibendum id faucibus vel, consectetur ut dolor. Curabitur at nunc vel ipsum ultrices luctus. Proin iaculis dapibus lectus, a venenatis tortor dictum lobortis. Fusce blandit posuere semper. In in orci varius libero volutpat porttitor nec non augue. Fusce tortor eros, ultrices in varius non, aliquet sollicitudin dui. Nam posuere metus eu sapien porta vulputate. Aliquam erat volutpat. Cras aliquet felis quis diam varius, id sodales tellus sollicitudin.';

            /**
             *  Return Content
             *  --------------
             */
            return      esc_attr( get_bloginfo( 'name' ) ) . ' - ' . esc_attr( $list[ array_rand( $list, absint( '1' ) ) ] );
        }
    }

    /**
     *  -------------------------------
     *  SDWeddingDirectory - ShortCode - Object
     *  -------------------------------
     */
    SDWeddingDirectory_Shortcode::get_instance();
}