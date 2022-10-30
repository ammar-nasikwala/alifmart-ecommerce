<?php /*
 * Main loop related functions
 *
 * @package parabola
 * @subpackage Functions
 */


 /**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Parabola 1.0
 * @return int
 */
function parabola_excerpt_length( $length ) {
	global $parabola_excerptwords;
	return  absint( $parabola_excerptwords );
}
add_filter( 'excerpt_length', 'parabola_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since parabola 0.5
 * @return string "Continue Reading" link
 */
function parabola_continue_reading_link() {
	global $parabola_excerptcont;
	return ' <a class="continue-reading-link" href="'. esc_url( get_permalink() ) . '">'  . wp_kses_post( $parabola_excerptcont ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and parabola_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since parabola 0.5
 * @return string An ellipsis
 */
function parabola_auto_excerpt_more( $more ) {
	global $parabola_excerptdots;
	return wp_kses_post( $parabola_excerptdots ) . parabola_continue_reading_link();
}
add_filter( 'excerpt_more', 'parabola_auto_excerpt_more' );


/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since parabola 0.5
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function parabola_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= parabola_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'parabola_custom_excerpt_more' );


/**
 * Adds a "Continue Reading" link to post excerpts created using the <!--more--> tag.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the the_content_more_link filter hook.
 *
 * @since parabola 0.5
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function parabola_more_link($more_link, $more_link_text) {
	global $parabola_excerptcont;
	$new_link_text = wp_kses_post( $parabola_excerptcont );
	if (preg_match("/custom=(.*)/",$more_link_text,$m) ) {
		$new_link_text = $m[1];
	};
	$more_link = str_replace($more_link_text, $new_link_text, $more_link);
	$more_link = str_replace('more-link', 'continue-reading-link', $more_link);
	return $more_link;
}
add_filter('the_content_more_link', 'parabola_more_link',10,2);


/**
 * Allows post excerpts to contain HTML tags
 * @since parabola 1.8.7
 * @return string Excerpt with most HTML tags intact
 */

function parabola_trim_excerpt($text) {
     global $parabola_excerptwords;
     global $parabola_excerptcont;
     global $parabola_excerptdots;
     $parabola_excerptwords = absint( $parabola_excerptwords );
     $parabola_excerptcont = wp_kses_post( $parabola_excerptcont );
     $parabola_excerptdots = wp_kses_post( $parabola_excerptdots );
     $raw_excerpt = $text;
     if ( '' == $text ) {
         //Retrieve the post content.
         $text = get_the_content('');

         //Delete all shortcode tags from the content.
         $text = strip_shortcodes( $text );

         $text = apply_filters('the_content', $text);
         $text = str_replace(']]>', ']]&gt;', $text);

         $allowed_tags = '<a>,<img>,<b>,<strong>,<ul>,<li>,<i>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<pre>,<code>,<em>,<u>,<br>,<p>';
         $text = strip_tags($text, $allowed_tags);

         $words = preg_split("/[\n\r\t ]+/", $text, $parabola_excerptwords + 1, PREG_SPLIT_NO_EMPTY);
         if ( count($words) > $parabola_excerptwords ) {
             array_pop($words);
             $text = implode(' ', $words);
             $text = $text .' '.$parabola_excerptdots. ' <a class="continue-reading-link" href="'. get_permalink() . '">' .$parabola_excerptcont.' <span class="meta-nav">&rarr; </span>' . '</a>';
         } else {
             $text = implode(' ', $words);
         }
     }
     return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
if ($parabola_excerpttags=='Enable') {
     remove_filter('get_the_excerpt', 'wp_trim_excerpt');
     add_filter('get_the_excerpt', 'parabola_trim_excerpt');
}


/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Parabola's style.css.
 *
 * @since parabola 0.5
 * @return string The gallery style filter, with the styles themselves removed.
 */
function parabola_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'parabola_remove_gallery_css' );


if ( ! function_exists( 'parabola_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since parabola 0.5
 */
function parabola_posted_on() {
     global $parabolas;
     foreach ($parabolas as $key => $value) { ${"$key"} = $value; }

     // If author is hidden don't give it a value
    $author_string = sprintf( '<span class="author vcard" >'.__( 'By ','parabola'). ' <a class="url fn n" rel="author" href="%1$s" title="%2$s">%3$s</a></span>',
    			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    			sprintf( esc_attr__( 'View all posts by %s', 'parabola' ), get_the_author() ),
    			get_the_author()
    		);

     // Post date/time option
     $date_string='<span><time class="onDate date published" datetime="' . get_the_time( 'c' ) . '"> %3$s </time></span>' .
                    '<time class="updated"  datetime="' . get_the_modified_date( 'c' ) . '">' . get_the_modified_date() . '</time>';
     switch($parabola_postdatetime){
          case "date":
               $parabola_formatted_datetime = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), get_the_date() ); break;
          case "time":
               $parabola_formatted_datetime = sprintf( '<a href="%1$s" rel="bookmark"> %2$s</a>', esc_url( get_permalink() ), esc_attr( get_the_time() ) ); break;
          case "hide":
               $parabola_formatted_datetime = "";$date_string = ""; break;
          case "datetime":
          default:
               $parabola_formatted_datetime = sprintf( '<a href="%1$s" rel="bookmark">%3$s - %2$s</a>', esc_url( get_permalink() ), esc_attr( get_the_time() ), get_the_date() );
     } // switch

     // Print the meta data
	printf( '&nbsp; %4$s '.$date_string.' <span class="bl_categ"> %2$s </span>  ',
        'meta-prep meta-prep-author',
        get_the_category_list( ', ' ),
        $parabola_formatted_datetime,
        $author_string
	);
}; // parabola_posted_on()
endif;


if ( ! function_exists( 'parabola_posted_after' ) ) :
/**
 * Prints HTML with tags information for the current post. ALso adds the edit button.
 *
 * @since parabola 0.9
 */
function parabola_posted_after() { ?>
 	<footer class="entry-meta">
	<?php	$tag_list = get_the_tag_list( '', ', ' );
     if ( $tag_list ) { ?>
	<div class="footer-tags"><span class="bl_tagg"><?php _e( 'Tagged','parabola'); echo '</span> &nbsp;&nbsp;'.$tag_list; ?> </div>
     <?php }
	edit_post_link( __( 'Edit', 'parabola' ), '<span class="edit-link">', '</span>' );
	cryout_post_footer_hook();  ?>
	</footer><!-- #entry-meta -->
<?php
}; // parabola_posted_after()
endif;
add_action('cryout_post_after_content_hook','parabola_posted_after');


// Remove category from rel in categry tags.
add_filter( 'the_category', 'parabola_remove_category_tag' );
add_filter( 'get_the_category_list', 'parabola_remove_category_tag' );


function parabola_remove_category_tag( $text ) {
     $text = str_replace('rel="category tag"', 'rel="tag"', $text); return $text;
}


if ( ! function_exists( 'parabola_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since parabola 0.5
 */
function parabola_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in =  '<div class="footer-tags"><span class="bl_posted">'.__( 'Tagged','parabola').'</span>&nbsp; %2$s.</div><span class="bl_bookmark">'.__(' Bookmark the ','parabola').' <a href="%3$s" title="'.__('Permalink to','parabola').' %4$s" rel="bookmark"> '.__('permalink','parabola').'</a>.</span>';
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = '<span class="bl_bookmark">'.__( 'Bookmark the ','parabola'). ' <a href="%3$s" title="'.__('Permalink to','parabola').' %4$s" rel="bookmark">'.__('permalink','parabola').'</a>. </span>';
	} else {
		$posted_in = '<span class="bl_bookmark">'.__( 'Bookmark the ','parabola'). ' <a href="%3$s" title="'.__('Permalink to','parabola').' %4$s" rel="bookmark">'.__('permalink','parabola').'</a>. </span>';
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		esc_url( get_permalink() ),
		the_title_attribute( 'echo=0' )
	);
}; // parabola_posted_in()
endif;

if ( ! function_exists( 'parabola_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function parabola_content_nav( $nav_id ) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="navigation">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'parabola' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'parabola' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}; // parabola_content_nav()
endif; // parabola_content_nav

// Custom image size for use with post thumbnails
if ($parabola_fcrop) add_image_size( 'custom', $parabola_fwidth, $parabola_fheight, true );
                else add_image_size( 'custom', $parabola_fwidth, $parabola_fheight );


function cryout_echo_first_image ($postID) {
	$args = array(
    	'numberposts' => 1,
    	'orderby'=> 'none',
    	'post_mime_type' => 'image',
    	'post_parent' => $postID,
    	'post_status' => 'any',
    	'post_type' => 'any'
	);

	$attachments = get_children( $args );
	//print_r($attachments);

	if ($attachments) {
		foreach($attachments as $attachment) {
			$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'custom' )  ? wp_get_attachment_image_src( $attachment->ID, 'custom' ) : wp_get_attachment_image_src( $attachment->ID, 'custom' );

			return esc_url( $image_attributes[0] );

		}
	}
}; // echo_first_image()

if ( ! function_exists( 'parabola_set_featured_thumb' ) ) :
/**
 * Adds a post thumbnail and if one doesn't exist the first image from the post is used.
 */
function parabola_set_featured_thumb() {
	global $parabolas;
	foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
    global $post;

    $image_src = cryout_echo_first_image( $post->ID );
    if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $parabola_fpost == 'Enable')
        the_post_thumbnail( 'custom', array("class" => "align" . strtolower($parabola_falign) . " post_thumbnail" ) );
    else if ($parabola_fpost == 'Enable' && $parabola_fauto == "Enable" && $image_src )
        echo '<a title="'.the_title_attribute('echo=0').'" href="' . esc_url( get_permalink() ).'" ><img width="'.$parabola_fwidth.'" title="" alt="" class="align' . strtolower($parabola_falign) . ' post_thumbnail" src="' . $image_src . '"></a>' ;

};
endif; // parabola_set_featured_thumb

if ($parabola_fpost == 'Enable' && $parabola_fpostlink) add_filter( 'post_thumbnail_html', 'parabola_thumbnail_link', 10, 2 );

/**
 * The thumbnail gets a link to the post's page
 */
function parabola_thumbnail_link( $html, $post_id ) {
     $html = '<a href="' . esc_url( get_permalink( $post_id ) ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
     return $html;
}; // parabola_thumbnail_link()

?>
