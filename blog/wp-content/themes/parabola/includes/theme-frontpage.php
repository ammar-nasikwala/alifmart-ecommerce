<?php
/**
 * Frontpage helper functions
 * Creates the custom css for the presentation page
 *
 * @package parabola
 * @subpackage Functions
 */

function parabola_presentation_css() {
	$parabolas= parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
	ob_start();
	echo '<style type="text/css">';
	if ($parabola_fronthideheader) {?> #branding {display: none;} <?php }
	if ($parabola_fronthidemenu) {?> #access, .topmenu {display: none;} <?php }
  	if ($parabola_fronthidewidget) {?> #colophon {display: none;} <?php }
	if ($parabola_fronthidefooter) {?> #footer2 {display: none;} <?php }
    if ($parabola_fronthideback) {?> #main {background: none;} <?php }
?>

.slider-wrapper {
 	max-width: <?php echo ($parabola_fpsliderwidth) ?>px; }

#slider{
	max-width: <?php echo ($parabola_fpsliderwidth) ?>px;
	max-height: <?php echo $parabola_fpsliderheight ?>px;
<?php if ($parabola_fpsliderbordercolor): ?> border:<?php echo $parabola_fpborderwidth ?>px solid <?php echo $parabola_fpsliderbordercolor; ?>; <?php endif; ?> }

#front-text1, #front-text2 {
	color: <?php echo $parabola_fronttitlecolor; ?>; }

#front-columns > div {
	<?php switch ($parabola_nrcolumns) {
    case 0: break;
	case 1: echo "width: 100%"; break;
    case 2: echo "width: 49%"; break;
    case 3: echo "width: 32%"; break;
    case 4: echo "width: 23.5%"; break;
	} ?>; }

#front-columns > div.ppcolumn:nth-child(<?php echo $parabola_nrcolumns; ?>n) { margin-<?php echo ( is_rtl() ? 'left' : 'right' ) ?>: 0; }
#front-columns > div.ppcolumn:nth-child(<?php echo $parabola_nrcolumns; ?>n+1) { clear: <?php echo ( is_rtl() ? 'right' : 'left' ) ?>; }



.column-image { padding: <?php echo $parabola_fpborderwidth ?>px; }
.column-image h3 {margin-bottom: <?php echo $parabola_fpborderwidth ?>px; }
.column-image img {	max-height:<?php echo ($parabola_colimageheight) ?>px;}

.nivo-caption { background-color: rgb(<?php echo cryout_hex2rgb($parabola_fpslidercaptionbg); ?>); background-color: rgba(<?php echo cryout_hex2rgb($parabola_fpslidercaptionbg); ?>,0.7); }
.nivo-caption, .nivo-caption a { color: <?php echo $parabola_fpslidercaptioncolor; ?>; }
.theme-default .nivoSlider { background-color: <?php echo $parabola_fpsliderbordercolor; ?>; }
.theme-default .nivo-controlNav:before, .theme-default .nivo-controlNav:after { border-top-color:<?php echo $parabola_fpsliderbordercolor; ?>; }
.theme-default .nivo-controlNav { background-color:<?php echo $parabola_fpsliderbordercolor; ?>; }
.slider-bullets .nivo-controlNav a { background-color: <?php echo $parabola_sidetitlebg; ?>; }
.slider-bullets .nivo-controlNav a:hover { background-color: <?php echo $parabola_menucolorbgdefault; ?>; }
.slider-bullets .nivo-controlNav a.active {background-color: <?php echo $parabola_accentcolora; ?>; }
.slider-numbers .nivo-controlNav a { color:<?php echo $parabola_sidetitlebg; ?>; background-color:<?php echo $parabola_backcolormain; ?>;}
.slider-numbers .nivo-controlNav a:hover { color: <?php echo $parabola_menucolorbgdefault; ?>;  background-color:<?php echo $parabola_contentcolorbg; ?> }
.slider-numbers .nivo-controlNav a.active { color:<?php echo $parabola_accentcolora; ?>;}

.column-image h3{ color: <?php echo $parabola_contentcolortxt; ?>; background-color: rgb(<?php echo cryout_hex2rgb($parabola_contentcolorbg); ?>); }
.columnmore { background-color: <?php echo $parabola_backcolormain; ?>; }
.columnmore:before { border-bottom-color: <?php echo $parabola_backcolormain;?>; }
#front-columns h3.column-header-noimage { background: <?php echo $parabola_contentcolorbg;?>; }

<?php
	echo '</style>';
	$parabola_presentation_page_styling = ob_get_contents();
	ob_end_clean();
	return $parabola_presentation_page_styling;
} // parabola_presentation_css()

// FIN