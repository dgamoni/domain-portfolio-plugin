<?php
/*
Template Name: No sidebar, footer widgets or comments
/* This template displays no sidebar, no footer widget area, and no comment box.
/* SWE: Sida utan sidopanel, sidfotspanel eller kommentarer.
*/

get_header(); ?>
<div class="container" style="width:100%;">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="post-title"><?php the_title(); ?></h1>
				
				<?php kvarken_breadcrumbs();?>
				
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages: ', 'kvarken' ), 'after' => '</div>' ) );
				?>					
				<br />
				<div class="page-link"><?php edit_post_link( __( 'Edit', 'kvarken' ) ); ?></div>
			</div>
<?php
endwhile;
?>
</div>
<?php
get_footer(); 
?>