<?php
/** Footer. */
if ( function_exists( 'block_template_part' ) ) {
	block_template_part( 'footer' );
} else {
	?>
	<footer class="sf-footer">
		<div class="sf-container">
			<div class="sf-footer__top">
				<div><div class="sf-footer__brand">سحر فراهانی</div><p><?php echo esc_html( sf_get_mod( 'sf_footer_text', get_bloginfo( 'description' ) ) ); ?></p></div>
			<nav aria-label="<?php esc_attr_e( 'منوی فوتر', 'saharfarahani' ); ?>"><?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'sf-footer-menu', 'fallback_cb' => false ) ); ?></nav>
			</div>
			<div class="sf-footer__bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> سحر فراهانی</span><span><?php echo esc_html( sf_get_mod( 'sf_footer_credit', 'طراحی و توسعه با WordPress' ) ); ?></span></div>
		</div>
	</footer>
	<?php
}
wp_footer();
?>
</body>
</html>
