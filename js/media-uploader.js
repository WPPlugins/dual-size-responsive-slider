
var large_slide_uploader;
var small_slide_uploader;

function responsive_large_slide_image_uploader( e, choose_text ) {
	e . preventDefault();
	if ( large_slide_uploader ) {
		large_slide_uploader . open();
		return;
	}
	large_slide_uploader = wp . media({
		title: choose_text,
		library: {
			type: 'image'
		}, 
		button: {
			text: choose_text
		},
		multiple: false // falseにすると画像を1つしか選択できなくなる
	});
	large_slide_uploader . on( 'select', function() {
		var images = large_slide_uploader . state() . get( 'selection' );
		images . each( function( file ) {
			slide_image_preview( 'large_slide_image', file );
		} );
	} );
	large_slide_uploader . open();
}

function responsive_small_slide_image_uploader( e, choose_text ) {
	e . preventDefault();
	if ( small_slide_uploader ) {
		small_slide_uploader . open();
		return;
	}
	small_slide_uploader = wp . media({
		title: choose_text,
		library: {
			type: 'image'
		}, 
		button: {
			text: choose_text
		},
		multiple: false // falseにすると画像を1つしか選択できなくなる
	});
	small_slide_uploader . on( 'select', function() {
		var images = small_slide_uploader . state() . get( 'selection' );
		images . each( function( file ) {
			slide_image_preview( 'small_slide_image', file );
		} );
	} );
	small_slide_uploader . open();
}

function slide_image_preview( key, file ) {
	var preview_element = jQuery( '#preview-' + key );
	    preview_element . empty();
	    preview_element . append( '<img src="' + file . attributes . url + '" width="' + preview_element . data( 'slide-width' ) + '%" />' );
	jQuery( '#' + key ) . attr( 'value', file . id );
	jQuery( '#' + key + '-unset' ) . prop( 'disabled', false );
}

function responsive_slider_image_unset( key ) {
	jQuery( '#preview-' + key ) . empty();
	jQuery( '#' + key ) . attr( 'value', '' );
	jQuery( '#' + key + '-unset' ) . prop( 'disabled', true );
}
