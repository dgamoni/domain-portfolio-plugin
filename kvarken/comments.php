<div id="comments">
<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'kvarken' ); ?></p>
</div>
<?php
		return;
	endif;
	if ( have_comments() ) : ?>
			<h2 id="comments-title"><?php comments_number(); ?></h2>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<div class="paged-comments border radius"><?php _e( 'Pages: ', 'kvarken' ); paginate_comments_links( array('prev_text' => '', 'next_text' => '') ) ?></div>
			<?php endif;?>			
			<ol class="commentlist">
				<?php wp_list_comments(); ?>
			</ol>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<div class="paged-comments border radius"><?php _e( 'Pages: ', 'kvarken' ); paginate_comments_links( array('prev_text' => '', 'next_text' => '') ) ?></div>
			<?php endif;
	endif; // end if have_comments() 
	comment_form();
?>
</div>
