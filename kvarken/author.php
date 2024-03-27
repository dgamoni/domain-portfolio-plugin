<?php get_header(); 
$kvarken_curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
?>
<div class="container">
		<h1 class="archive-title">
		<?php printf( __('About %s','kvarken'), $kvarken_curauth->display_name); ?>
		</h1>
			<div class="author-info">
			<div class="author-avatar">
				<?php echo get_avatar($kvarken_curauth->user_email, 60); ?></div>
				<div class="author-description">
					<?php	
					echo $kvarken_curauth->description;
					?>
			</div>
		</div>
		<br /><br />
		<h1 class="archive-title"><?php printf( __( 'View all posts by %s', 'kvarken' ), $kvarken_curauth->display_name ); ?></h1>
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