<?php /*
 * meta related functions
 *
 * @package parabola
 * @subpackage Functions
 */

/**
 * Meta Title
 */
function parabola_meta_title() {
global $parabolas;
if ($parabolas['parabola_iecompat']): echo PHP_EOL.'<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />'; endif;
}

function parabola_mobile_meta() {
global $parabolas;
if ($parabolas['parabola_zoom']==1) 
	echo '<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=3.0">';
else echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">';
echo PHP_EOL;
}

add_action ('cryout_meta_hook','parabola_meta_title',0);
add_action ('cryout_meta_hook','parabola_mobile_meta');

// Parabola favicon
function parabola_fav_icon() {
global $parabolas;
foreach ($parabolas as $key => $value) {
${"$key"} = $value ;}
	 echo '<link rel="shortcut icon" href="'.esc_url($parabolas['parabola_favicon']).'" />';
	 echo '<link rel="apple-touch-icon" href="'.esc_url($parabolas['parabola_favicon']).'" />';
	}

if ($parabolas['parabola_favicon']) add_action ('cryout_header_hook','parabola_fav_icon');


?>