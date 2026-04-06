<?php
/**
 *  SDWeddingDirectory - Theme Header Template
 *  ----------------------------------
 *  @link - https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *  ---------------------------------------------------------------------------------------
 */
do_action( 'sdweddingdirectory/page-start' ); ?>

<!DOCTYPE html>
<!--
   _____ ___    _   __   ____  ____________________
  / ___//   |  / | / /  / __ \/  _/ ____/ ____/ __ \
  \__ \/ /| | /  |/ /  / / / // // __/ / / __/ / / /
 ___/ / ___ |/ /|  /  / /_/ // // /___/ /_/ / /_/ /
/____/_/  |_/_/ |_/__/_____/___/_____/\____/\____/

 _       ____________  ____  _____   ________
| |     / / ____/ __ \/ __ \/  _/ | / / ____/
| | /| / / __/ / / / / / / // //  |/ / / __
| |/ |/ / /___/ /_/ / /_/ // // /|  / /_/ /
|__/|__/_____/_____/_____/___/_/ |_/\____/

    ____  ________  ______________________  ______  __
   / __ \/  _/ __ \/ ____/ ____/_  __/ __ \/ __ \ \/ /
  / / / // // /_/ / __/ / /     / / / / / / /_/ /\  /
 / /_/ // // _, _/ /___/ /___  / / / /_/ / _, _/ / /
/_____/___/_/ |_/_____/\____/ /_/  \____/_/ |_| /_/
-->

<?php do_action( 'sdweddingdirectory/html-start' ); ?>

<html <?php language_attributes(); ?>>

  <head><?php do_action( 'sdweddingdirectory/head' ); wp_head(); ?></head>

  <body <?php do_action( 'sdweddingdirectory_body' ); ?>>

  <?php wp_body_open(); ?>

  <div id="page" class="site">

      <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sdweddingdirectory' ); ?></a>
      
      <?php

      /**
       *  Load Header ?
       *  -------------
       */
      if( ! is_singular( 'website' ) && ( ! class_exists( 'SDWeddingDirectory' ) || ! SDWeddingDirectory:: is_dashboard() ) ){

          /**
           *  SDWeddingDirectory - Header Actions
           *  ---------------------------
           */
          do_action( 'sdweddingdirectory/header' );
      }

      ?>
      <main id="content" class="site-content">
