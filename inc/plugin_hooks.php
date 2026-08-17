<?php

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'siwce_load_plugin_textdomain' );

add_filter( 'woocommerce_email_footer_text' , 'siwce_email_social_icons' );

add_filter( 'woocommerce_get_settings_email', 'siwce_social_icons_settings', 10, 2 );

add_filter( 'woocommerce_email_styles', 'siwce_text_before_icons_style' );