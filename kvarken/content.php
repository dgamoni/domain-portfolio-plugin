<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<?php kvarken_breadcrumbs();?>
	<?php
	if (strpos($post->post_content,'[gallery') === false){
		if ( has_post_thumbnail()){
			the_post_thumbnail();
		}
	}
	the_content(); 
	wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages: ', 'kvarken' ), 'after' => '</div>' ) ); 
	kvarken_meta();
	?>						
</div>