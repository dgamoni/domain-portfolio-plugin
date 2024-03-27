<div id="rightsidebar">
<?php if( get_theme_mod( 'kvarken_logo_sidebar' ) <> '') {
			if( get_theme_mod( 'kvarken_logo' ) ) {
					if( get_theme_mod( 'kvarken_logo_link' ) <> '') {
						echo '<a href="' . get_theme_mod( 'kvarken_logo_link' ) . '">';
					}
					echo '<img src="' . get_theme_mod( 'kvarken_logo' ) . '" alt="" class="sidebar-logo">';
					
					if( get_theme_mod( 'kvarken_header_image_link' ) <> '') {
						echo '</a>';
					}
			}
		}
	?>	
	<ul>
		<?php dynamic_sidebar(1);?>
	</ul>
</div>