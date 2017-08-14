
<div id="dual-size-responsive-slider" class="wrap">
	<h2>Responsive Slider</h2>
	<p class="infomation"><?php printf( __( 'Using shortcode %s or function %s, you can display slider having all slides.', DSRS ), '<code>[responsive-slider]</code>', '<code>responsive_slider()</code>' ); ?></p>
	<form method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>">
		<?php wp_nonce_field( 'dual-size-responsive-slider', 'slider_option_wpnonce' ); ?> 
		<div class="responsive-slider-option large-slider">
			<h3><?php _e( 'Large Slider Size', DSRS ); ?></h3>
			<table>
				<tr class="set-up-width">
					<th><label for="large-slider-width"><?php _e( 'width', DSRS ); ?></label></th>
					<td><input type="text" name="large-slider-width" id="large-slider-width" size="10" value="<?php echo esc_attr( get_option( 'large-slider-width' ) ); ?>" /><span class="unit-label">px</span></td>
				</tr>
				<tr class="set-up-height">
					<th><label for="large-slider-height"><?php _e( 'height', DSRS ); ?></label></th>
					<td><input type="text" name="large-slider-height" id="large-slider-height" size="10" value="<?php echo esc_attr( get_option( 'large-slider-height' ) ); ?>"/><span class="unit-label">px</span></td>
				</tr>
			</table>
		</div>
		<div class="responsive-slider-option small-slider">
			<h3><?php _e( 'Small Slider Size', DSRS ); ?></h3>
			<table>
				<tr class="set-up-width">
					<th><label for="small-slider-width"><?php _e( 'width', DSRS ); ?></label></th>
					<td><input type="text" name="small-slider-width" id="small-slider-width" size="10" value="<?php echo esc_attr( get_option( 'small-slider-width' ) ); ?>"/><span class="unit-label">px</span></td>
				</tr>
				<tr class="set-up-height">
					<th><label for="small-slider-height"><?php _e( 'height', DSRS ); ?></label></th>
					<td><input type="text" name="small-slider-height" id="small-slider-height" size="10" value="<?php echo esc_attr( get_option( 'small-slider-height' ) ); ?>"/><span class="unit-label">px</span></td>
				</tr>
			</table>
		</div>
		<div class="responsive-slider-option responsive-option">
			<h3><?php _e( 'Responsive Option', DSRS ); ?></h3>
			<table>
				<tr>
					<th><label for="responsive-slider-change-width"><?php _e( 'Threshold Width', DSRS ); ?></label></th>
					<td>
						<input type="text" name="responsive-slider-change-width" id="responsive-slider-change-width" size="10" value="<?php echo esc_attr( get_option( 'responsive-slider-change-width' ) ); ?>"/><span class="unit-label">px</span>
						<p><?php _e( 'If screen width is over this value, slider uses large size. If screen width is smaller than this value, diplay small size. ', DSRS ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<div class="responsive-slider-option slider-option">
			<h3><?php _e( 'Slider Option', DSRS ); ?></h3>
			<table>
				<tr>
					<th><label for="slider-animation">animation</label></th>
					<td>
						<select name="slider-animation" id="slider-animation"<?php $selected = get_option( 'slider-animation' ); ?>>
							<option value="fade"<?php  echo ( 'fade'  == $selected ) ? ' selected' : ''; ?>><?php _e( 'fade',  DSRS ); ?></option>
							<option value="slide"<?php echo ( 'slide' == $selected ) ? ' selected' : ''; ?>><?php _e( 'slide', DSRS ); ?></option>
						</select>
						<p><?php _e( 'Select your animation type, "fade" or "slide"', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-direction">direction</label></th>
					<td>
						<select name="slider-direction" id="slider-direction"<?php $selected = get_option( 'slider-direction' ); ?>>
							<option value="horizontal"<?php echo ( 'horizontal' == $selected ) ? ' selected' : ''; ?>><?php _e( 'horizontal', DSRS ); ?></option>
							<option value="vertical"<?php   echo ( 'vertical'   == $selected ) ? ' selected' : ''; ?>><?php _e( 'vertical',   DSRS ); ?></option>
						</select>
						<p><?php _e( 'Select the sliding direction, "horizontal" or "vertical"', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-animationLoop">animationLoop</label></th>
					<td>
						<select name="slider-animationLoop" id="slider-animationLoop"<?php $selected = get_option( 'slider-animationLoop' ); ?>>
							<option value="true"<?php  echo ( true  == $selected ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
							<option value="false"<?php echo ( false == $selected ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
						</select>
						<p><?php _e( 'Should the animation loop?', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-slideshowSpeed">slideshowSpeed</label></th>
					<td>
						<input type="text" name="slider-slideshowSpeed" id="slider-slideshowSpeed" size="10" value="<?php echo esc_attr( get_option( 'slider-slideshowSpeed' ) ); ?>" /><span class="unit-label">ms</span>
						<p><?php _e( 'Set the speed of the slideshow cycling, in milliseconds', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-animationSpeed">animationSpeed</label></th>
					<td>
						<input type="text" name="slider-animationSpeed" id="slider-animationSpeed" size="10" value="<?php echo esc_attr( get_option( 'slider-animationSpeed' ) ); ?>" /><span class="unit-label">ms</span>
						<p><?php _e( 'Set the speed of animations', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-pauseOnHover">pauseOnHover</label></th>
					<td>
						<select name="slider-pauseOnHover" id="slider-pauseOnHover"<?php $selected = get_option( 'slider-pauseOnHover' ); ?>>
							<option value="true"<?php  echo ( true  == $selected ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
							<option value="false"<?php echo ( false == $selected ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
						</select>
						<p><?php _e( 'Pause the slideshow when hovering over slider, then resume when no longer hovering', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-controlNav">controlNav</label></th>
					<td>
						<select name="slider-controlNav" id="slider-controlNav"<?php $selected = get_option( 'slider-controlNav' ); ?>>
							<option value="true"<?php  echo ( true  == $selected ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
							<option value="false"<?php echo ( false == $selected ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
						</select>
						<p><?php _e( 'Create navigation for paging control of each slide?', DSRS ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="slider-directionNav">directionNav</label></th>
					<td>
						<select name="slider-directionNav" id="slider-directionNav"<?php $selected = get_option( 'slider-directionNav' ); ?>>
							<option value="true"<?php  echo ( true  == $selected ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
							<option value="false"<?php echo ( false == $selected ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
						</select>
						<p><?php _e( 'Create navigation for previous/next navigation?', DSRS ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<p class="submit"><input type="submit" value="<?php echo esc_attr( __( 'Save', DSRS ) ); ?>" class="button button-primary button-large" /></p>
	</form>
</div>
<style>
	#dual-size-responsive-slider .infomation {
		background: #fff;
		padding: 10px;
	}
	.responsive-slider-option tbody {
		vertical-align: top;
	}
	.responsive-slider-option th {
		width: 120px;
	}
	.responsive-slider-option .unit-label {
		padding-left: 10px;
		vertical-align: bottom;
	}
	.slider-option td {
		padding-bottom: 15px;
	}
	@media only screen and (max-width: 400px) {
		.responsive-slider-option.slider-option th, .responsive-slider-option.slider-option td {
			width: 100%;
			display: block;
			text-align: left;
			margin-left: 15px;
		}
	}
</style>
