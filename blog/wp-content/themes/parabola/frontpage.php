<?php
/**
 * Frontpage generation functions
 * Creates the slider, the columns, the titles and the extra text
 *
 * @package parabola
 * @subpackage Functions
 */

function parabola_excerpt_length_slider( $length ) {
	return apply_filters( 'parabola_slider_excerpt', 50 );
}

function parabola_excerpt_more_slider( $more ) {
	return apply_filters( 'parabola_slider_more', '...' );
}


$parabolas = parabola_get_theme_options();
foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
?>

<script type="text/javascript">
     jQuery(document).ready(function() {
	// Slider creation
	     jQuery('#slider').nivoSlider({
				effect: '<?php  echo $parabola_fpslideranim; ?>',
		       	animSpeed: <?php echo $parabola_fpslidertime; ?>,
				<?php if($parabola_fpsliderarrows=="Hidden"): ?>directionNav: false,<?php endif;
		   		      if($parabola_fpsliderarrows=="Always Visible"): ?>directionNavHide: false,<?php endif; ?>
					  beforeChange: function(){
						//jQuery('.nivo-caption').slideUp(300);

		  			},
					afterChange: function(){
					  //jQuery('.nivo-caption').slideDown(500);
					},


					  pauseTime: <?php echo $parabola_fpsliderpause; ?>
	     });
	});
</script>

<div id="frontpage">
<?php

// First FrontPage Title
if($parabola_fronttext1) {?><h2 id="front-text1"><?php echo esc_attr($parabola_fronttext1) ?> </h2><?php }

parabola_ppslider();

// Second FrontPage title
if($parabola_fronttext2) {?><h2 id="front-text2"><?php echo esc_attr($parabola_fronttext2) ?> </h2><?php }

parabola_ppcolumns();

// Frontpage text areas
if($parabola_fronttext3) {?><div id="front-text3"><?php echo do_shortcode($parabola_fronttext3) ?></div><?php }
if($parabola_fronttext4) {?><div id="front-text4"><?php echo do_shortcode($parabola_fronttext4) ?></div><?php }
?>
</div> <!-- frontpage -->

<?php

remove_filter( 'excerpt_length', 'parabola_excerpt_length_slider', 999 );
remove_filter( 'excerpt_more', 'parabola_excerpt_more_slider', 999 );
if ($parabola_frontposts=="Enable"): get_template_part('content/content', 'frontpage'); endif;


// pp blocks functions

// SLIDER
function parabola_ppslider() {

	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
	$custom_query = new WP_query();
	$slides = array();

	if($parabola_slideNumber>0) {

		// Switch for Query type
		switch ($parabola_slideType) {
			case 'Latest Posts' :
			   $custom_query->query('showposts='.$parabola_slideNumber.'&ignore_sticky_posts=1');
			   break;
			case 'Random Posts' :
			   $custom_query->query('showposts='.$parabola_slideNumber.'&orderby=rand&ignore_sticky_posts=1');
			   break;
			case 'Latest Posts from Category' :
			   $custom_query->query('showposts='.$parabola_slideNumber.'&category_name='.$parabola_slideCateg.'&ignore_sticky_posts=1');
			   break;
			case 'Random Posts from Category' :
			   $custom_query->query('showposts='.$parabola_slideNumber.'&category_name='.$parabola_slideCateg.'&orderby=rand&ignore_sticky_posts=1');
			   break;
			case 'Sticky Posts' :
			   $custom_query->query(array('post__in'  => get_option( 'sticky_posts' ), 'showposts' =>$parabola_slideNumber,'ignore_sticky_posts' => 1));
			   break;
			case 'Specific Posts' :
			   // Transofm string separated by commas into array
			   $pieces_array = explode(",", $parabola_slideSpecific);
			   $custom_query->query(array( 'post_type' => 'any', 'showposts' => -1, 'post__in' => $pieces_array, 'ignore_sticky_posts' => 1,'orderby' => 'post__in' ));
			   break;
			case 'Custom Slides':
			   break;
		}//switch

	}; // slidenumber>0

	add_filter( 'excerpt_length', 'parabola_excerpt_length_slider', 999 );
	add_filter( 'excerpt_more', 'parabola_excerpt_more_slider', 999 );
	// switch for reading/creating the slides
	switch ($parabola_slideType) {
	  case 'Disabled':
			$slides = array();
			break;
	  case 'Custom Slides':
		   for ($i=1;$i<=5;$i++):
				if(${"parabola_sliderimg$i"}):
					 $slide['image'] = esc_url(${"parabola_sliderimg$i"});
					 $slide['link'] = esc_url(${"parabola_sliderlink$i"});
					 $slide['title'] = ${"parabola_slidertitle$i"};
					 $slide['text'] = ${"parabola_slidertext$i"};
					 $slides[] = $slide;
				endif;
		   endfor;
		   break;
	  default:
		   if($parabola_slideNumber>0):
		   if ( $custom_query->have_posts() ) while ($custom_query->have_posts()) :
				$custom_query->the_post();
					 $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ),'slider');
				$slide['image'] = esc_url( $img[0] );
				$slide['link'] = esc_url( get_permalink() );
				$slide['title'] = get_the_title();
				$slide['text'] = get_the_excerpt();
				$slides[] = $slide;
		   endwhile;
		   endif; // slidenumber>0
		   break;
	}; // switch

	if (count($slides)>0) { ?>
	<div class="slider-wrapper theme-default <?php if($parabola_fpsliderarrows=="Visible on Hover"): ?>slider-navhover<?php endif; ?> slider-<?php echo  preg_replace("/[^a-z0-9]/i","",strtolower($parabola_fpslidernav)); ?>">
		 <div class="ribbon"></div>
		 <div id="slider" class="nivoSlider">
		<?php foreach($slides as $id=>$slide):
				if($slide['image']): ?>
				<a href='<?php echo ($slide['link']?$slide['link']:'#'); ?>'>
					 <img src='<?php echo $slide['image']; ?>' alt="<?php echo ($slide['title']?wp_kses($slide['title'],array()):''); ?>" <?php if ($slide['title'] || $slide['text']): ?>title="#caption<?php echo $id;?>" <?php endif; ?> />
				</a><?php endif; ?>
		 <?php endforeach; ?>
		 </div>
		 <?php foreach($slides as $id=>$slide): ?>
				<div id="caption<?php echo $id;?>" class="nivo-html-caption">
					<?php echo '<h3>'.$slide['title'].'</h3><div class="slide-text">'.$slide['text']."</div>"; ?>
				</div>
		<?php endforeach; ?>
		 </div> <?php
	} // count(slides)
} // parabola_ppslider()

// COLUMNS
function parabola_ppcolumns() {

	 $parabolas = parabola_get_theme_options();
	 foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
     // Initiating query
     $custom_query2 = new WP_query();
     $columns = array();

	 if($parabola_columnNumber>0):
     // Switch for Query type
     switch ($parabola_columnType) {
          case 'Latest Posts' :
               $custom_query2->query('showposts='.$parabola_columnNumber.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts' :
               $custom_query2->query('showposts='.$parabola_columnNumber.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Latest Posts from Category' :
               $custom_query2->query('showposts='.$parabola_columnNumber.'&category_name='.$parabola_columnCateg.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts from Category' :
               $custom_query2->query('showposts='.$parabola_columnNumber.'&category_name='.$parabola_columnCateg.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Sticky Posts' :
               $custom_query2->query(array('post__in'  => get_option( 'sticky_posts' ), 'showposts' =>$parabola_columnNumber,'ignore_sticky_posts' => 1));
          break;
          case 'Specific Posts' :
               // Transform string separated by commas into array
               $pieces_array = explode(",", $parabola_columnSpecific);
               $custom_query2->query(array( 'post_type' => 'any', 'post__in' => $pieces_array, 'ignore_sticky_posts' => 1,'orderby' => 'post__in' ));
               break;
          case 'Widget Columns':
		  case 'Disabled':
			   // no query to do
               break;
     }//switch

	 endif; // columnNumber>0


	 // switch for reading/creating the columns
     switch ($parabola_columnType) {
		  case 'Disabled':
			   break;
		  case 'Custom Columns':
			   // fill up columns data with custom fields
			   for ($i=1;$i<=$parabola_nrcolumns;$i++) {
					$column['image'] = ${"parabola_columnimg$i"};
					$column['link'] = ${"parabola_columnlink$i"};
					$column['title'] = ${"parabola_columntitle$i"};
					$column['text'] = ${"parabola_columntext$i"};
					$columns[] = $column;
			   };
			   parabola_ppcolumns_output($columns,$parabola_nrcolumns);
			   break;
          case 'Widget Columns':
		       // if widgets loaded
               if (is_active_sidebar('presentation-page-columns-area')) {
					echo '<div id="front-columns">';
					dynamic_sidebar( 'presentation-page-columns-area' );
					echo "</div>";
				}
				// if no widgets loaded use the defaults ??!??!!?
			    else {
					global $parabola_column_defaults;
					parabola_ppcolumns_output($parabola_column_defaults,$parabola_nrcolumns);
				}
               break;
          default:
			   if($parabola_columnNumber>0):
               if ( $custom_query2->have_posts() )
					while ($custom_query2->have_posts()) :
						$custom_query2->the_post();
                    if ( has_image_size('parabola-columns')) {
					   	$img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'parabola-columns');
					}
					else {
						$img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ));
					}
						$column['image'] = esc_url( $img[0] );
						$column['link'] = esc_url( get_permalink() );
						$column['text'] = get_the_excerpt();
						$column['title'] = get_the_title();
						$columns[] = $column;
					endwhile;
					parabola_ppcolumns_output($columns,$parabola_nrcolumns);
			   endif; // columnNumber>0
               break;
     }; // switch
} // parabola_ppcolumns()

function parabola_ppcolumns_output($columns,$nr_columns){
	$counter=0;
	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value; }
	?>
	<div id="front-columns"> <?php
		foreach($columns as $column):
			if( !empty($column['image']) || !empty($column['title']) || !empty($column['text']) ) :
				$counter++;
				if (!isset($column['blank'])) $column['blank'] = 0;
				$coldata = array(
					'colno' => (($counter%$nr_columns)?$counter%$nr_columns:$nr_columns),
					'counter' => $counter,
					'image' => esc_url($column['image']),
					'link' => esc_url($column['link']),
					'blank' => ($column['blank']?'target="_blank"':''),
					'title' =>  $column['title'],
					'text' => $column['text'],
				);
				parabola_singlecolumn_output($coldata);
				// parabola_singlecolumn_output() located in includes/widget.php
			endif;
		endforeach; ?>
	</div><?php
} // parabola_ppcolumns_output()

// FIN
