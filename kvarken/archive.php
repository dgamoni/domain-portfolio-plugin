<?php get_header(); ?>
<div class="container">
		<h1 class="archive-title">
		<?php 
		if ( is_day() ) :  printf( __( 'Daily Archives: %s', 'kvarken' ), get_the_date(get_option('date_format')) ); 
		elseif ( is_month() ) : printf( __( 'Monthly Archives: %s', 'kvarken' ), get_the_date('F Y') ); 
		elseif ( is_year() ) : printf( __( 'Yearly Archives: %s', 'kvarken' ), get_the_date('Y') ); 
		elseif ( is_tag() ) : printf( __( 'Tag Archives: %s', 'kvarken' ), single_tag_title( '', false ) );
		elseif ( is_category() ) : printf( __( 'Category Archives: %s', 'kvarken' ), single_cat_title( '', false ));
		else : _e( 'Archive:', 'kvarken' ); 
		endif; 
		?>
		</h1><br />
		<?php
		while ( have_posts() ) : the_post(); ?> 
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			    <?php kvarken_breadcrumbs();?>
					<?php
					if (strpos($post->post_content,'[gallery') === false){
					   if ( has_post_thumbnail()) {
							the_post_thumbnail();
					   }
					}
					the_content(); 
					wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages: ', 'kvarken' ), 'after' => '</div>' ) ); 
										
				kvarken_meta();
				?>	
			</div>
<?php
endwhile; 
 
next_posts_link('<div class="newer-posts border radius">'. __('Next page &rarr;', 'kvarken') . '</div>'); 
previous_posts_link('<div class="older-posts border radius">' . __('&larr; Previous page','kvarken') . '</div>'); 
?>
</div>
<?php
get_sidebar(); 
get_footer();
?> 