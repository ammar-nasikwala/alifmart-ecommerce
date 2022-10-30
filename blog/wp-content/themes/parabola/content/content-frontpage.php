<?php
/**
 * The frontpage template for displaying posts
 *
 * @package Cryout Creations
 * @subpackage Parabola
 * @since Parabola 1.1
 */

$parabolas = parabola_get_theme_options();
foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
?>

		<section id="container" class="one-column <?php //echo parabola_get_layout_class(); ?>">

			<div id="content" role="main">

			<?php //cryout_before_content_hook();

			if ( have_posts() ) :

				?> <div class="content-masonry"> <?php
				/* Start the Loop */
				$the_query = new WP_Query( array('posts_per_page'=>$parabolas['parabola_frontpostscount']) );
				while ( $the_query->have_posts() ) : $the_query->the_post();

					global $more; $more=0;
					get_template_part( 'content/content', get_post_format() );

				endwhile;
				?> </div> <!--content-masonry--> <?php

				//if($parabola_pagination=="Enable") parabola_pagination(); else parabola_content_nav( 'nav-below' );

			else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'No Posts to Display', 'parabola' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php printf(
							__( 'You currently have no published posts.<br/>To hide this message either <a href="%s">add some posts</a> or disable displaying posts on the Presentation Page in <a href="%s">theme settings</a>.', 'parabola' ),
							esc_url( admin_url()."post-new.php"),
							esc_url( admin_url()."themes.php?page=parabola-page") ); ?>
						</p>
						<?php //get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php
			endif;
			//cryout_after_content_hook();
			?>

			</div><!-- #content -->
		<?php //parabola_get_sidebar(); ?>
		</section><!-- #container -->
