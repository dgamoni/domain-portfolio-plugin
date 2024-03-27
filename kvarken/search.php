<?php get_header(); ?>
<div class="container">
		<div class="search-post border radius">
		<h1 class="post-title"><?php printf( __( 'Search Results for: %s', 'kvarken'), get_search_query()); ?></h1>		
		<?php get_search_form(); ?>
		</div><br/><br/>
			<?php while ( have_posts() ) : the_post(); ?> 					
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
<?php endwhile; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?> 