<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function dual_size_responsive_slider_uninstaller() {

	$option_keys = array(
		'large-slider-width',
		'large-slider-height',
		'small-slider-width',
		'small-slider-height',
		'responsive-slider-change-width',
		'slider-animation',
		'slider-direction',
		'slider-animationLoop',
		'slider-slideshowSpeed',
		'slider-animationSpeed',
		'slider-pauseOnHover',
		'slider-controlNav',
		'slider-directionNav',
	);
	foreach ( $option_keys as $key ) {
		delete_option( $key );
	}
	
	$posts = get_posts( array(
		'post_type'      => 'responsive-slider',
		'post_status'    => array(
			'any',
			'trash',
			'auto-draft',
		),
		'posts_per_page' => -1,
	) );
	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}
}

dual_size_responsive_slider_uninstaller();
