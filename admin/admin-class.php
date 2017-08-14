<?php

/*
 * 管理画面のみに用いる関数をクラスにまとめる
 */

class DSRS_Admin {
	
	private $version = '';
	
	public function __construct( $version = '' ) {
		$this->version = $version;
	}

/* オプション設定 ここから */

	/* 管理画面のメニューに表示 */
	public function admin_menu() {
		global $submenu;
		$submenu['edit.php?post_type=responsive-slider'][11] = array(
			__( 'Add New Set', DSRS ),
			'edit_posts',
			'/wp-admin/post-new.php?post_type=responsive-slider&parent=top',
		);
		ksort( $submenu['edit.php?post_type=responsive-slider'], SORT_NUMERIC );
		
		add_options_page(
			__( 'Responsive Slider', DSRS ),
			__( 'Responsive Slider', DSRS ),
			'manage_options',
			'dual-size-responsive-slider',
			array( $this, 'options_page' )
		);
	}

	/* ページの中身は別ファイル */
	public function options_page() {
		include_once 'option-page.php';
	}
	
	/* オプション画面からPOST送信があった場合、slide_option_saveを呼び出す */
	public function admin_init() {
		if ( isset( $_POST['slider_option_wpnonce'] ) && $_POST['slider_option_wpnonce'] ) {
			if ( check_admin_referer( 'dual-size-responsive-slider', 'slider_option_wpnonce' ) ) {
				$this->slider_options_save();
			}
		}
	}

	public function slider_options_save() {
		$e = new WP_Error();
		
		/* オプションキーの定義 登録値が数値になる場合はエラー文も定義 */
		$numeric_key_and_errors = array(
			'large-slider-width'             => array( 'field' => __( 'Large Slider Size', DSRS ), 'key' => __( 'width',  DSRS ) ),
			'large-slider-height'            => array( 'field' => __( 'Large Slider Size', DSRS ), 'key' => __( 'height', DSRS ) ),
			'small-slider-width'             => array( 'field' => __( 'Small Slider Size', DSRS ), 'key' => __( 'width',  DSRS ) ),
			'small-slider-height'            => array( 'field' => __( 'Small Slider Size', DSRS ), 'key' => __( 'height', DSRS ) ),
			'responsive-slider-change-width' => array( 'field' => __( 'Responsive Option', DSRS ), 'key' => __( 'Threshold Width', DSRS ) ),
			'slider-slideshowSpeed'          => array( 'field' => __( 'Slider Option', DSRS ),     'key' => 'slideshowSpeed' ),
			'slider-animationSpeed'          => array( 'field' => __( 'Slider Option', DSRS ),     'key' => 'animationSpeed' ),
		);
		$select_option_keys = array(
			'slider-animation',
			'slider-direction',
			'slider-animationLoop',
			'slider-pauseOnHover',
			'slider-controlNav',
			'slider-directionNav',
		);
		
		/* 別の関数でオプションを保存 */
		foreach ( $numeric_key_and_errors as $key => $error_texts ) {
			$this->slider_numeric_option_save( $key, $error_texts, $e );
		}
		foreach ( $select_option_keys as $key ) {
			$this->slider_select_option_save( $key, $e );
		}
		
		/* エラー文がある場合は、トランジエントに保存 */
		$messages = $e->get_error_messages();
		if ( ! empty( $messages ) ) {
			set_transient( 'responsive-slider-admin-errors', $messages, 5 );
		}
		
		/* 更新したこと示す文をトランジエントに保存 */
		set_transient( 'responsive-slider-admin-save', array( __( 'Option is updated.', DSRS ) ), 5 );

		/* 安全なリダイレクト */
		wp_safe_redirect( menu_page_url( 'dual-size-responsive-slider', false ) );
	}

	/* 数値のオプション値を保存する関数 エラー文あり */
	public function slider_numeric_option_save( $option_key = '', $error_texts = array( 'field' => '', 'key' => '' ), $error_object = array() ) {
		if ( isset( $_POST[ $option_key ] ) && $_POST[ $option_key ] ) {
			if ( is_numeric( trim( $_POST[ $option_key ] ) ) ) {
				update_option( $option_key, trim( $_POST[ $option_key ] ) );
			} else {
				$error_object->add(
					'error',
					sprintf( __( 'Please enter a numeric value in %1$s for %2$s.', DSRS ), $error_texts['key'], $error_texts['field'] )
				);
			}
		} else {
			update_option( $option_key, '' );
		}
	}

	/* セレクトボックスからのオプション値を保存する関数 */
	public function slider_select_option_save( $option_key = '', $error_object = array() ) {
		if ( isset( $_POST[ $option_key ] ) && $_POST[ $option_key ] ) {
			$save_value = trim( $_POST[ $option_key ] );
			if ( 'true' == $save_value ) {
				$save_value = true;
			}
			elseif ( 'false' == $save_value ) {
				$save_value = false;
			}
			update_option( $option_key, $save_value );
		}
	}
/* オプション設定 ここまで */
	
/* 投稿画面のメタボックス ここから */
	
	/* プラグインが作成したカスタム投稿にメタボックスを生成 */
	public function add_custom_box() {
		$post_type = '';
		if ( isset( $_GET['post_type'] ) ) {
			$post_type = $_GET['post_type'];
		} elseif ( isset( $_GET['post'] ) ) {
			$post_type = get_post_type( $_GET['post'] );
		}
		
		if ( 'responsive-slider' === $post_type ) {
			// メタボックス用のCSSファイル読み込み
			wp_register_style(
				'slide-metabox',
				plugins_url( 'metabox.css', __FILE__ ),
				array(),
				$this->version,
				'all'
			);
			wp_enqueue_style( 'slide-metabox' );
		// 投稿の種類により読み込むメタボックスを変化
			if ( $this->is_parent_post() ) {
				add_meta_box(
					'responsive-slider-parent-field',
					__( 'Slider Set', DSRS ),
					array( $this, 'slide_parent_box' ),
					'responsive-slider',
					'normal'
				);
				add_meta_box(
					'responsive-slider-option-field',
					__( 'Slider Option', DSRS ),
					array( $this, 'slide_option_meta' ),
					'responsive-slider',
					'normal'
				);
				remove_meta_box( 'pageparentdiv', 'responsive-slider', 'advanced' );
			} else {
				add_meta_box(
					'responsive-slider-custom-field',
					__( 'Slide Images', DSRS ),
					array( $this, 'slide_meta_box' ),
					'responsive-slider',
					'normal'
				);
			}
			add_filter( 'get_sample_permalink_html' , array( $this, 'how_to_use_text' ) );
			add_filter( 'get_shortlink' , '__return_false' );
		}
	}

	/* メタボックスの中身のHTMLは別ファイル */
	public function slide_parent_box() {
		include_once 'parent-box.php';
	}
	
	public function slide_option_meta() {
		include_once 'parent-option-meta-box.php';
	}
	
	public function slide_meta_box() {
		include_once 'slide-metabox.php';
	}
	
	/* パーマリンクの代わりにスライダーの表示コードを出力 */
	public function how_to_use_text() {
		$return_html = '';
		if ( $this->is_parent_post() && isset( $_GET['post'] ) ) {
			$shortcode   = '<code>' . sprintf( '[responsive-slider id=%s]', esc_html( $_GET['post'] ) ) . '</code>';
			$function    = '<code>' . esc_html( sprintf( '<?php responsive_slider( %s ); ?>', esc_html( $_GET['post'] ) ) ) . '</code>';
			$return_html = sprintf( __( 'Using shortcode %s or function %s, you can display this slider.', DSRS ), $shortcode, $function );
			$return_html = '<p class="information">' . $return_html . '</p>';
		}
		return $return_html;
	}
	
	/* メタボックスからPOSTデータが送られてきたかを確認 */
	public function check_meta_value( $post_id ) {
	
		// 重複フック回避
		remove_action( 'save_post', array( $this, 'check_meta_value' ) );

		// nonceチェック
		if ( !isset( $_POST['slide_meta_wpnonce'] ) ) {
			return $post_id;
		}
		if ( ! wp_verify_nonce( $_POST['slide_meta_wpnonce'], DSRS ) ) {
			return $post_id;
		}

		// 自動保存のルーチンかチェック
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		
		// 権限チェック
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		if ( 'responsive-slider' === $post_type ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		} else {
			return $post_id;
		}

		if ( $this->is_parent_post() ) {
			$this->save_parent_value( $post_id );
		} else {
			$this->save_meta_value( $post_id );
		}
	}
	
	/* スライダーセットの保存 */
	public function save_parent_value( $post_id = 0 ) {
		$e = new WP_Error();
		
		// 親の識別用カスタムフィールドの保存
		if ( isset( $_POST['slide_parent'] ) ) {
			update_post_meta( $post_id, 'slide_parent', $_POST['slide_parent'] );
		}
		
		$new_children  = ( isset( $_POST['slide_children'] ) && is_array( $_POST['slide_children'] ) ) ? $_POST['slide_children'] : array();
		
		// 親子関係の解除
		if ( isset( $_POST['prev_children'] ) && is_array( $_POST['prev_children'] ) ) {
			foreach ( $_POST['prev_children'] as $prev_child ) {
				if ( ! in_array( $prev_child, $new_children ) ) {
					$prev_child_post = get_post( $prev_child );
					$prev_child_post->post_parent = 0;
					$prev_child_post->menu_order  = 0;
					$update_id = wp_update_post( $prev_child_post, false );
					$e->add(
						'save',
						sprintf( __( 'Parent Attribute of Slide %1$s (post ID: %2$s) is also updated.', DSRS ), $prev_child_post->post_title, $prev_child_post->ID )
					);
				}
			}
		}
		
		// 親子関係の保存
		if ( ! empty( $new_children ) ) {
			$post_order = get_post( $post_id )->menu_order;
			foreach ( $new_children as $key => $child_id ) {
				$child_post = get_post( $child_id );
				if ( $child_post->post_parent != $post_id || $child_post->menu_order != ( $post_order + $key + 1 ) ) {
					$child_post->post_parent = $post_id;
					$child_post->menu_order  = $post_order + $key + 1;
					$update_id = wp_update_post( $child_post, false );
					$e->add(
						'save',
						sprintf( __( 'Parent Attribute of Slide %1$s (post ID: %2$s) is also updated.', DSRS ), $child_post->post_title, $child_post->ID )
					);
				}
			}
		}
		
		// スライドオプション
		if ( isset( $_POST['slider-option'] ) && is_array( $_POST['slider-option'] ) ) {
			foreach ( $_POST['slider-option'] as $option_key => $option_value ) {
				if ( 'true' == $option_value ) {
					$option_value = true;
				} elseif ( 'false' == $option_value ) {
					$option_value = false;
				}
//				if ( get_option( 'slider-' . $option_key ) != $option_value ) {
					update_post_meta( $post_id, $option_key, $option_value );
//				} else {
//					delete_post_meta( $post_id, $option_value );
//				}
			}
		}
		
		$messages = $e->get_error_messages();
		if ( ! empty( $messages ) ) {
			set_transient( 'responsive-slider-admin-save', $messages, 5 );
		}
	}
	
	/* スライドのカスタムフィールドを保存 */
	public function save_meta_value( $post_id = 0 ) {
		$large_slide = isset( $_POST['large_slide_image'] ) ? $_POST['large_slide_image'] : '';
		$small_slide = isset( $_POST['small_slide_image'] ) ? $_POST['small_slide_image'] : '';
		$slide_link = isset( $_POST['slide_link'] ) ? $_POST['slide_link'] : '';
		
		// 画像のアタッチメントIDを保存
		if ( ! empty( $large_slide ) ) {
			update_post_meta( $post_id, 'large_slide_image', $large_slide );
		} else {
			delete_post_meta( $post_id, 'large_slide_image' );
		}
		if ( ! empty( $small_slide ) ) {
			update_post_meta( $post_id, 'small_slide_image', $small_slide );
		} else {
			delete_post_meta( $post_id, 'small_slide_image' );
		}
		
		// リンク情報を保存
		if ( ! empty( $slide_link ) ) {
			update_post_meta( $post_id, 'slide_link', $slide_link );
			if ( isset( $_POST['slide_link_target'] ) && '_blank' == $_POST['slide_link_target'] ) {
				update_post_meta( $post_id, 'slide_link_target', true );
			} else {
				delete_post_meta( $post_id, 'slide_link_target' );
			}
		} else {
			delete_post_meta( $post_id, 'slide_link' );
			if ( get_post_meta( $post_id, 'slide_link_target', true ) ) {
				delete_post_meta( $post_id, 'slide_link_target' );
			}
		}

		return $large_slide;
	}
	
/* 投稿画面のメタボックス ここまで */

	/* 保存後のメッセージ表示 */
	public function admin_notices() {
		$error_messages  = get_transient( 'responsive-slider-admin-errors' );
		$success_message = get_transient( 'responsive-slider-admin-save' );
		if ( ! empty( $error_messages ) ) {
			?> 
			<div class="error">
				<ul>
					<?php foreach ( $error_messages as $message ) : ?> 
						<li><?php echo esc_html( $message ) ?></li>
					<?php endforeach; ?> 
				</ul>
			</div>
			<?php
		}
		if ( ! empty( $success_message ) ) {
			?> 
			<div class="updated">
				<ul>
					<?php foreach ( $success_message as $message ) : ?> 
						<li><?php echo esc_html( $message ) ?></li>
					<?php endforeach; ?> 
				</ul>
			</div>
			<?php
		}
	}
	
	/* 投稿一覧の行タイトルに thumb を配列キーとした dashicon を追加 */
	public function manage_posts_columns( $columns ) {
		global $post_type;
		if( 'responsive-slider' !== $post_type ) {
			return $columns;
		}

		// 画像のスタイルのついでに「表示」ボタンを隠す
		echo '<style>.column-thumb{width:100px; vertical-align: middle!important; } .row-actions .view{ display: none; }</style>';

		$new_columns = array(
			'cb'    => $columns['cb'],
			'thumb' => '<div class="dashicons dashicons-format-image"></div>',
			'title' => $columns['title'],
		);
		unset( $columns['cb'], $columns['title'] );
		$columns = array_merge( $new_columns, $columns );

		return $columns;
	}

	/* スライド画像一覧に画像出力 */
	public function manage_posts_custom_column( $column, $post_id ) {
		global $post_type;
		if( 'responsive-slider' !== $post_type ) {
			remove_filter( 'manage_posts_columns', array( $this, 'manage_posts_custom_column' ) );
			return;
		}
		
		switch ( $column ) {
			case 'thumb':
				if ( $img_id = get_post_meta( $post_id, 'small_slide_image', true ) ) {
					$image = wp_get_attachment_image_src( $img_id, 'thumbnail' );
					echo '<img src="' . esc_url( $image[0] ) . '" width="80" />';
				} elseif ( $img_id = get_post_meta( $post_id, 'large_slide_image', true ) ) {
					$image = wp_get_attachment_image_src( $img_id, 'thumbnail' );
					echo '<img src="' . esc_url( $image[0] ) . '" width="80" />';
				}
				break;
			default:
				break;
		}
	}

	/*  */
	public function is_parent_post() {
		if ( isset( $_GET['parent'] ) && $_GET['parent'] ) {
			return ( 'top' == $_GET['parent'] ) ? true : false;
		} elseif ( isset( $_POST['slide_parent'] ) && $_POST['slide_parent'] ) {
			return ( 'top' == $_POST['slide_parent'] ) ? true : false;
		} elseif ( isset( $_GET['post'] ) && $_GET['post'] ) {
			$parent_meta = get_post_meta( $_GET['post'], 'slide_parent', true );
			$parent_posts = get_ancestors( $_GET['post'], 'responsive-slider' );
			return ( 'top' == $parent_meta && 0 == count( $parent_posts ) ) ? true : false;
		} else {
			return false;
		}
	}
	
}

