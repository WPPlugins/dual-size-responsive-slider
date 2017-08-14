<?php

wp_enqueue_media(); // メディアアップローダー用のスクリプトをロードする

// カスタムメディアアップローダー用のJavaScript
wp_enqueue_script(
	'slide-media-uploader',
	plugins_url( "js/media-uploader.js", DSRS_PLUGIN_FILE ),
	array( 'jquery' ),
	filemtime( DSRS_PLUGIN_PATH . '/js/media-uploader.js' ),
	false
);

// tableの中身はループで生成
$custom_fields = array(
	'large_slide_image' => __( 'Large Slide Image', DSRS ),
	'small_slide_image' => __( 'Small Slide Image', DSRS ),
);

// スライドの幅をオプションから取得
$slide_width = array(
	'large_slide_image' => 100,
	'small_slide_image' => 100 * ( get_option( 'small-slider-width' ) / get_option( 'large-slider-width' ) ),
);
?>

<?php wp_nonce_field( DSRS, 'slide_meta_wpnonce' ); ?>

<table class="slide-table">
	<?php foreach ( $custom_fields as $key => $key_name ) : $field_value = get_post_meta( get_the_ID(), $key, true ); ?> 
		<tr class="set-<?php echo $key; ?>">
			<th><label for="<?php echo $key; ?>-from-media"><?php echo $key_name; ?></label></th>
			<td>
				<div id="preview-<?php echo $key; ?>"  data-slide-width="<?php echo esc_attr( $slide_width[ $key ] ); ?>">
					<?php if ( $field_value ): $image_url = wp_get_attachment_url( $field_value ); ?> 
						<img src="<?php echo esc_url( $image_url ); ?>" width="<?php echo esc_attr( $slide_width[ $key ] ); ?>%" />
					<?php endif; ?> 
				</div>
				<input type="hidden" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo esc_attr( $field_value ); ?>" />
				<input type="button" id="<?php echo $key; ?>-from-media" value="<?php _e( 'Choose from Media Uploader', DSRS ); ?>" onClick="responsive_<?php echo $key; ?>_uploader( event, '<?php _e( 'Choose Image', DSRS ); ?>')" />
				<input type="button" id="<?php echo $key; ?>-unset" value="<?php _e( 'Unset this Image', DSRS ); ?>" onClick="responsive_slider_image_unset( '<?php echo $key; ?>' );" <?php echo $field_value ? '' : 'disabled'; ?>/>
				<?php if ( 'large_slide_image' == $key ): ?>
					<p><?php _e( 'Width', DSRS ) ?>: <?php echo esc_html( get_option( 'large-slider-width' ) ); ?>, <?php _e( 'Height', DSRS ) ?>: <?php echo esc_html( get_option( 'large-slider-height' ) ); ?></p>
				<?php elseif ( 'small_slide_image' == $key ): ?>
					<p><?php _e( 'Width', DSRS ) ?>: <?php echo esc_html( get_option( 'small-slider-width' ) ); ?>, <?php _e( 'Height', DSRS ) ?>: <?php echo esc_html( get_option( 'small-slider-height' ) ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?> 
	<tr class="set-slide-link">
		<th><label for="slide_link"><?php _e( 'Slide Link', DSRS ); ?></label></th>
		<td>
			<input type="text" id="slide_link" name="slide_link" value="<?php echo esc_url( get_post_meta( get_the_ID(), 'slide_link', true ) ); ?>" placeholder="URL"/><br />
			<p>
				<label for="slide_link_target"><?php _e( 'Open Link in New Tab', DSRS ); ?></label>
				<input type="checkbox" id="slide_link_target" name="slide_link_target" value="_blank"<?php echo get_post_meta( get_the_ID(), 'slide_link_target', true ) ? ' checked' : ''; ?>/>
			</p>
		</td>
	</tr>
</table>
