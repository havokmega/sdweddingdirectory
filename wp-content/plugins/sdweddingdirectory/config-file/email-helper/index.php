<?php
/**
 *  SDWeddingDirectory - Email Builder
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Email' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Email Builder
     *  --------------------------
     */
    class SDWeddingDirectory_Email extends SDWeddingDirectory_Config {

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

        }

        /**
         *  Email Object : Error Message
         *  ----------------------------
         */
        public static function sdweddingdirectory_email_notification_error(){

            die( json_encode( array(

                'message'   =>  esc_attr__( 'Your Email Notification Could Not Successfully Connect. Please contact administrator.', 'sdweddingdirectory' )

            ) ) );
        }

        /**
         *  Setting Option > Email Setting > Admin Email List Key to get Email ID
         *  ---------------------------------------------------------------------
         */
        public static function setting_admin_email( $setting_id = '' ){

            /**
             *  Setting ID is not empty!
             *  ------------------------
             */
            if( empty( $setting_id ) ){

                return;
            }

            /**
             *  Have admin email ?
             *  ------------------
             */
            $_admin_emails      =   sdweddingdirectory_option( 'admin_emails' );

            $_email_enable      =   sdweddingdirectory_option( esc_attr( 'admin-email-' . $setting_id ) )  == esc_attr( 'on' );

            $_email_id_key      =   sdweddingdirectory_option( esc_attr( 'admin-email-id-' . $setting_id ) );

            $_have_email        =   parent:: _have_data( $_admin_emails[ absint( $_email_id_key ) ][ 'email_id' ] );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_admin_emails ) && $_email_enable && $_have_email ){

                return  sanitize_email( $_admin_emails[ absint( $_email_id_key ) ][ 'email_id' ] );
            }
        }

        /**
         *  Generally : This Formate Acceptable for SDWeddingDirectory Email Sending Process
         *  ------------------------------------------------------------------------
         */
        public static function sending_email( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( ! parent:: _is_array( $args ) ){

                self:: sdweddingdirectory_email_notification_error();
            }

            /**
             *   Extract Args
             *   ------------
             */
            extract( $args );

            /**
             *  Email Content
             *  -------------
             */
            $data         =   array(

                /**
                 *  1. Subject
                 *  ----------
                 */
                'subject'           =>  self:: ot_email_content_replace_with_value(

                                            /**
                                             *  1. Replace Key with Value From Email Format
                                             *  -------------------------------------------
                                             */
                                            $email_data,

                                            /**
                                             *  2. Email Format Get Via Setting Option
                                             *  --------------------------------------
                                             */
                                            sdweddingdirectory_option( esc_attr( 'email-subject-' . $setting_id ) ),
                                        ),

                'email_content'     =>  self:: ot_email_content_replace_with_value(

                                            /**
                                             *  1. Replace Key with Value From Email Format
                                             *  -------------------------------------------
                                             */
                                            $email_data,

                                            /**
                                             *  2. Email Format Get Via Setting Option
                                             *  --------------------------------------
                                             */
                                            sdweddingdirectory_option( esc_attr( 'email-body-' . $setting_id ) )
                                        )              
            );

            /**
             *  Collected Email IDs
             *  -------------------
             */
            $email_ids      =   [];

            /**
             *  User Email ID
             *  -------------
             */
            $email_ids[]    =   sanitize_email( $sender_email );

            /**
             *  Admin Email ID ?
             *  ----------------
             */
            $_have_admin_email  =   self:: setting_admin_email( $setting_id );

            /**
             *  Have Admin Email ?
             *  ------------------
             */
            if( parent:: _have_data( $_have_admin_email ) ){

                $email_ids[]    =   sanitize_email( $_have_admin_email );
            }

            /**
             *  Sending Email
             *  -------------
             */
            self:: send_email(

                /**
                 *  1. Sender Email ID
                 *  ------------------
                 */
                $email_ids,

                /**
                 *  2. Subject
                 *  ----------
                 */
                $data[ 'subject' ],

                /**
                 *  Message
                 *  -------
                 */
                self:: get_contents( $data )
            );
        }

        /**
         *  Theme Option ShortCode to Fetch Value
         *  -------------------------------------
         */
        public static function ot_email_content_replace_with_value( $variables = [], $template = '' ){

              if( parent:: _is_array( $variables ) && parent:: _have_data( $template ) ){

                  foreach( array_merge( $variables, apply_filters( 'sdweddingdirectory/helper', [] ) ) as $key => $value ){

                      if( isset( $key ) ){

                          $template = str_replace('{{'.$key.'}}', preg_replace('/\s+/', ' ', $value  ), $template);
                      }
                  }
              }

              return $template;
        }

        /**
         *  Email HTML Template Replace Key => Value
         *  ----------------------------------------
         */
        public static function get_contents( $variables = [], $templateName = 'template-one.html' ) {

            $template   =   file_get_contents(

                                plugin_dir_path( __FILE__ ) . '/templates/' . $templateName 
                            );

            foreach( array_merge( $variables, apply_filters( 'sdweddingdirectory/helper', [] ) ) as $key => $value ){

                if( isset( $key ) ){

                    $template = str_replace('{{'.$key.'}}', preg_replace('/\s+/', ' ', $value  ), $template );
                }
            }

            return $template;
        }

        /**
         *  Sending Email
         *  -------------
         */
        public static function send_email( $mail_to, $mail_subject, $mail_message ){

            /**
             *  To send HTML mail, the Content-type header must be set
             *  ------------------------------------------------------
             */
            $headers   = [];

            $headers[] = 'MIME-Version: 1.0';

            $headers[] = 'Content-type: text/html; charset=utf-8';

            $headers[] = 'From: '. esc_attr( get_bloginfo( 'name' ) ) .' <noreply@'. $_SERVER['HTTP_HOST'] .'>';

            $headers[] = 'Reply-To: noreply@'. $_SERVER['HTTP_HOST'];

            // $headers[] = 'Cc: '. get_option('admin_email') ;

            $headers[] = 'X-Mailer: PHP/' . phpversion();

            /**
             *  Send Email
             *  ----------
             */
            @wp_mail(

                $mail_to,

                str_replace( '<br/>', "\r\n\r\n", $mail_subject ),

                str_replace( '<br/>', "\r\n\r\n", $mail_message ),

                implode( "\r\n", $headers )
            );
        }
    }   

    /**
     *  Email Object
     *  ------------
     */
    SDWeddingDirectory_Email::get_instance();
}