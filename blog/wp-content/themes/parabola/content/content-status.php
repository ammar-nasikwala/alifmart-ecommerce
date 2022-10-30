<?php
/**
 * The template for displaying posts in the Status Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Cryout Creations
 * @subpackage Parabola
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php parabola_comments_on(); ?>
		<header class="entry-header">		
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'parabola' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="entry-meta">
					<h3 class="entry-format"><?php _e( 'Status', 'parabola' ); ?></h3>
					<?php parabola_posted_on(); ?>
			</div><!-- .entry-meta -->
			
			<?php cryout_post_title_hook(); ?>
		</header><!-- .entry-header -->
<?php cryout_post_before_content_hook();  
	?><?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
		
			<?php /* endif; */ ?>
			<div class="avatar">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'parabola_status_avatar', '65' ) ); ?>
				</div>
		<div class="status_content">	<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'parabola' ) ); ?> </div>
		
		</div><!-- .entry-content -->
		<?php endif; ?><?php
		cryout_post_after_content_hook();  ?>
	</article><!-- #post-<?php the_ID(); ?> -->
