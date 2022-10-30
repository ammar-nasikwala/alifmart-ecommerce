<?php
/**
 * The template for displaying a video in the Video Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Cryout Creations
 * @subpackage Parabola
 * @since Parabola 1.0
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'indexed' ); ?>>
	<?php parabola_comments_on(); ?>
		<header class="entry-header">
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'parabola' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php cryout_post_title_hook(); ?>
		<div class="entry-meta">
		<h3 class="entry-format"><?php _e( 'Video', 'parabola' ); ?></h3>
				<?php parabola_posted_on(); 
			cryout_post_meta_hook();  ?>
			</div><!-- .entry-meta -->
		
		</header><!-- .entry-header -->
<?php cryout_post_before_content_hook();  ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'parabola' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'parabola' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
<?php cryout_post_after_content_hook();  ?>
	</article><!-- #post-<?php the_ID(); ?> -->
