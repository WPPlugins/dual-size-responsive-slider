<?php
if ( isset( $_GET['post'] ) ) {
	$slide_query1 = new WP_Query( get_responsive_slider_query_parameta( $_GET['post'] ) );
} else {
	$slide_query1 = false;
}
$slide_query2     = new WP_Query( get_responsive_slider_query_parameta() );
?>

<?php wp_nonce_field( DSRS, 'slide_meta_wpnonce' ); ?> 
<input type="hidden" name="slide_parent" value="top" />

<div class="slider-edit-wrap">
	<ol class="slider-edit">
		<?php
		$li_count = 1;
		if ( $slide_query1 && $slide_query1->have_posts() ) :
			while ( $slide_query1->have_posts() ) :
				$slide_query1->the_post();
				$post_id = get_the_ID();
				?> 
				<li data-slide-id="<?php echo esc_attr( $post_id ); ?>" data-slide-number="<?php echo $li_count; ?>">
					<img src="<?php echo esc_url( wp_get_attachment_url( get_post_meta( $post_id, 'large_slide_image', true ) ) ); ?>" height="50" />
					<span class="slide-title"><?php the_title(); ?></span>
					<div class="slide-controller">
						<span class="slide-remove-button"><?php _e( 'Remove', DSRS ); ?></span>
						<span class="slide-move-button"><span class="up-button">▲</span><br /><span class="down-button">▼</span></span>
					</div>
					<input type="hidden" name="slide_children[<?php echo ( $li_count - 1 ); ?>]" value="<?php echo esc_attr( $post_id ); ?>" />
				</li>
				<input type="hidden" name="prev_children[<?php echo ( $li_count - 1 ); ?>]" value="<?php echo esc_attr( $post_id ); ?>" />
				<?php
				$li_count++;
			endwhile;
			wp_reset_postdata();
		endif;
		?> 
	</ol>
	<?php if ( 1 == $li_count ) : ?><p class="slider-no-slide"><?php _e( 'Please Add Slides from below.', DSRS ); ?></p><?php endif; ?> 
</div>

<h4><?php _e( 'Add Slides', DSRS ); ?></h4>
<ul class="add-slides">
	<?php
	$no_slides = true;
	if ( $slide_query2->have_posts() ) :
		while ( $slide_query2->have_posts() ) :
			$slide_query2->the_post();
			$post_id = get_the_ID();
			$img_url = wp_get_attachment_url( get_post_meta( $post_id, 'large_slide_image', true ) );
			if ( $img_url ) :
				?> 
				<li id="noset-slide-<?php echo esc_attr( $post_id ); ?>">
					<input type="checkbox" id="slide-post-<?php echo esc_attr( $post_id ); ?>" class="slide-select" name="slide-post-<?php echo esc_attr( $post_id ); ?>" value="<?php echo esc_attr( $post_id ); ?>" />
					<label for="slide-post-<?php echo esc_attr( $post_id ); ?>" class="slide-item">
						<img src="<?php echo esc_url( $img_url ); ?>" width="100%" />
						<br /><span class="slide-title"><?php the_title(); ?></span>
					</label>
				</li>
				<?php
				$no_slides = false;
			endif;
		endwhile;
		wp_reset_postdata();
	endif;
	?> 
</ul>
<?php if ( $no_slides ) : ?><p class="no-add-slide"><?php _e( 'There is no slide which you can use for this slider.', DSRS ); ?></p><?php endif; ?> 
<p><input type="button" id="add-slides-button" value="<?php _e( 'Add Images to Slider', DSRS ); ?>" onClick="click_add_slide_button( event );" /></p>

<script type="text/javascript">
	var li_html_1  = '<img src="" height="50" />';
	    li_html_1 += '<span class="slide-title"></span>';
	    li_html_1 += '<div class="slide-controller"><span class="slide-remove-button"><?php _e( 'Remove', DSRS ); ?></span>';
	    li_html_1 += '<span class="slide-move-button"><span class="up-button">▲</span><br /><span class="down-button">▼</span></span></div>';
	
	var next_li = <?php echo $li_count; ?>;
	
	var li_html_2 = '<label class="slide-item"><img width="100%" /><br /><span class="slide-title"></span></label>';
	
	function click_add_slide_button( event ) {
		event.preventDefault();
		jQuery( function( $ ) {
			$( '.slide-select:checked' ) . each( function() {
				add_new_slide( $, $( this ) . val() );
			} );
			if ( 0 === $( '.add-slides li' ) . length ) {
				$( '.add-slides' ) . after( '<p class="no-add-slide"><?php _e( 'There is no slide which you can use for this slider.', DSRS ); ?></p>' );
			}
			$( '#add-slides-button' ) . prop( 'disabled', true );
		} );
	}
	

	function add_new_slide( $, post_id ) {
		var new_element = $( '<li>' + li_html_1 + '</li>' );
		var message_element = $( '.slider-no-slide' );
		var checked_element = $( '#noset-slide-' + post_id );
		
		new_element . attr( 'data-slide-id', post_id ) . attr( 'data-slide-number', next_li );
		new_element . find( 'img' ) . attr( 'src', checked_element . find( 'img' ) . attr( 'src' ) );
		new_element . find( '.slide-title' ) . text( checked_element . find( '.slide-title' ) . text() );
		new_element . append( '<input type="hidden" name="slide_children[' + ( next_li - 1 ) + ']" value="' + post_id + '" />' );
		
		if ( 0 !== message_element . length ) {
			message_element . remove();
		}
		$( '.slider-edit' ) . append( new_element );
		checked_element . remove();
		next_li++;
	}

	jQuery( function( $ ) {
		<?php
		// IEの場合、labelタグ内の画像をクリックしてもチェックされないのを修整
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ( strstr( $user_agent, 'Trident' ) || strstr( $user_agent, 'MSIE' ) ) :
		?> 
			$( document ) . on( 'click', '.slide-item img', function() {
				$( '#' + $( this ) . parent( 'label' ) . attr( 'for' ) ) . focus() . click();
			} );
		<?php endif; ?> 

		add_slide_button_disabled();
		
		$( document ) . on( 'change', '.slide-select', function() {
			add_slide_button_disabled();
		} );
		
		$( document ) . on( 'click', '.slide-remove-button', function() {
			remove_slide_item( $( this ) . parents( 'li' ) . attr( 'data-slide-id' ) );
		} );
		
		$( document ) . on( 'click', '.slide-move-button .up-button', function() {
			move_slide_item( $( this ) . parents( 'li' ) . attr( 'data-slide-id' ), 'up' );
		} );
		
		$( document ) . on( 'click', '.slide-move-button .down-button', function() {
			move_slide_item( $( this ) . parents( 'li' ) . attr( 'data-slide-id' ), 'down' );
		} );
		
		function add_slide_button_disabled() {
			if ( 0 === $( '.slide-select:checked' ) . length ) {
				$( '#add-slides-button' ) . prop( 'disabled', true );
			} else {
				$( '#add-slides-button' ) . prop( 'disabled', false );
			}
		}
		
		function remove_slide_item( post_id ) {
			var add_element = $( '<li>' + li_html_2 + '</li>' );
			var removed_element = $( 'li[data-slide-id="' + post_id + '"]' );
			var wrap_element = $( '.slider-edit-wrap' );
			var add_slide_message = $( '.no-add-slide' );
			
			add_element . attr( 'id', 'noset-slide-' + post_id );
			add_element . prepend( '<input type="checkbox" id="slide-post-' + post_id + '" class="slide-select" name="slide-post-' + post_id + '" value="' + post_id + '" />' );
			add_element . find( 'label' ) . attr( 'for', 'slide-post-' + post_id );
			add_element . find( 'img' ) . attr( 'src', removed_element . find( 'img' ) . attr( 'src' ) );
			add_element . find( '.slide-title' ) . text( removed_element . find( '.slide-title' ) . text() );
			
			if ( 2 === next_li ) {
				wrap_element . css( 'height', wrap_element . height() + 'px' );
			}
			removed_element . hide( 'normal', function() {
				for ( var i = Number( $( this ) . attr( 'data-slide-number' ) ) + 1; i < next_li; i++ ) {
					slide_number_rewrite( ( i - 1 ), $( 'li[data-slide-number="' + i + '"]' ) );
				}
				$( this ) . remove();
				$( '.add-slides' ) . append( add_element );
				next_li--;
				
				if ( 1 === next_li ) {
					wrap_element . css( 'height', '' );
					wrap_element . append( '<p class="slider-no-slide"><?php _e( 'Please Add Slides from below.', DSRS ); ?></p>' );
				}
				if ( 0 !== add_slide_message . length ) {
					add_slide_message . remove();
				}
			} );
		}
		
		function move_slide_item( post_id, direction ) {
			var move_element = $( 'li[data-slide-id="' + post_id + '"]' );
			var slide_number  = Number( move_element . attr( 'data-slide-number' ) );
			
			if ( 'up' === direction && 1 !== slide_number ) {
				var before_element = $( 'li[data-slide-number="' + ( slide_number - 1 ) + '"]' );
				before_element . before( move_element );
				slide_number_rewrite( ( slide_number - 1 ), move_element );
				slide_number_rewrite( slide_number, before_element );
			}
			
			else if ( 'down' === direction && ( next_li - 1 ) !== slide_number ) {
				var after_element = $( 'li[data-slide-number="' + ( slide_number + 1 ) + '"]' );
				after_element . after( move_element );
				slide_number_rewrite( slide_number, after_element );
				slide_number_rewrite( ( slide_number + 1 ), move_element );
			}
		}
		
		function slide_number_rewrite( new_number, slide_element ) {
			slide_element . attr( 'data-slide-number', new_number );
			slide_element . find( 'input' ) .attr( 'name', 'slide_children[' + ( new_number - 1 ) + ']' );
		}
		
		<?php if ( ! isset( $_GET['post'] ) ) : ?> 
		$( '#menu-posts-responsive-slider li.current' ) . removeClass( 'current' );
		$( '#menu-posts-responsive-slider a[href="/wp-admin/post-new.php?post_type=responsive-slider&parent=top"]' ) . addClass( 'current' ) . parent( 'li' ) . addClass( 'current' );
		<?php endif; ?> 
	} );
</script>
