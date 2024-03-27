<div id="footer">
<?php 
if (! is_page_template('templates/no-widgets-or-comments.php') ){ //This template should not show the widgets in the footer
	if (is_active_sidebar(2)){
		dynamic_sidebar(2);
	}
}
	
if( get_theme_mod( 'kvarken_logo_footer' ) <> '') {
	if( get_theme_mod( 'kvarken_logo' ) ) {
		if( get_theme_mod( 'kvarken_logo_link' ) <> '') {
			echo '<a href="' . get_theme_mod( 'kvarken_logo_link' ) . '">';
		}
		echo '<img src="' . get_theme_mod( 'kvarken_logo' ) . '" alt="" class="footer-logo">';
		if( get_theme_mod( 'kvarken_logo_link' ) <> '') {
			echo '</a>';
		}
	}
}
		
if( get_theme_mod( 'kvarken_footer_title' ) <> '') {
?>
	<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
<?php
	}
?>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>