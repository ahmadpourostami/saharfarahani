<?php
/** Dashboard editor for learning path cards. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sf_register_homepage_settings() {
	register_setting( 'sf_homepage_group', 'sf_learning_paths', array( 'type' => 'array', 'sanitize_callback' => 'sf_sanitize_learning_paths', 'default' => array() ) );
}
add_action( 'admin_init', 'sf_register_homepage_settings' );

function sf_add_homepage_admin_menu() {
	add_theme_page( __( 'صفحه اصلی سحر فراهانی', 'saharfarahani' ), __( 'صفحه اصلی سحر', 'saharfarahani' ), 'edit_theme_options', 'sf-homepage', 'sf_render_homepage_admin' );
}
add_action( 'admin_menu', 'sf_add_homepage_admin_menu' );

function sf_sanitize_learning_paths( $input ) {
	$output = array();
	if ( ! is_array( $input ) ) { return $output; }
	for ( $i = 1; $i <= 8; $i++ ) {
		$item = isset( $input[ $i ] ) && is_array( $input[ $i ] ) ? $input[ $i ] : array();
		$output[ $i ] = array(
			'image' => isset( $item['image'] ) ? absint( $item['image'] ) : 0,
			'title' => isset( $item['title'] ) ? sanitize_text_field( $item['title'] ) : '',
			'text' => isset( $item['text'] ) ? sanitize_textarea_field( $item['text'] ) : '',
			'button_text' => isset( $item['button_text'] ) ? sanitize_text_field( $item['button_text'] ) : '',
			'button_url' => isset( $item['button_url'] ) ? esc_url_raw( $item['button_url'] ) : '',
		);
	}
	return $output;
}

function sf_render_homepage_admin() {
	if ( ! current_user_can( 'edit_theme_options' ) ) { return; }
	$items = sf_get_path_items();
	wp_enqueue_media();
	?>
	<div class="wrap" dir="rtl">
		<h1><?php esc_html_e( 'مسیر «اگر می‌خواهید بازیگر شوید»', 'saharfarahani' ); ?></h1>
		<p><?php esc_html_e( 'برای هر مرحله تصویر آیکن، عنوان، توضیح، متن دکمه و لینک را دستی وارد کنید. تصویر انتخابی در صفحه اصلی به صورت آیکن دایره‌ای نمایش داده می‌شود.', 'saharfarahani' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'sf_homepage_group' ); ?>
			<?php for ( $i = 1; $i <= 8; $i++ ) : $item = isset( $items[ $i ] ) ? $items[ $i ] : array( 'image' => 0, 'title' => '', 'text' => '', 'button_text' => '', 'button_url' => '' ); $image = ! empty( $item['image'] ) ? wp_get_attachment_image_url( $item['image'], 'thumbnail' ) : ''; ?>
				<div style="background:#fff;border:1px solid #dcdcde;border-radius:12px;padding:20px;margin:0 0 16px;max-width:900px;">
					<h2><?php echo esc_html( sprintf( __( 'مرحله %d', 'saharfarahani' ), $i ) ); ?></h2>
					<div style="display:grid;grid-template-columns:120px 1fr;gap:18px;align-items:start;">
						<div>
							<div class="sf-image-preview" data-preview="<?php echo esc_attr( $i ); ?>" style="width:110px;height:110px;background:#f2f2f2;border-radius:50%;overflow:hidden;margin-bottom:8px;">
								<?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" style="width:100%;height:100%;object-fit:cover;"><?php endif; ?>
							</div>
							<input type="hidden" class="sf-image-id" name="sf_learning_paths[<?php echo esc_attr( $i ); ?>][image]" value="<?php echo esc_attr( $item['image'] ); ?>">
							<button type="button" class="button sf-upload-image" data-target="<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'انتخاب تصویر آیکن', 'saharfarahani' ); ?></button>
						</div>
						<div>
							<p><label><strong>عنوان مرحله</strong></label><br><input class="regular-text" type="text" name="sf_learning_paths[<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>" style="width:100%;"></p>
							<p><label><strong>توضیح کوتاه</strong></label><br><textarea name="sf_learning_paths[<?php echo esc_attr( $i ); ?>][text]" rows="2" style="width:100%;"><?php echo esc_textarea( $item['text'] ); ?></textarea></p>
							<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
								<p><label><strong>متن دکمه</strong></label><br><input type="text" name="sf_learning_paths[<?php echo esc_attr( $i ); ?>][button_text]" value="<?php echo esc_attr( $item['button_text'] ); ?>" style="width:100%;"></p>
								<p><label><strong>لینک دکمه</strong></label><br><input type="url" name="sf_learning_paths[<?php echo esc_attr( $i ); ?>][button_url]" value="<?php echo esc_attr( $item['button_url'] ); ?>" style="width:100%;" dir="ltr"></p>
							</div>
						</div>
					</div>
				</div>
			<?php endfor; ?>
			<?php submit_button( __( 'ذخیره مسیر یادگیری', 'saharfarahani' ) ); ?>
		</form>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.sf-upload-image').forEach(function(button) {
			button.addEventListener('click', function() {
				const index = this.dataset.target;
				const frame = wp.media({title: 'انتخاب تصویر آیکن', button: {text: 'استفاده از تصویر'}, multiple: false});
				frame.on('select', function() {
					const attachment = frame.state().get('selection').first().toJSON();
					document.querySelector('[data-preview="' + index + '"]').innerHTML = '<img src="' + attachment.url + '" style="width:100%;height:100%;object-fit:cover;">';
					document.querySelector('[name="sf_learning_paths[' + index + '][image]"]').value = attachment.id;
				});
				frame.open();
			});
		});
	});
	</script>
	<?php
}
