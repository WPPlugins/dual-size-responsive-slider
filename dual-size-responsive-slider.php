<?php

/* 
 * Plugin Name: Dual Size Responsive Slider
 * Description: This plugin displays slide show for responsive using FlexSlider2. You can set 2 images its size is different, size for PC and smart phone.
 * Version: 1.2.0
 * Author: Hiroki Kanazawa
 * License: GPLv2
 * Domain Path: /languages
 */

define( 'DSRS_PLUGIN_FILE', __FILE__ );
define( 'DSRS_PLUGIN_PATH', dirname(__FILE__) );
define( 'DSRS', dirname( plugin_basename( __FILE__ ) ) );

register_activation_hook(__FILE__, 'dual_size_responsive_slider_activate');

/* 管理画面専用ファイルあり */
if ( is_admin() ) {
	include_once 'admin/admin-class.php';
}

$dsrs_class = new Dual_Size_Responsive_Slider();
$dsrs_class->register();

add_action( 'init', array( $dsrs_class, 'init_register' ) );

/* オプション値の初期値を登録 */
if ( ! function_exists( 'dual_size_responsive_slider_activate' ) ) {
	function dual_size_responsive_slider_activate() {
		$key_and_init = array(
			'large-slider-width'             => 930,
			'large-slider-height'            => 300,
			'small-slider-width'             => 650,
			'small-slider-height'            => 300,
			'responsive-slider-change-width' => 650,
			'slider-animation'               => 'fade',
			'slider-direction'               => 'horizontal',
			'slider-animationLoop'           => true,
			'slider-slideshowSpeed'          => 7000,
			'slider-animationSpeed'          => 600,
			'slider-pauseOnHover'            => false,
			'slider-controlNav'              => true,
			'slider-directionNav'            => true,
		);
		foreach ( $key_and_init as $key => $value ) {
			if( ! get_option( $key ) ) {
				update_option( $key, $value );
			}
		}
	}
}


class Dual_Size_Responsive_Slider {

	private $version = '';
	private $langs   = '';

	public function __construct() {
		$data = get_file_data(
			__FILE__,
			array( 'ver' => 'Version', 'langs' => 'Domain Path' )
		);
		$this->version = $data['ver'];
		$this->langs   = $data['langs'];
	}

	public function register() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	/* スライド画像はカスタム投稿タイプで登録 */
	public function init_register() {
		$labels = array(
			'name'               => _x( 'Slider', 'post type general name', DSRS ),
			'singular_name'      => _x( 'Slide', 'post type singular name', DSRS ),
			'menu_name'          => _x( 'Slider', 'admin menu', DSRS ),
			'name_admin_bar'     => _x( 'Slider', 'add new on admin bar', DSRS ),
			'add_new'            => _x( 'Add New Slide', 'slider', DSRS ),
			'add_new_item'       => __( 'Add New', DSRS ),
			'new_item'           => __( 'New Slide', DSRS ),
			'edit_item'          => __( 'Edit', DSRS ),
			'view_item'          => __( 'View Slider', DSRS ),
			'all_items'          => __( 'All Slider', DSRS ),
			'search_items'       => __( 'Search Slides', DSRS ),
			'parent_item_colon'  => __( 'Parent Slider:', DSRS ),
			'not_found'          => __( 'No books found.', DSRS ),
			'not_found_in_trash' => __( 'No books found in Trash.', DSRS ),
		);
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => true,
			'menu_position'      => null,
			'supports'           => array( 'title', 'page-attributes' ),
		);
		register_post_type( 'responsive-slider', $args );
		
		/* オプションで設定した画像サイズにトリミングするように */
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'large-slider', get_option( 'large-slider-width' ), get_option( 'large-slider-height' ), array( 'center', 'center' ) );
			add_image_size( 'small-slider', get_option( 'small-slider-width' ), get_option( 'small-slider-height' ), array( 'center', 'center' ) );
		}
	}

	public function plugins_loaded() {
		load_plugin_textdomain(
			DSRS,
			false,
			dirname( plugin_basename(__FILE__) ) . $this->langs
		);

		if ( is_admin() ) {
			global $pagenow;
			$dsrs_admin = new DSRS_Admin( $this->version );
			add_action( 'admin_menu', array( $dsrs_admin, 'admin_menu' ) );
			add_action( 'admin_init', array( $dsrs_admin, 'admin_init' ) );
			add_action( 'admin_notices', array( $dsrs_admin, 'admin_notices' ) );
			if ( 'post-new.php' == $pagenow || 'post.php' == $pagenow ) {
				add_action( 'admin_menu', array( $dsrs_admin, 'add_custom_box' ) );
				add_action( 'save_post', array( $dsrs_admin, 'check_meta_value' ) );
			} elseif ( 'edit.php' == $pagenow ) {
				add_filter( 'manage_posts_columns', array( $dsrs_admin, 'manage_posts_columns' ), 15 );
				add_filter( 'manage_pages_custom_column', array( $dsrs_admin, 'manage_posts_custom_column' ), 10, 2 );
			}
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
			add_action( 'wp_head', array( $this, 'slider_load_script' ) );
		}
	}
	
	public function enqueue_script() {
		wp_register_script(
				'jquery-flexslider',
				plugins_url( 'js/jquery.flexslider-min.js', __FILE__ ),
				array( 'jquery' ),
				'2.6.0',
				false
		);
		wp_enqueue_script( 'jquery-flexslider' );
		wp_register_style(
				'flexslider',
				plugins_url( 'js/flexslider.css', __FILE__ ),
				array(),
				'2.6.0',
				'all'
		);
		wp_enqueue_style( 'flexslider' );
	}
	
	public function slider_load_script() {
		?>

<script type="text/javascript">
	jQuery( function( $ ) {
		var slider = $(".flexslider");
		if ( slider.length > 0 ) {
			var window_size = '';
			var slide_images = $(".flexslider img");
			var img_src_rewrite = function() {
				if (  'large' !== window_size && window.innerWidth > <?php echo esc_js( get_option( 'responsive-slider-change-width' ) ); ?> ) {
					slide_images.each( function() {
						$( this ).attr( "src", $( this ).data( "large-slide" ) );
					} );
					window_size = 'large';
				} else if ( 'small' !== window_size && window.innerWidth < <?php echo esc_js( get_option( 'responsive-slider-change-width' ) ); ?> ) {
					slide_images.each( function() {
						$( this ).attr( "src", $( this ).data( "small-slide" ) );
					} );
					window_size = 'small';
				}
			}
			img_src_rewrite();
			$( window ).load( function() {
				slider.flexslider( slider.data( "slider-option" ) );
			} );
			$( window ).on( 'resize', img_src_rewrite );
		}
	} );
</script>

		<?php
	}
}


/* スライドをクエリで取得する際のパラメータ配列を生成する関数 */
if ( ! function_exists( 'get_responsive_slider_query_parameta' ) ) {
	function get_responsive_slider_query_parameta( $post_id = 0 ) {
		$args = array(
			'post_type'      => 'responsive-slider',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);
		if ( is_admin() ) {
			$args['post_status'] = 'any';
		}
		if ( 0 == $post_id ) {
			$args['meta_query'] = array(
				'key'     => 'slide_parent',
				'value'   => 'top',
				'compare' => '!=',
			);
			if ( is_admin() ) {
				$args['post_parent'] = 0;
			}
		} else {
			$args['post_parent'] = $post_id;
			$args['orderby']     = 'menu_order';
			$args['order']       = 'ASC';
		}
		return $args;
	}
}


/* スライドのオプションを配列で取得する関数 */
if ( ! function_exists( 'get_responsive_slider_option' ) ) {
	function get_responsive_slider_option( $post_id = 0 ) {
		
		$slider_options = array(
			'animation'      => get_option( 'slider-animation' ),
			'direction'      => get_option( 'slider-direction' ),
			'animationLoop'  => get_option( 'slider-animationLoop' ),
			'slideshowSpeed' => get_option( 'slider-slideshowSpeed' ),
			'animationSpeed' => get_option( 'slider-animationSpeed' ),
			'pauseOnHover'   => get_option( 'slider-pauseOnHover' ),
			'controlNav'     => get_option( 'slider-controlNav' ),
			'directionNav'   => get_option( 'slider-directionNav' ),
		);
		
		if ( 0 != $post_id ) {
			$meta_values = get_post_meta( $post_id );
			foreach ( $meta_values as $meta_key => $meta_value ) {
				if ( isset( $slider_options[ $meta_key ] ) ) {
					$slider_options[ $meta_key ] = array_shift( $meta_value );
				}
			}
		}
		return $slider_options;
	}
}


/* スライダーを表示する関数 */
if ( ! function_exists( 'responsive_slider' ) ) {
	function responsive_slider( $slider_id = 0 ) {

		$slide_query = new WP_Query( get_responsive_slider_query_parameta( $slider_id ) );

		// スライドリストのHTMLを生成
		if ( $slide_query->have_posts() ) {
			
			echo "<div class='flexslider' data-slider-option='", json_encode( get_responsive_slider_option( $slider_id ) ), "' style='line-height: 1;", ( $slide_query->found_posts === 1 ? " margin-bottom: 0;" : "" ), "'>\n";
			echo "\t<ul class='slides'>\n";
			
			$img_format = "\t\t\t<img class='slide-image-%s' alt='%s' data-large-slide='%s' data-small-slide='%s' />\n";
			$a_format   = "\t\t\t<a href='%s'%s>\n%s\t\t\t</a>\n";

			for ( $i = 0; $i < $slide_query->found_posts; $i++ ) {
				$slide_query->the_post();
				$post_id = get_the_ID();
				$html    = '';

				// 画像データ読み込み
				$large_id = get_post_meta( $post_id, 'large_slide_image', true );
				$small_id = get_post_meta( $post_id, 'small_slide_image', true );
				if ( '' != $large_id && '' == $small_id ) {
					$small_id = $large_id;
				}
				elseif ( '' == $large_id && '' != $small_id ) {
					$large_id = $small_id;
				}
				$large_images = wp_get_attachment_image_src( $large_id, 'large-slider' );
				$small_images = wp_get_attachment_image_src( $small_id, 'small-slider' );
				$html        .= sprintf( $img_format, $i, esc_attr( get_the_title() ), esc_url( is_array( $large_images ) ? $large_images[0] : '' ), esc_url( is_array( $small_images ) ? $small_images[0] : '' ) );
				
				// リンク情報を読み込み
				$slide_link = get_post_meta( $post_id, 'slide_link', true );
				if ( '' != $slide_link ) {
					$html = sprintf( $a_format, esc_url( $slide_link ), get_post_meta( $post_id, 'slide_link_target', true ) ? " target='_blank'" : '', "\t" . $html );
				}

				echo "\t\t<li>\n", $html, "\t\t</li>\n";
			}
			wp_reset_postdata();
			
			echo "\t</ul>\n";
			echo "</div>\n";
			
		} else {
			echo '<!-- ',  __('There is no slide', DSRS), ' -->';
		}
	}
}


/* スライダーをショートコードで表示する場合の関数 */
if ( ! function_exists( 'shortcode_responsive_slider' ) ) {
	function shortcode_responsive_slider( $atts ) {
		$default_atts = array(
			'id' => 0,
		);
		$marged_atts = shortcode_atts( $default_atts, $atts );
		extract( $marged_atts );
		responsive_slider( $id );
	}
	
	add_shortcode( 'responsive-slider', 'shortcode_responsive_slider' );
}
