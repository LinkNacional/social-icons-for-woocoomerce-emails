<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function siwce_get_icon_list () {
	return apply_filters( 'siwce_social_links', array(
		'facebook' => 'Facebook',
		'instagram' => 'Instagram',
		'twitter' => 'Twitter',
		'youtube' => 'Youtube',
		'linkedin' => 'LinkedIn',
		'pinterest' => 'Pinterest'
	) );
}

function siwce_get_used_icons () {
	$icons = array();

	foreach ( siwce_get_icon_list() as $id => $name ) {
		$href = get_option( 'siwce_url_' . $id );

		if ( empty( $href ) ) continue;

		$icons[] = array(
			'href' => $href,
			'image_src' => siwce_get_icon_image_uri( $id ),
			'name' => $name
		);
	}

	return $icons;
}

function siwce_get_icon_image_uri ( $id ) {
	return apply_filters( 'siwce_icon_image_uri', SIWCE_ASSETS_URL . 'images/' . $id . '.png', $id );
}

function siwce_get_icon_default_size () {
	return apply_filters( 'siwce_icon_default_size', 48 );
}