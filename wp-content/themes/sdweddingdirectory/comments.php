<?php
/**
 *  The template for displaying Comments
 *  ------------------------------------
 */
if ( post_password_required() ){

   return;
}

/**
 *  Is Is Page / Post ?
 *  ----------------------------
 */
if( is_page() ){

    ?><div id="comments" class="sdweddingdirectory-section-comment comments-area commnets-reply comments_list_wrap sdweddingdirectory-page-comment"><?php
    
}else{

    ?><div id="comments" class="sdweddingdirectory-section-comment comments-area commnets-reply comments_list_wrap sdweddingdirectory-blog-comment"><?php
}

    /**
     *  Have Post / Page Comment ?
     *  --------------------------
     */
    if ( have_comments() ) {

        ?><h3 class="fw-7 mb-4"><?php

            /**
             *  Comment Post Heading
             *  --------------------
             */
            printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', absint( get_comments_number() ), 'sdweddingdirectory' ),

                number_format_i18n( absint( get_comments_number() ) ), '<span>' . esc_attr( get_the_title() ) . '</span>' 
            ); 

        ?></h3><?php 

        /**
         *  Comment Next + Prev Pagination
         *  ------------------------------
         */
        if ( get_comment_pages_count() > absint( '1' ) && get_option( 'page_comments' ) ) { ?>

            <nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">

                <h2 class="assistive-text"><?php esc_html_e( 'Comment navigation', 'sdweddingdirectory' ); ?></h2>

                <div class="sdweddingdirectory-comment-navigation">

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-start">

                            <div class="nav-previous">
                            <?php

                                previous_comments_link( esc_attr__( '&larr; Older Comments', 'sdweddingdirectory' ) );
                            ?>
                            </div>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-end">

                            <div class="nav-next">
                            <?php 

                                next_comments_link( esc_attr__( 'Newer Comments &rarr;', 'sdweddingdirectory' ) );

                            ?>
                            </div>

                        </div>

                    </div>

                </div>

            </nav>

            <?php 
        }

        /**
         *  Load Comments
         *  -------------
         */
        ?><ol class="comments" id="sdweddingdirectory-comment-section"><?php

            /**
             *  Load Comments
             *  -------------
             */
            wp_list_comments( array( 

                'callback'  =>  [ esc_attr( 'SDWeddingDirectory_Comment_Helper' ), esc_attr( 'sdweddingdirectory_comment' ) ],

                'style'     =>  esc_attr( 'ol' )

            ) );

        ?></ol><?php 

        /**
         *  Comment Next + Prev Pagination
         *  ------------------------------
         */
        if ( get_comment_pages_count() > absint( '1' ) && get_option( 'page_comments' ) ) { ?>

            <nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">

                <h2 class="assistive-text"><?php esc_html_e('Comment navigation', 'sdweddingdirectory' ); ?></h2>

                <div class="sdweddingdirectory-comment-navigation">

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-start">

                            <div class="nav-previous">
                            <?php 

                                previous_comments_link( esc_attr__( '&larr; Older Comments', 'sdweddingdirectory') ); 

                            ?>
                            </div>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-end">

                            <div class="nav-next">
                            <?php 

                                next_comments_link( esc_attr__( 'Newer Comments &rarr;', 'sdweddingdirectory') ); 

                            ?>
                            </div>

                        </div>

                    </div>

                </div>

            </nav>

            <?php 

        } // end if
    }

    /**
     *  If Current Post Have Open Comment
     *  ---------------------------------
     */
    if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ){

        printf( '<p class="nocomments">%1$s</p>', 

            /**
             *  1. Translation String
             *  ---------------------
             */
            esc_attr_e('Comments are closed.', 'sdweddingdirectory' )
        );
    }

    /**
     *  Comment form
     *  ------------
     */
    $comment_form = array(

        'class_form'                =>  'contact-form comment-form form-style-one row',

        /**
         *  Translation String
         *  ------------------
         */
        'title_reply'               =>  esc_attr__( 'Leave a Comment', 'sdweddingdirectory' ),

        /**
         *  Translation String
         *  ------------------
         */
        'title_reply_to'            =>  esc_attr__( 'Leave a Reply to %s', 'sdweddingdirectory' ),

        'comment_notes_before'      =>  '',

        'comment_notes_after'       =>  '',

        'fields'                    =>  array(

            'author' => sprintf(   '<div class="col-sm-6">

                                        <div class="mb-3">

                                           <input autocomplete="off" id="author" name="author" type="text" placeholder="%1$s" class="form-control input-md" required="">

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Translation String
                                     *  ---------------------
                                     */
                                    esc_attr__( 'Your Name', 'sdweddingdirectory' )
                        ),

            'email' =>  sprintf(    '<div class="col-sm-6">

                                        <div class="mb-3">

                                          <input autocomplete="off" id="email" name="email" type="text" placeholder="%1$s" class="form-control input-md" required="">

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Translation String
                                     *  ---------------------
                                     */
                                    esc_attr__( 'Your Email', 'sdweddingdirectory' )
                        ),

            'cookies' =>  sprintf(  '<div class="col-12">

                                        <div class="mb-3">

                                            <p class=" form-dark comment-form-cookies-consent">

                                                <input autocomplete="off" id="%1$s" class="form-check-input" name="%1$s" type="checkbox" value="yes"/>

                                                <label class="form-check-label" for="%1$s">%2$s</label>

                                            </p>

                                        </div>

                                    </div>',

                                    /**
                                     *  1. Cookies Field ID
                                     *  -------------------
                                     */
                                    esc_attr( 'wp-comment-cookies-consent' ),

                                    /**
                                     *  2. Translation String
                                     *  ---------------------
                                     */
                                    esc_attr__( 'Save my name, email, and website in this browser for the next time I comment.', 'sdweddingdirectory' )
                        ),                    
        ),

        'comment_field'             =>  sprintf('<div class="col-sm-12">

                                                    <div class="mb-3">

                                                        <textarea class="form-control" rows="4" id="comment" 
                                                        name="comment" placeholder="%1$s"></textarea>

                                                    </div>

                                                </div>',

                                            /**
                                             *  Translation String
                                             *  ------------------
                                             */
                                            esc_attr__( 'Comments', 'sdweddingdirectory' )
                                    ),

        'title_reply_before'        =>  '<h3 id="reply-title" class="comment-reply-title fw-7 mb-3">',

        'title_reply_after'         =>  '</h3>',

        'submit_field'              =>  '<p class="form-submit col-md-12 text-start">%1$s %2$s</p>',

        /**
         *  Translation String
         *  ------------------
         */
        'label_submit'              =>  esc_attr__( 'Post Your Comment', 'sdweddingdirectory' ),

        'class_submit'              =>  'btn btn-primary',

        'logged_in_as'              =>  '',
    );

    /**
     *  Load Comment Form
     *  -----------------
     */
    comment_form( $comment_form );

/**
 *  Is Is Page / Post ?
 *  -------------------
 */
?></div>