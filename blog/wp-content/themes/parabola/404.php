<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Cryout Creations
 * @subpackage parabola
 * @since parabola 0.5
 */

get_header(); ?>

	<div id="container" class="<?php echo parabola_get_layout_class(); ?>">
	
		<div id="content" role="main">

			<div id="post-0" class="post error404 not-found">
				<h1 class="entry-title"><?php _e( 'Not Found', 'parabola' ); ?></h1>
				<div class="entry-content">
					<div class="contentsearch">
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'parabola' ); ?></p>
					<?php get_search_form(); ?>
					</div>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->

		</div><!-- #content -->
<?php parabola_get_sidebar(); ?>
	</div><!-- #container -->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>