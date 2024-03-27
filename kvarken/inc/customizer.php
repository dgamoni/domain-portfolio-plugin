<?php
function kvarken_customizer( $wp_customize ) {

//Add sections to the WordPress customizer
	 $wp_customize->add_section('kvarken_section_one',      array(
            'title' => __( 'Logo image', 'kvarken' ),
            'priority' => 90
        )
    );
	$wp_customize->add_section('kvarken_section_two',        array(
            'title' => __( 'Author and meta', 'kvarken' ),
            'priority' => 205
        )
    );
	 $wp_customize->add_section('kvarken_section_three',        array(
            'title' => __( 'Sidebar position', 'kvarken' ),
            'priority' => 210
        )
    );
	$wp_customize->add_section('kvarken_section_four',        array(
            'title' => __( 'Design details', 'kvarken' ), //Shadows etc
            'priority' => 215
        )
    );
	$wp_customize->add_section( 'kvarken_colors',        array(
            'title' => __( 'Advanced Color Settings', 'kvarken' ),
            'priority' => 220
        )
    );
	//-------------------------------------------------------

	/** Breadcrumbs **/
	$wp_customize->add_setting( 'kvarken_hide_breadcrumbs',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_hide_breadcrumbs',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to hide the breadcrumb navigation.', 'kvarken' ),
			'section' => 'nav',
		)
	);
	
	/** Display title and description in the footer */
	$wp_customize->add_setting( 'kvarken_footer_title',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_footer_title',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Display the text in the footer.', 'kvarken' ),
			'section' => 'title_tagline',
		)
	);
			
	/** Logo settings */
	$wp_customize->add_setting( 'kvarken_logo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'kvarken_logo', array(
		'label'    => __( 'Choose a logo: ', 'kvarken' ),
		'section' => 'kvarken_section_one',
		'settings' => 'kvarken_logo',
	)));
	
	/** Logo placement: */
	$wp_customize->add_setting( 'kvarken_logo_header',array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
		$wp_customize->add_control('kvarken_logo_header',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to display the logo in the header.', 'kvarken' ),
			'section' => 'kvarken_section_one',
		)
	);
	
	$wp_customize->add_setting( 'kvarken_logo_sidebar',array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_logo_sidebar',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to display the logo on top of the sidebar. This requires an active sidebar', 'kvarken' ),
			'section' => 'kvarken_section_one',
		)
	);
	
	$wp_customize->add_setting( 'kvarken_logo_footer',array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
		$wp_customize->add_control('kvarken_logo_footer',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to display the logo in the footer.', 'kvarken' ),
			'section' => 'kvarken_section_one',
		)
	);
	
	$wp_customize->add_setting( 'kvarken_logo_link',array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control('kvarken_logo_link',		array(
			'type' => 'text',
			'label' =>  __( 'Add this link to the logo:', 'kvarken' ),
			'section' => 'kvarken_section_one',
		)
	);
	//------------------------------------------------------------------------------------------------------
	
	/** Colors */
	/*link color */
	$wp_customize->add_setting( 'kvarken_link_color', array(
		'default'        => '#314a69',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_link_color', array(
	'label'        => __( 'Link Color', 'kvarken' ),
	'section' => 'kvarken_colors',
	'settings'  => 'kvarken_link_color',
	) ) );

	/*content text color*/
	$wp_customize->add_setting( 'kvarken_content_color', array(
		'default'        => '#333333',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_content_color', array(
	'label'        => __( 'Content text color', 'kvarken' ),
	'section' => 'kvarken_colors',
	'settings'  => 'kvarken_content_color',
	) ) );
	
	/* title color*/
	$wp_customize->add_setting( 'kvarken_title_color', array(
		'default'        => '#314a69',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_title_color', array(
	'label'        => __( 'Single post and page title color', 'kvarken' ),
	'section' => 'kvarken_colors',
	'settings'  => 'kvarken_title_color',
	) ) );

	/* widget title color*/
	$wp_customize->add_setting( 'kvarken_widget_title_color', array(
		'default'        => '#314a69',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_widget_title_color', array(
	'label'        => __( 'Widget title color', 'kvarken' ),
	'section' => 'kvarken_colors',
	'settings'  => 'kvarken_widget_title_color',
	) ) );
	
	/* comment respond backrgound color*/
	$wp_customize->add_setting( 'kvarken_comment_color', array(
		'default'        => '#314a69',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_comment_color', array(
	'label'        => __( 'Comment area backgroundcolor', 'kvarken' ),
	'section' => 'kvarken_colors',
	'settings'  => 'kvarken_comment_color',
	) ) );
	
	
	/* top border color*/
	$wp_customize->add_setting( 'kvarken_topborder_color', array(
		'default'        => '#314a69',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_topborder_color', array(
		'label'        => __( 'Color of the top and full page border of the site', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_topborder_color',
		) ) );
	
	
	/* content border color*/
	$wp_customize->add_setting( 'kvarken_border_color', array(
		'default'        => '#f7f7f7',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_border_color', array(
		'label'        => __( 'Color of the border of the sticky posts, meta, navigation, widgets and header image.', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_border_color',
		) ) );
		
	/*content background color*/	
	$wp_customize->add_setting( 'kvarken_bg_color', array(
		'default'        => '#f5f5f5',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_bg_color', array(
		'label'        => __( 'Background color for sticky posts, navigation and widgets.', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_bg_color',
		) ) );	
	
	/*content mouse over background color */
	$wp_customize->add_setting( 'kvarken_hover_color', array(
		'default'        => '#e6e6e6',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_hover_color', array(
		'label'        => __( 'Background hover color for sticky posts, navigation and widgets.', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_hover_color',
		) ) );	
	
	/* post and page background color*/
	$wp_customize->add_setting( 'kvarken_postbg_color', array(
		'default'        => '#ffffff',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_postbg_color', array(
		'label'        => __( 'Background color for posts and pages.', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_postbg_color',
		) ) );	
	
	/*meta background color*/
	$wp_customize->add_setting( 'kvarken_metabg_color', array(
		'default'        => '#f5f5f5',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_metabg_color', array(
		'label'        => __( 'Background color for the meta.', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_metabg_color',
		) ) );
	
/* menu background color*/	
	$wp_customize->add_setting( 'kvarken_menu_bg_color', array(
		'default'        => '#ffffff',
	) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kvarken_menu_bg_color', array(
		'label'        => __( 'Background color for the menu', 'kvarken' ),
		'section' => 'kvarken_colors',
		'settings'  => 'kvarken_menu_bg_color',
		) ) );
	//-------------------------------------------------------------------------------------------------------------------
	
	
	
	//Hide meta and extended author information
	$wp_customize->add_setting( 'kvarken_hide_authorinfo',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_hide_authorinfo',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to hide the author information and avatar that are displayed in single post view.', 'kvarken' ),
			'section' => 'kvarken_section_two',
		)
	);
		$wp_customize->add_setting( 'kvarken_hide_meta',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_hide_meta',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to hide all the meta information.', 'kvarken' ),
			'section' => 'kvarken_section_two',
		)
	);
	//-------------------------------------------------------------------------------------------------
	
	
	//Header image
	$wp_customize->add_setting( 'kvarken_header_image',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_header_image',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to place the header image above the menu.', 'kvarken' ),
			'section' => 'header_image',
		)
	);
	
	$wp_customize->add_setting( 'kvarken_header_image_link',	array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control('kvarken_header_image_link',	array(
			'type' => 'text',
			'label' =>  __( 'Add this link to the header image:', 'kvarken' ),
			'section' => 'header_image',
		)
	);
	//------------------------------------------------------------------------------------------------------
	
	
	/*Curly brackets**/
	$wp_customize->add_setting( 'kvarken_hide_curly',
		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
		
	$wp_customize->add_control('kvarken_hide_curly', array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to remove the curly brackets from the meny and to replace them with a | divider in the breadcrumb navigation.', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);
	
	
	
	/*shadows**/
	$wp_customize->add_setting( 'kvarken_hide_shadows',
		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
		
	$wp_customize->add_control('kvarken_hide_shadows', array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to hide all shadows.', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);
	
	/*genericons*/
	$wp_customize->add_setting( 'kvarken_genericons', array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
		
	$wp_customize->add_control('kvarken_genericons', array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to show icons in the post meta', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);
	
	
	/*remove rounded corners*/
	$wp_customize->add_setting( 'kvarken_hide_border-radius',array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_hide_border-radius',array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to remove the rounded corners on posts, widgets, navigation.', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);
	
	/*remove all borders*/
	$wp_customize->add_setting( 'kvarken_hide_all_borders',	array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_hide_all_borders',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to remove the borders around posts, widgets, navigation.', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);
	
	/* hide the top border */
	$wp_customize->add_setting( 'kvarken_hide_topborder',array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
		
	$wp_customize->add_control('kvarken_hide_topborder',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to remove the border on the very top of the page.', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);

	/* add a full page border */
	$wp_customize->add_setting( 'kvarken_full_border',	array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_full_border',	array(
			'type' => 'checkbox',
			'label' => __( 'Check this box to add a border to the entire page. (This requires the top border to be active)', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);

	/* size of the top/full page border */
	$wp_customize->add_setting( 'kvarken_topborder_size',	array(
			'sanitize_callback' => 'kvarken_sanitize_border', //checks for in
		)
	);
	$wp_customize->add_control('kvarken_topborder_size',  array(
			'type' => 'text',
			'label' => __( 'Or, choose a border size in the box below:', 'kvarken' ),
			'section' => 'kvarken_section_four',
		)
	);
	
	//Sidebar position	
	$wp_customize->add_setting( 'kvarken_sidebar_left',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_sidebar_left',		array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to move the sidebar to the left hand side. (Default is the right hand side. This requires an active sidebar)', 'kvarken' ),
			'section' => 'kvarken_section_three',
		)
	);
	
	/*Hide sidebar on frontapge*/
	$wp_customize->add_setting( 'kvarken_hide_sidebar',		array(
			'sanitize_callback' => 'kvarken_sanitize_checkbox',
		)
	);
	$wp_customize->add_control('kvarken_hide_sidebar',	array(
			'type' => 'checkbox',
			'label' =>  __( 'Check this box to hide the sidebar on the frontpage. (This setting is overwritten depending on template)', 'kvarken' ),
			'section' => 'kvarken_section_three',
		)
	);
 
}

add_action( 'customize_register', 'kvarken_customizer' );

function kvarken_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}

function kvarken_sanitize_border( $input ) { //border width
   if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

//Sidebar position added to body
function kvarken_body_class($classes) {
	if (! is_active_sidebar(1)){
		$classes[] = 'no-sidebar';
	}
	if( get_theme_mod( 'kvarken_sidebar_left' ) <> '') {
		$classes[] = 'left-sidebar';
	}
	
	if( get_theme_mod( 'kvarken_hide_sidebar' ) <> '') {
		if (is_home()){
			$classes[] = 'no-sidebar';
		}
	}
	return $classes;
}
add_filter('body_class','kvarken_body_class');

//border and radius classes added to posts
function kvarken_post_class($classes) {
		if(is_sticky()){
			$classes[] = 'border';
		}
		$classes[] = 'radius';
	return $classes;
}
add_filter('post_class','kvarken_post_class');


function kvarken_customize_css() { ?>
 <style type="text/css">
	<?php
	if (get_theme_mod( 'kvarken_hide_border-radius') <> '') {
		echo '.radius, .widget, #respond, .comment-body, #searchsubmit, body .wrapper #header .header-image img{border-radius:0;}' . "\n";
	}
	if (get_theme_mod( 'kvarken_hide_all_borders') <> '') {
		echo '.border, .widget, #respond, #searchsubmit, body .wrapper #header .header-image img{border:none;}' . "\n";
	}
	if( get_theme_mod( 'kvarken_menu_bg_color' ) <> '') {
	?>
		#header-menu ul,#header-menu ul ul a:hover,	#header-menu ul li ul :hover > a {background-color: <?php echo get_theme_mod('kvarken_menu_bg_color') ?>;}
	<?php
	}
	
	if( get_theme_mod( 'kvarken_hide_curly' ) <> '') {
	?>
	#header-menu li:hover > a:before, 
	#header-menu li.current_page_item:before {content:"";
	margin-bottom:-0px;}
	#header-menu li:hover > a:after, 
	#header-menu li.current_page_item:after {content:"";
	margin-top:-0px;}
	
	.crumbs i{opacity:0.3;}
	.crumbs a{opacity:1.0;}
	<?php
	}
	
		
	
	
	if( get_theme_mod( 'kvarken_link_color' ) <> '') {
	?>
		body a, body .widget ul li a, .page-link a,  .page-link a:hover, .newer-posts, .older-posts, .newer-posts:hover, .older-posts:hover,
		.newer-posts a, .newer-posts a:hover, .older-posts a, .older-posts a:hover,  #wp-calendar a, .tagcloud a,	.comment-meta a,
		.paged-comments a, .paged-comments a:hover, .comment-author a,	.page-numbers a, #respond a, 
		#header-menu li.current_page_item:after, #header-menu li:hover > a:after, 
		#header-menu li.current_page_item:before, #header-menu li:hover > a:before
		{color:<?php echo get_theme_mod('kvarken_link_color'); ?>; }
				
		#header-menu ul ul a:hover,	#header-menu ul li ul :hover > a{ border-left: 3px solid <?php echo get_theme_mod('kvarken_link_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_hide_topborder' ) == '') {
		if( get_theme_mod( 'kvarken_topborder_color' ) <> '') {	
			echo 'body{border-color:' . get_theme_mod('kvarken_topborder_color') . ';}' . "\n";
			if( get_theme_mod( 'kvarken_topborder_size' ) <> '') {
				echo 'body{border-top:' . get_theme_mod('kvarken_topborder_color') . ' ' . get_theme_mod('kvarken_topborder_size') . 'px solid;}' . "\n";
			}
		}else{
			if( get_theme_mod( 'kvarken_topborder_size' ) <> '') {
				echo 'body{border-top:' . get_theme_mod('kvarken_topborder_size') . 'px solid #314a69 ;}' . "\n";
			}
		}
		
		if( get_theme_mod( 'kvarken_full_border' ) <> '') {
			if( get_theme_mod( 'kvarken_topborder_size' ) <> '') {
				echo 'body{border:' . get_theme_mod('kvarken_topborder_size') . 'px solid ';
				if( get_theme_mod( 'kvarken_topborder_color' ) <> '') {	
					 echo get_theme_mod('kvarken_topborder_color') . ';}' . "\n";
				}else{
					echo ';}' . "\n";
				}
			}else{
				echo 'body{border:1px solid;}' . "\n";
			}
		}
	}else{
		echo 'body {border-top:0;}' . "\n";
	}
	if( get_theme_mod( 'kvarken_content_color' ) <> '') {	?>
		.post, .type-page, .page-link, .paged-comments, .widget ul li, .widget p, .widget #wp-calendar, .comment-body,
		#respond, #respond .form-allowed-tags{color:<?php echo get_theme_mod('kvarken_content_color'); ?>;}
				
		blockquote{border-left: 2px solid <?php echo get_theme_mod('kvarken_content_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_title_color' ) <> '') {	 ?>
		.post-title, .archive-title, #comments #comments-title {color: <?php echo get_theme_mod('kvarken_title_color'); ?>;}
		.widget title {color: <?php echo get_theme_mod('kvarken_widget_title_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_comment_color' ) <> '') {	?>
		.bypostauthor .comment-body {border-left:2px solid <?php echo get_theme_mod('kvarken_comment_color'); ?>}
		#respond, .reply a {background-color: <?php echo get_theme_mod('kvarken_comment_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_border_color' ) <> '') {  ?>	
		.border, .widget, .comment-body, input[type=submit], .widget_search input, .search-post input, #header img {border-color: <?php echo get_theme_mod('kvarken_border_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_bg_color' ) <> '') {	?>
		.post.sticky, .newer-posts, .older-posts, .widget, .textwidget, .paged-comments {background-color: <?php echo get_theme_mod('kvarken_bg_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_hover_color' ) <> '') {  	?>
		.sticky:hover, .widget:hover {background-color: <?php echo get_theme_mod('kvarken_hover_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_postbg_color' ) <> '') {	?>
		.post, .type-page {background-color: <?php echo get_theme_mod('kvarken_postbg_color'); ?>;}
	<?php 
	}
	if( get_theme_mod( 'kvarken_metabg_color' ) <> '') {   ?>
		.meta {background-color: <?php echo get_theme_mod('kvarken_metabg_color'); ?>;}
	<?php
	}
	if( get_theme_mod( 'kvarken_hide_topborder' ) == '') {
		?>
		@media screen and (max-width:601px){
			body{border:0px; border-top:5px solid;}
		}
		<?php
	}else{
	?>
		@media screen and (max-width:601px){
			body{border:0px;}
		}
		<?php
	}
	?>
	
    </style>
<?php
}
add_action( 'wp_head', 'kvarken_customize_css');



function kvarken_breadcrumbs(){
	if( get_theme_mod( 'kvarken_hide_breadcrumbs' ) == '') {
	?>
		<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e('Home', 'kvarken');?></a>
		<?php
				if ( count( get_the_category() ) ) : 
					$kvarken_category = get_the_category(); 
					if($kvarken_category[0]){
					
						if( get_theme_mod( 'kvarken_hide_curly' ) == '') {
							echo '} ';
						}else{
							echo '<i>|</i>  ';
						}
						echo '<a href="'.get_category_link($kvarken_category[0]->term_id ).'">'.$kvarken_category[0]->cat_name.'</a>';
					}
				endif;
				if( get_theme_mod( 'kvarken_hide_curly' ) == '') {
					echo ' } ';
				}else{
					echo ' <i>|</i>  ';
				}
				?>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
	<?php
	}
}


function kvarken_author(){
	if( get_theme_mod( 'kvarken_hide_authorinfo' ) == '') {				
	?>
		<div class="author-info">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60); ?></div>
				<div class="author-description">
					<h2><?php printf( __('About %s','kvarken'), get_the_author() ); ?></h2>
					<?php	
					if ( get_the_author_meta( 'description' ) ) :  
						the_author_meta( 'description' ); 
					endif;
					?>
					<div class="author-link"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ) ); ?>">
					<?php printf( __( 'View all posts by %s', 'kvarken' ), get_the_author() ); ?></a>
				</div>
			</div>
		</div>
	<?php
	}
}


function kvarken_meta(){
	if( get_theme_mod( 'kvarken_hide_meta' ) == '') {
		if(get_theme_mod( 'kvarken_genericons' ) == '') {
		?>
				<div class="meta border radius">
					<?php printf( ('<a href="%3$s" title="%4$s" rel="author">%5$s</a>, <a href="%1$s" rel="bookmark">%2$s</a>. '),
					esc_url( get_permalink() ),
					esc_html( get_the_date(get_option('date_format')) ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'kvarken' ), get_the_author() ) ),
					get_the_author()
					);
					
					if ( comments_open() ) :
						comments_popup_link();
						echo '.';					
					endif;
					
					if ( count( get_the_category() ) ) : 
					?>
						<?php printf( __( 'Category: %1s', 'kvarken' ), get_the_category_list(', ')); ?>.
					<?php 
					endif; 
					    if(get_the_tag_list()) {
							$kvarken_tags_list = get_the_tag_list( '', ', ' );
							printf( __( 'Tagged: %1$s. ', 'kvarken' ), $kvarken_tags_list );
						}
						edit_post_link( __( 'Edit', 'kvarken' ) );
					if(is_single()){
						kvarken_author();
					}
					?>
				</div>
		<?php
		}else{
		//genericons are active, display them with the i tag.
		?>
				<div class="meta border radius">
				
				<?php
				//Post format link
				if (has_post_format('link') ){
					echo '<i class="formats-link"></i> ';
				//Post format aside
				}
				
				if(has_post_format('aside') ){
					echo '<i class="formats-aside"></i> ';
				//Post format status
				}
				
				if(has_post_format('status') ){
					echo '<i class="formats-status"></i> ';
				}
				
			 printf( '<i class="author-links"></i> ' . ('<a href="%3$s" title="%4$s" rel="author">%5$s</a>, <a href="%1$s" rel="bookmark">%2$s</a>. '),
				esc_url( get_permalink() ),
				esc_html( get_the_date(get_option('date_format')) ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'kvarken' ), get_the_author() ) ),
				get_the_author()
				);
					
				if ( comments_open() ) :
					echo ' <i class="comment-icon"></i> ';
					comments_popup_link();			
				endif;
					
				if ( count( get_the_category() ) ) : 
					echo ' <i class="cat-links"></i> ';
					echo get_the_category_list(', ');
				endif; 
					if(get_the_tag_list()) {
						echo ' <i class="tag-links"></i> ';
						echo get_the_tag_list( '', ', ' );
					}
				edit_post_link(' <i class="edit-links"></i> ' . __( 'Edit', 'kvarken' ) );
				if(is_single()){
					kvarken_author();
				}
				?>
			</div>
		<?php
		}
	}
}
?>