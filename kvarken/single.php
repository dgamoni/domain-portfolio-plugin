<?php get_header(); ?>
<div class="container">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="post-title"><?php the_title(); ?></h1>
				<?php kvarken_breadcrumbs();?>		
				<?php
				if ( is_attachment() ) {
					echo '<div class="fullimg">' . wp_get_attachment_image('','full'). '</div>';
					if ( ! empty( $post->post_excerpt ) ) :
							echo '<br /><p>' . the_excerpt() .'</p>';
					endif; 					
					next_image_link();
					previous_image_link();
				} else {
					the_content();
					wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages: ', 'kvarken' ), 'after' => '</div>' ) ); 
					}
				kvarken_meta();
				?>	
		</div>
<?php
endwhile;
next_post_link('<div class="newer-posts border radius">%link &rarr;</div>');
previous_post_link('<div class="older-posts border radius">&larr; %link </div>');
comments_template( '', true ); 
?>
</div>
<?php
get_sidebar(); 
get_footer(); 
?>