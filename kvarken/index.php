<?php get_header(); ?>
	<div class="container">
		<?php
		while ( have_posts() ) : the_post(); 
			get_template_part( 'content', get_post_format() ); 
		endwhile; 
			
		if( get_next_posts_link() ){
			echo '<div class="newer-posts border radius">';
			next_posts_link(__('Next page &rarr;', 'kvarken'));
			echo '</div>'; 
		}
		if( get_previous_posts_link() ){
			echo '<div class="older-posts border radius">';
			previous_posts_link(__('&larr; Previous page','kvarken'));
			echo '</div>'; 
		}
		?>
	</div>
<?php
if( get_theme_mod( 'kvarken_hide_sidebar' ) == '') {
	get_sidebar();
}else{
	if (! is_home()){
		get_sidebar();
	}
}
 get_footer(); ?>