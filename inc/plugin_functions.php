<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function siwce_email_social_icons ( $footer_content ) {
	$icons = siwce_get_used_icons();

	if ( empty( $icons ) ) return $footer_content;

	$text_before_icons = get_option( 'siwce_text_before_icons' );
	$img_size = get_option( 'siwce_img_width' );

	$img_size = empty( $img_size ) ? siwce_get_icon_default_size() : esc_attr( $img_size );

	ob_start();

	if ( ! empty( $text_before_icons ) ) :
	?>

	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center" valign="top" id="siwce_text_before_icons">
				<?php echo esc_html( $text_before_icons ); ?>
			</td>
		</tr>
	</table>

	<?php
	endif;

	if ( ! empty( $icons ) ) :
	?>

	<table border="0" cellpadding="0" cellspacing="0" align="center" id="siwce_icons">
		<tr>
			<td valign="middle">
				<table border="0" cellpadding="0" cellspacing="10" align="center">
					<tr>

		<?php
		foreach ( $icons as $icon ) :
		?>

						<td valign="middle" id="siwce_icon_<?php echo esc_attr( $icon['name'] ); ?>">
							<a href="<?php echo esc_url( $icon['href'] ); ?>" title="<?php echo esc_attr( $icon['name'] ); ?>"><img
								alt="<?php echo esc_attr( $icon['name'] ); ?>"
								src="<?php echo esc_attr( $icon['image_src'] ); ?>"
								width="<?php echo esc_attr( $img_size ); ?>"
								height="<?php echo esc_attr( $img_size ); ?>" /></a>
						</td>

		<?php
		endforeach;
		?>

					</tr>
				</table>
			</td>
		</tr>
	</table>

	<?php
	endif;

	if ( ! empty( $footer_content ) ) :
	?>

	<table width="100%" border="0" cellpadding="10" cellspacing="10">
		<!-- foter text -->
		<tr>
			<td valign="middle"><?php echo wp_kses_post( $footer_content ); ?></td>
		</tr>
	</table>

	<?php
	endif;

	return ob_get_clean();
}

function siwce_social_icons_settings( $settings ) {
	$icons = siwce_get_icon_list();

	$settings[] = array(
		'title' => __( 'Social Icons', 'social-icons-for-woocoomerce-emails' ),
		'desc' => __( 'Leave in blank the social networks url that you don\'t use.', 'social-icons-for-woocoomerce-emails' ),
		'type' => 'title',
		'id' => 'siwce'
	);

	$settings[] = array(
		'title' => __( 'Icon size', 'social-icons-for-woocoomerce-emails' ),
		'id' => 'siwce_img_width',
		'type' => 'text',
		'desc' => __( 'Don\'t use any unit like "px", "em", etc.', 'social-icons-for-woocoomerce-emails' ),
		/* translators: %s: default icon size in pixels */
		'placeholder' => sprintf( __( 'default value is %s', 'social-icons-for-woocoomerce-emails' ), siwce_get_icon_default_size() )
	);

	$settings[] = array(
		'title' => __( 'Text before of icons', 'social-icons-for-woocoomerce-emails' ),
		'id' => 'siwce_text_before_icons',
		'type' => 'text',
		'placeholder' => __( 'Example: Follow us', 'social-icons-for-woocoomerce-emails' )
	);

	foreach($icons as $id => $name) {
		$settings[] = array(
			/* translators: %s: social network name */
			'title' => sprintf( __( '%s URL', 'social-icons-for-woocoomerce-emails' ), $name ),
			'id' => 'siwce_url_' . $id,
			'type' => 'text'
		);
	}

	$settings[] = array(
		'type' => 'sectionend',
		'id' => 'siwce'
	);

	return $settings;
}

function siwce_text_before_icons_style ( $css ) {
	$base_color = get_option( 'woocommerce_email_base_color' );

	$style = '#siwce_text_before_icons {'
		. 'color:' . esc_attr( $base_color ) . ';'
		. 'display: block;'
		. 'font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;'
		. 'font-size: 18px;'
		. 'font-weight: bold;'
		. 'line-height: 130%;'
		. '}'; // close declarion

	return $css . apply_filters( 'siwce_footer_style', $style );
}

function siwce_load_plugin_textdomain () {
	load_plugin_textdomain( 'social-icons-for-woocoomerce-emails', false, dirname( plugin_basename( SIWCE_FILE ) ) . '/languages/' );
}