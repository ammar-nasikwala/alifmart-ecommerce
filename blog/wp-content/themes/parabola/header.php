<?php
/**
 * The Header
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Cryout Creations
 * @subpackage parabola
 * @since parabola 0.5
 */
 ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php  cryout_meta_hook(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
 	cryout_header_hook();
	wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php cryout_body_hook(); ?>

<div id="wrapper" class="hfeed">

<?php cryout_wrapper_hook(); ?>

<div id="header-full">

<header id="header">

<?php cryout_masthead_hook(); ?>

		<div id="masthead">

			<div id="branding" role="banner" >

				<?php cryout_branding_hook();?>
				<?php cryout_header_widgets_hook(); ?>
				<div style="clear:both;"></div>

			</div><!-- #branding -->
			<a id="nav-toggle"><span>&nbsp;</span></a>
			<nav id="access" role="navigation">

				<?php cryout_access_hook();?>

			</nav><!-- #access -->

		</div><!-- #masthead -->

	<div style="clear:both;height:1px;width:1px;"> </div>

</header><!-- #header -->
</div><!-- #header-full -->
<div id="main">
	<div  id="forbottom" >
		<?php cryout_forbottom_hook(); ?>

		<div style="clear:both;"> </div>

		<?php cryout_breadcrumbs_hook();?>
