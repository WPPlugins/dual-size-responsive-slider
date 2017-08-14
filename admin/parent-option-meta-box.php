<?php
$slider_options   = get_responsive_slider_option( isset( $_GET['post'] ) ? $_GET['post'] : 0 );
?>

<div class="responsive-slider-option slider-option">
	<table>
		<tr>
			<th><label for="slider-option-animation">animation</label></th>
			<td>
				<select name="slider-option[animation]" id="slider-option-animation">
					<option value="fade"<?php  echo ( 'fade'  == $slider_options['animation'] ) ? ' selected' : ''; ?>><?php _e( 'fade',  DSRS ); ?></option>
					<option value="slide"<?php echo ( 'slide' == $slider_options['animation'] ) ? ' selected' : ''; ?>><?php _e( 'slide', DSRS ); ?></option>
				</select>
				<p><?php _e( 'Select your animation type, "fade" or "slide"', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-direction">direction</label></th>
			<td>
				<select name="slider-option[direction]" id="slider-option-direction">
					<option value="horizontal"<?php echo ( 'horizontal' == $slider_options['direction'] ) ? ' selected' : ''; ?>><?php _e( 'horizontal', DSRS ); ?></option>
					<option value="vertical"<?php   echo ( 'vertical'   == $slider_options['direction'] ) ? ' selected' : ''; ?>><?php _e( 'vertical',   DSRS ); ?></option>
				</select>
				<p><?php _e( 'Select the sliding direction, "horizontal" or "vertical"', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-animationLoop">animationLoop</label></th>
			<td>
				<select name="slider-option[animationLoop]" id="slider-option-animationLoop">
					<option value="true"<?php  echo ( true  == $slider_options['animationLoop'] ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
					<option value="false"<?php echo ( false == $slider_options['animationLoop'] ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
				</select>
				<p><?php _e( 'Should the animation loop?', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-slideshowSpeed">slideshowSpeed</label></th>
			<td>
				<input type="text" name="slider-option[slideshowSpeed]" id="slider-option-slideshowSpeed" size="10" value="<?php echo esc_attr( $slider_options['slideshowSpeed'] ); ?>" /><span class="unit-label">ms</span>
				<p><?php _e( 'Set the speed of the slideshow cycling, in milliseconds', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-animationSpeed">animationSpeed</label></th>
			<td>
				<input type="text" name="slider-option[animationSpeed]" id="slider-option-animationSpeed" size="10" value="<?php echo esc_attr( $slider_options['animationSpeed'] ); ?>" /><span class="unit-label">ms</span>
				<p><?php _e( 'Set the speed of animations', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-pauseOnHover">pauseOnHover</label></th>
			<td>
				<select name="slider-option[pauseOnHover]" id="slider-option-pauseOnHover">
					<option value="true"<?php  echo ( true  == $slider_options['pauseOnHover'] ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
					<option value="false"<?php echo ( false == $slider_options['pauseOnHover'] ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
				</select>
				<p><?php _e( 'Pause the slideshow when hovering over slider, then resume when no longer hovering', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-controlNav">controlNav</label></th>
			<td>
				<select name="slider-option[controlNav]" id="slider-option-controlNav">
					<option value="true"<?php  echo ( true  == $slider_options['controlNav'] ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
					<option value="false"<?php echo ( false == $slider_options['controlNav'] ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
				</select>
				<p><?php _e( 'Create navigation for paging control of each slide?', DSRS ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="slider-option-directionNav">directionNav</label></th>
			<td>
				<select name="slider-option[directionNav]" id="slider-option-directionNav">
					<option value="true"<?php  echo ( true  == $slider_options['directionNav'] ) ? ' selected' : ''; ?>><?php _e( 'Yes', DSRS ); ?></option>
					<option value="false"<?php echo ( false == $slider_options['directionNav'] ) ? ' selected' : ''; ?>><?php _e( 'No',  DSRS ); ?></option>
				</select>
				<p><?php _e( 'Create navigation for previous/next navigation?', DSRS ); ?></p>
			</td>
		</tr>
	</table>
</div>
