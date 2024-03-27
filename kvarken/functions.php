<?php
/* width     bredd */
if ( ! isset( $content_width ) ) $content_width = 560;

if ( ! function_exists( 'kvarken_setup' ) ) :
	function kvarken_setup() {
		$kvarken_ch = array( //custom header settings
		'default-image'          => get_template_directory_uri() . '/images/headers/seagull.png',
		'random-default'         => false,
		'width'                  => 960,
		'height'                 => 300,
		'flex-height'            => true,
		'flex-width'             => true,
		'uploads'                => true,
		'header-text'            => true,
		'default-text-color'     => '222',
		'wp-head-callback'       => 'kvarken_header_textcolor'
		);
		add_theme_support( 'custom-header', $kvarken_ch );
		add_theme_support( 'custom-background');
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 190, 190 );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-formats', array( 'aside','link','status'));
		
		/* translate */
		load_theme_textdomain( 'kvarken', get_template_directory() . '/languages' );
		
		/* add menu */
		register_nav_menus( array('header' => __( 'Header Navigation', 'kvarken' ) ) );	

		register_default_headers( array(
			'sunset' => array(
				'url' => '%s/images/headers/sunset.png',
				'thumbnail_url' => '%s/images/headers/sunset-thumb.png',
				/* translators: header image description */
				'description' => __( 'Sunset', 'kvarken' )
			),
			'stones' => array(
				'url' => '%s/images/headers/stones.png',
				'thumbnail_url' => '%s/images/headers/stones-thumb.png',
				/* translators: header image description */
				'description' => __( 'Stones', 'kvarken' )
			),
			'flower' => array(
				'url' => '%s/images/headers/flora.png',
				'thumbnail_url' => '%s/images/headers/flora-thumb.png',
				/* translators: header image description */
				'description' => __( 'Flower', 'kvarken' )
			),
			'cloudy' => array(
				'url' => '%s/images/headers/acloudyday.png',
				'thumbnail_url' => '%s/images/headers/acloudyday-thumb.png',
				/* translators: header image description */
				'description' => __( 'A cloudy day', 'kvarken' )
			),
			'seagull' => array(
				'url' => '%s/images/headers/seagull.png',
				'thumbnail_url' => '%s/images/headers/seagull-thumb.png',
				/* translators: header image description */
				'description' => __( 'Seagull', 'kvarken' )
			),
			'birdsonrock' => array(
				'url' => '%s/images/headers/birdsonrock.png',
				'thumbnail_url' => '%s/images/headers/birdsonrock-thumb.png',
				/* translators: header image description */
				'description' => __( 'Birds On Rock', 'kvarken' )
			),
			'sunset2' => array(
				'url' => '%s/images/headers/sunset2.png',
				'thumbnail_url' => '%s/images/headers/sunset2-thumb.png',
				/* translators: header image description */
				'description' => __( 'Sunset', 'kvarken' )
			),
			'sunset2' => array(
				'url' => '%s/images/headers/sunset3.png',
				'thumbnail_url' => '%s/images/headers/sunset3-thumb.png',
				/* translators: header image description */
				'description' => __( 'Sunset', 'kvarken' )
			)
		) );
}
endif;
add_action( 'after_setup_theme', 'kvarken_setup' );

function kvarken_header_textcolor() {
echo '<style type="text/css">
		.site-title a,
		.site-description {
			color: #' . get_header_textcolor() . '!important;
		}
	</style>';
}

/* add 'home' button to menu            'hem' knapp i menyn*/
function kvarken_menu( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'kvarken_menu' );


function kvarken_editor_styles() {
    add_editor_style();
}
add_action( 'init', 'kvarken_editor_styles' );


/* Enqueue fonts */
 function kvarken_fonts_styles() {
    wp_register_style('kvarken_Font','//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic');
	wp_register_style('kvarken_Font2','//fonts.googleapis.com/css?family=Oswald&subset=latin,latin-ext');

	wp_enqueue_style('kvarken_style', get_stylesheet_uri() );
    wp_enqueue_style('kvarken_Font');
	wp_enqueue_style('kvarken_Font2');
	
	if( get_theme_mod( 'kvarken_hide_shadows' ) == '') {
		wp_register_style('kvarken_shadow', get_template_directory_uri() . '/css/shadow.css');
		wp_enqueue_style('kvarken_shadow');
	}
	if( get_theme_mod( 'kvarken_genericons' ) <> '') {
		wp_register_style( 'kvarken_genericons', get_template_directory_uri() . '/css/icons.css' );
		wp_enqueue_style( 'kvarken_genericons' );
	}

}
 add_action('wp_print_styles', 'kvarken_fonts_styles');
 
 
/* Enqueue comment reply / threaded comments. */
function kvarken_enqueue_comment_reply() {
	if ( ! is_admin() ){
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
			wp_enqueue_script( 'comment-reply' ); 
		}
	}
}
add_action( 'wp_enqueue_scripts', 'kvarken_enqueue_comment_reply' );


/* Add title to read more links */
add_filter( 'get_the_excerpt', 'kvarken_custom_excerpt_more',100 );
add_filter( 'excerpt_more', 'kvarken_excerpt_more',100 );
add_filter( 'the_content_more_link', 'kvarken_content_more', 100 );

 function kvarken_continue_reading($id ) {
	return '<a href="'.get_permalink( $id ).'">' . __( 'Read more: ', 'kvarken' ) . get_the_title($id) . '</a>';
}
 
function kvarken_content_more($more) {
	global $id;
	return kvarken_continue_reading( $id );
}
 
function kvarken_excerpt_more($more) {
	global $id;
	return '... '.kvarken_continue_reading( $id );
}

function kvarken_custom_excerpt_more($output) {
	if (has_excerpt() && !is_attachment()) {
		global $id;
		$output .= ' '.kvarken_continue_reading( $id );
	}
	return $output;
}

/* Register widget areas (Sidebars)        Skapa sidebars*/
function kvarken_widgets_init() {
	register_sidebar(
		array(
		'name' => __( 'Right Hand Sidebar', 'kvarken' ),
  		'description' => __( 'Widgets in this area will be shown on the right-hand side.', 'kvarken' ),
		)
	);
	
	register_sidebar(
		array(
		'name' => __( 'Footer Sidebar', 'kvarken' ),
  		'description' => __( 'Widgets in this area will be shown in the footer.', 'kvarken' ),
		)
	);
}
add_action( 'widgets_init', 'kvarken_widgets_init' );


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function kvarken_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'kvarken' ), max( $paged, $page ) );
		
	if ( is_404() ) {
        $title .=  " $sep " . sprintf( __( 'Page not found', 'kvarken' ) );
    }
	
	return $title;
}
add_filter( 'wp_title', 'kvarken_wp_title', 11, 2 );

//Customizer
require get_template_directory() . '/inc/customizer.php';




?>