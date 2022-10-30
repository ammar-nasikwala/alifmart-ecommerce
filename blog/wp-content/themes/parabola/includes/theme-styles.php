<?php
/*
 * Styles and scripts registration and enqueuing
 *
 * @package parabola
 * @subpackage Functions
 */

// Adding the viewport meta if the mobile view has been enabled

function parabola_register_styles() {
	global $parabolas;
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ;}

	wp_register_style( 'parabola-style', get_stylesheet_uri(), NULL, _CRYOUT_THEME_VERSION );

	if($parabola_mobile=="Enable") { wp_register_style( 'parabola-mobile', get_template_directory_uri() . '/styles/style-mobile.css', NULL, _CRYOUT_THEME_VERSION );}

	if($parabola_googlefont) wp_register_style( 'parabola_googlefont', esc_attr("//fonts.googleapis.com/css?family=".preg_replace( '/\s+/', '+', $parabola_googlefont )));
	if($parabola_googlefonttitle) wp_register_style( 'parabola_googlefonttitle', esc_attr("//fonts.googleapis.com/css?family=".preg_replace( '/\s+/', '+',$parabola_googlefonttitle )));
	if($parabola_googlefontside) wp_register_style( 'parabola_googlefontside',esc_attr("//fonts.googleapis.com/css?family=".preg_replace( '/\s+/', '+',$parabola_googlefontside )));
	if($parabola_headingsgooglefont) wp_register_style( 'parabola_headingsgooglefont', esc_attr("//fonts.googleapis.com/css?family=".preg_replace( '/\s+/', '+',$parabola_headingsgooglefont )));
	if($parabola_sitetitlegooglefont) wp_register_style( 'parabola_sitetitlegooglefont', esc_attr("//fonts.googleapis.com/css?family=".preg_replace( '/\s+/', '+',$parabola_sitetitlegooglefont )));
	if($parabola_menugooglefont) wp_register_style( 'parabola_menugooglefont', esc_attr("//fonts.googleapis.com/css?family=".preg_replace( '/\s+/', '+',$parabola_menugooglefont )));

}

add_action('init', 'parabola_register_styles' );


function parabola_enqueue_styles() {
	global $parabolas;
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ;}

	wp_enqueue_style( 'parabola-style');
	if ( is_rtl() ) wp_enqueue_style( 'parabola-rtl', get_template_directory_uri() . '/styles/rtl.css', NULL, _CRYOUT_THEME_VERSION );

	wp_enqueue_style( 'parabola_googlefont');
	wp_enqueue_style( 'parabola_googlefonttitle');
	wp_enqueue_style( 'parabola_googlefontside');
	wp_enqueue_style( 'parabola_headingsgooglefont');
	wp_enqueue_style( 'parabola_sitetitlegooglefont');
	wp_enqueue_style( 'parabola_menugooglefont');

}

if( !is_admin() ) { add_action('wp_head', 'parabola_enqueue_styles', 5 ); }

function parabola_styles_echo() {
	global $parabolas;

	foreach ($parabolas as $key => $value) { ${"$key"} = $value ;}
	echo preg_replace("/[\n\r\t\s]+/"," " ,parabola_custom_styles())."\n";



	if(($parabola_frontpage=="Enable")&&is_front_page()) { echo preg_replace("/[\n\r\t\s]+/"," " ,parabola_presentation_css())."\n";}
	echo preg_replace("/[\n\r\t\s]+/"," " ,parabola_customcss())."\n";
}

add_action('wp_head', 'parabola_styles_echo', 20);

function parabola_load_mobile_css() {
global $parabolas;
foreach ($parabolas as $key => $value) {
    							 ${"$key"} = $value ;
									}
	if ($parabola_mobile=="Enable") {
		echo "<link rel='stylesheet' id='parabola_style_mobile'  href='".get_template_directory_uri() . '/styles/style-mobile.css?ver=' . _CRYOUT_THEME_VERSION . "' type='text/css' media='all' />";
	}
}

add_action ('wp_head','parabola_load_mobile_css', 30);

// JS loading and hook into wp_enque_scripts
add_action('wp_head', 'parabola_customjs', 35 );



// Scripts loading and hook into wp_enque_scripts

function parabola_scripts_method() {
	global $parabolas;
	foreach ($parabolas as $key => $value) { ${"$key"} = $value; }

	wp_register_script('parabola-frontend', get_template_directory_uri() . '/js/frontend.js', array('jquery'), _CRYOUT_THEME_VERSION );
	wp_enqueue_script('parabola-frontend');
	// If parabola from page is enabled and the current page is home page - load the nivo slider js
	if ( $parabola_frontpage == "Enable" && is_front_page() ) {
			wp_register_script('parabola-nivoSlider', get_template_directory_uri() . '/js/nivo-slider.js', array('jquery'), _CRYOUT_THEME_VERSION);
			wp_enqueue_script('parabola-nivoSlider');
	}

	$magazine_layout = FALSE;
	if ($parabolas['parabola_magazinelayout'] == "Enable") {
		if (is_front_page()) {
			if ( ($parabolas['parabola_frontpage'] == "Enable") && (intval($parabolas['parabola_frontpostsperrow']) == 1) ) { /* no magazine layout */ }
																											 else { $magazine_layout = TRUE; }
		} else {
			$magazine_layout = TRUE;
		}
	}
	if ( is_front_page() && ($parabolas['parabola_frontpage'] == "Enable") && (intval($parabolas['parabola_frontpostsperrow']) == 2) ) { $magazine_layout = TRUE; }

	if ( $magazine_layout && $parabola_masonry ) wp_enqueue_script('masonry');

	$js_options = array(
		'masonry' => (($parabola_masonry && $magazine_layout)?1:0),
		'magazine' => ($magazine_layout?1:0),
		'mobile' => (($parabola_mobile=='Enable')?1:0),
		'fitvids' => $parabola_fitvids,
	);
	wp_localize_script( 'parabola-frontend', 'parabola_settings', $js_options );


	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
if( !is_admin() ) { add_action('wp_enqueue_scripts', 'parabola_scripts_method'); }
