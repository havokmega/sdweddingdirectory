<?php
/**
 *  Create : SDWeddingDirectory Blog Widget
 *  -------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Blog_Widget' ) && class_exists( 'WP_Widget' ) ){

    /**
     *  Create : SDWeddingDirectory Blog Widget
     *  -------------------------------
     */
    class SDWeddingDirectory_Blog_Widget extends WP_Widget {
      
        /**
         *  Create : SDWeddingDirectory Blog Widget
         *  -------------------------------
         */
        function __construct() {

            parent::__construct(
              
                /**
                 *  Widget ID
                 *  ---------
                 */
                esc_attr( 'sdweddingdirectory_blog_widget' ),
              
                /**
                 *  Widget Name
                 *  -----------
                 */
                esc_attr__('SDWeddingDirectory Blog Widget', 'sdweddingdirectory'),
              
                /**
                 *  Widget Description
                 *  ------------------
                 */
                array( 

                    'description' => esc_attr__( 'SDWeddingDirectory Blog Widget', 'sdweddingdirectory' ) 
                )
            );
        }
          
        /**
         *  Widget
         *  ------
         */
        public function widget( $args, $instance ){
            
            /**
             *  Extract Args
             *  ------------
             */
            extract( $args );

            $title      =       isset( $instance['title'] ) 

                        ?       esc_attr( $instance['title'] )

                        :       esc_attr__( 'SDWeddingDirectory Recent Post Title', 'sdweddingdirectory' );

            $limit      =       isset( $instance['limit'] ) 

                        ?       absint( $instance[ 'limit' ] )

                        :       absint( '3' );

            /**
             *  Before Widget
             *  -------------
             */
            print   $before_widget  .  $before_title  .  esc_attr( $title )  .  $after_title;

            /**
             *  Get Post IDs
             *  ------------
             */
            $post_ids   =   apply_filters( 'sdweddingdirectory/post/data', array_merge(

                                /**
                                 *  Post Per Page
                                 *  -------------
                                 */
                                [ 
                                    'posts_per_page'    =>     $limit,
                                ],

                                /**
                                 *  Meta Query
                                 *  ----------
                                 */
                                [   'meta_query'        =>     [
                                                                    [
                                                                        'key'           =>      esc_attr( '_thumbnail_id' ),

                                                                        'type'          =>      'numeric',

                                                                        'compare'       =>      '!=',

                                                                        'value'         =>      ''
                                                                    ]
                                                                ]
                                ],

                                /**
                                 *  2. Is Single Page
                                 *  -----------------
                                 */
                                is_single()

                                ?       [   'post__not_in'      =>      [ get_the_ID() ]     ]

                                :       []

                            ) );

            /**
             *  Make sure post id exists
             *  ------------------------
             */
            if(  SDWeddingDirectory_Loader:: _is_array(  $post_ids  )  ){

                ?><div class="popular-post"><ul class="list-unstyled"><?php

                foreach( $post_ids as $post_id => $post_title ){

                    printf( '<li>

                                %1$s
                                
                                <div class="align-self-center">

                                    <h6><a href="%3$s">%2$s</a></h6>

                                    <small>%4$s</small>

                                </div>

                            </li>',

                            /**
                             *  1. Load Post Thumbnails
                             *  -----------------------
                             */
                            get_the_post_thumbnail( absint( $post_id ), 'thumbnail' ),

                            /**
                             *  2. Post Title
                             *  -------------
                             */
                            esc_attr( get_the_title( $post_id ) ),

                            /**
                             *  3. Post Link
                             *  ------------
                             */
                            esc_url( get_the_permalink( $post_id ) ),

                            /**
                             *  4. Post Date
                             *  ------------
                             */
                            esc_attr( get_the_time( get_option( 'date_format' ), $post_id ) )
                    );
                }

                ?></ul></div><?php
            }

            /**
             *  After Widget
             *  ------------
             */
            print   $after_widget;
        }
   
        /**
         * Echo the settings update form
         *
         * @param array $instance Current settings
         */
        public function form( $instance ){  // Output admin widget options form

            $title = isset ( $instance['title'] ) ? $instance['title'] : __( 'Recent Post' , 'sdweddingdirectory' );

            $title = esc_attr( $title );
            
            printf(
                '<p><label for="%1$s">%2$s</label><br />
                <input autocomplete="off" type="text" name="%3$s" id="%1$s" value="%4$s" class="widefat"></p>',
                $this->get_field_id( 'title' ),
                esc_html__( 'Title', 'sdweddingdirectory' ),
                $this->get_field_name( 'title' ),
                esc_attr( $title )
            );

            $limit = isset ( $instance['limit'] ) ? $instance['limit'] : absint('4');
            $limit = esc_attr( $limit );
            printf(
                '<p><label for="%1$s">%2$s</label><br />
                <input autocomplete="off" type="text" name="%3$s" id="%1$s" value="%4$s" class="widefat"></p>',
                $this->get_field_id( 'limit' ),
                esc_html__( 'Limit', 'sdweddingdirectory' ),
                $this->get_field_name( 'limit' ),
                absint( $limit )
            );
        }

        /**
         * Prepares the content. Not.
         *
         * @param  array $new_instance New content
         * @param  array $old_instance Old content
         * @return array New content
         */
        public function update( $new_instance, $old_instance ){

            $new_instance_array = array( 'title', 'limit' );

            if( SDWeddingDirectory_Loader:: _is_array( $new_instance_array ) ){

                foreach( $new_instance_array as $val ){

                    $old_instance[ $val ] = $new_instance[ $val ];
                }
            }

            return $old_instance;        
        }
    }
}
 
// Register and load the widget
function sdweddingdirectory_load_widget() {

    register_widget( 'sdweddingdirectory_blog_widget' );
}
add_action( 'widgets_init', 'sdweddingdirectory_load_widget' );