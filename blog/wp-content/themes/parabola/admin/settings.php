<?php
// Callback functions

// General suboptions description

function cryout_section_layout_fn() { };
function cryout_section_presentation_fn() { };
function cryout_section_header_fn() { };
function cryout_section_text_fn() { };
function cryout_section_graphics_fn() { };
function cryout_section_post_fn() { };
function cryout_section_excerpt_fn() { };
function cryout_section_appereance_fn() { };
function cryout_section_featured_fn() { };
function cryout_section_social_fn() { };
function cryout_section_misc_fn() { };

//////////////////////////
//// PALEO-PROTOTYPES ////
//////////////////////////

function cryout_color_clean($color){
	if (strlen($color)>1): return "#".str_replace("#","",$color);
	else: return $color;
	endif;
} // cryout_color_clean()

function cryout_color_field($id,$title,$value,$hint=""){
	echo '<input type="text" id="'.$id.'" class="colorthingy" title="'.$title.'" name="parabola_settings['.$id.']" value="'.esc_attr(cryout_color_clean($value)).'"  />';
    echo '<div id="'.$id.'2"></div>';
	if (strlen($hint)>0) echo "<div><small>".$hint."</small></div>";
} // cryout_color_field()

// master function used for displaying font family / gfont / size selectors
function cryout_font_selector($fonts,$sizes,$size,$font,$gfont,$labelsize,$labelfont,$labelgfont,$general=""){ ?>
	<?php if ($size>0): ?>
	<select id='<?php echo $labelsize; ?>' name='parabola_settings[<?php echo $labelsize; ?>]' class='fontsizeselect'>
	<?php foreach($sizes as $item): ?>
		<option value='<?php echo $item; ?>' <?php selected($size,$item); ?>><?php echo $item; ?></option>
	<?php endforeach; ?>
	</select>
	<?php endif; ?>

	<select id='<?php echo $labelfont; ?>' class='admin-fonts fontnameselect' name='parabola_settings[<?php echo $labelfont; ?>]'>";
	<?php if (strlen($general)>0): ?>
		<optgroup>
			<option value="<?php echo $general; ?>"><?php echo $general; ?></option>
		</optgroup>
	<?php endif;
	foreach ($fonts as $fgroup => $fsubs): ?>
		<optgroup label='<?php echo $fgroup; ?>'>
		<?php foreach($fsubs as $item):
			$item_show = explode(',',$item); ?>
			<option style='font-family:<?php echo $item; ?>;' value='<?php echo $item; ?>' <?php selected($font,$item); ?>>
				<?php echo $item_show[0]; ?>
			</option>
		<?php endforeach; // fsubs ?>
		</optgroup>
	<?php endforeach; // fonts ?>
	</select>
	<input class="googlefonts" type="text" size="35" value="<?php echo esc_attr($gfont); ?>"  name="parabola_settings[<?php echo $labelgfont; ?>]" id="<?php echo $labelgfont; ?>" placeholder = "<?php _e("or Google font","parabola"); ?>"/>
<?php
} // cryout_font_selector()

////////////////////////////////
//// LAYOUT SETTINGS ///////////
////////////////////////////////

// RADIO-BUTTON - Name: parabola_settings[side]
function cryout_setting_side_fn() {
global $parabolas;
	$items = array("1c", "2cSr", "2cSl", "3cSr" , "3cSl", "3cSs");
	$layout_text["1c"] = __("One column (no sidebars)","parabola");
	$layout_text["2cSr"] = __("Two columns, sidebar on the right","parabola");
	$layout_text["2cSl"] = __("Two columns, sidebar on the left","parabola");
	$layout_text["3cSr"] = __("Three columns, sidebars on the right","parabola");
	$layout_text["3cSl"] = __("Three columns, sidebars on the left","parabola");
	$layout_text["3cSs"] = __("Three columns, one sidebar on each side","parabola");

	foreach($items as $item) {
		$checkedClass = ($parabolas['parabola_side']==$item) ? ' checkedClass' : '';
		echo "<label id='$item' class='layouts $checkedClass'><input ";
		checked($parabolas['parabola_side'],$item);
		echo " value='$item' onClick=\"changeBorder('$item','layouts');\" name='parabola_settings[parabola_side]' type='radio' /><img title='$layout_text[$item]' src='".get_template_directory_uri()."/admin/images/".$item.".png'/></label>";
	}
	echo "<div><small>".__("Choose your layout. Possible options are: <br> No sidebar, a single sidebar on either left of right, two sidebars on either left or right and two sidebars on each side.<br>This can be overriden in pages by using Page Templates.","parabola")."</small></div>";
}

 //SLIDER - Name: parabola_settings[sidewidth]
function cryout_setting_sidewidth_fn() {
     global $parabolas; ?>
     <script type="text/javascript">

	jQuery(function() {

		jQuery( "#slider-range" ).slider({
			range: true,
			step:10,
			min: 0,
			max: 1980,
			values: [ <?php echo $parabolas['parabola_sidewidth'] ?>, <?php echo ($parabolas['parabola_sidewidth']+$parabolas['parabola_sidebar']); ?> ],
			slide: function( event, ui ) {
          			range=ui.values[ 1 ] - ui.values[ 0 ];

           			if (ui.values[ 0 ]<500) {ui.values[ 0 ]=500; return false;};
          			if (	range<220 || range>800 ) { ui.values[ 1 ] = <?php echo $parabolas['parabola_sidebar']+$parabolas['parabola_sidewidth'];?>; return false; };

          			jQuery( "#parabola_sidewidth" ).val( ui.values[ 0 ] );
          			jQuery( "#parabola_sidebar" ).val( ui.values[ 1 ] - ui.values[ 0 ] );
          			jQuery( "#totalsize" ).html( ui.values[ 1 ]);
          			jQuery( "#contentsize" ).html( ui.values[ 0 ]);jQuery( "#barsize" ).html( ui.values[ 1 ]-ui.values[ 0 ]);

          			var percentage = parseInt( jQuery( "#slider-range .ui-slider-range" ).css('width') );
          			var leftwidth = parseInt( jQuery( "#slider-range .ui-slider-range" ).position().left );
          			jQuery( "#barb" ).css('left',-80+leftwidth+percentage/2+"px");
          			jQuery( "#contentb" ).css('left',-50+leftwidth/2+"px");
          			jQuery( "#totalb" ).css('width',(percentage+leftwidth)+"px");
               }
		});

		jQuery( "#parabola_sidewidth" ).val( <?php echo $parabolas['parabola_sidewidth'];?> );
		jQuery( "#parabola_sidebar" ).val( <?php echo $parabolas['parabola_sidebar'];?> );
		var percentage = <?php echo ($parabolas['parabola_sidebar']/1980)*100;?> ;
		var leftwidth = <?php echo ($parabolas['parabola_sidewidth']/1980)*100;?> ;

		jQuery( "#barb" ).css('left',(-18+leftwidth+percentage/2)+"%");
		jQuery( "#contentb" ).css('left',(-8+leftwidth/2)+"%");
		jQuery( "#totalb" ).css('width',(-2+percentage+leftwidth)+"%");
	});

     </script>

     <div id="absolutedim">

     	<b id="contentb"><?php _e("Content =","parabola");?> <span id="contentsize"><?php echo $parabolas['parabola_sidewidth'];?></span>px</b>
     	<b id="barb"><?php _e("Sidebar(s) =","parabola");?> <span id="barsize"><?php echo $parabolas['parabola_sidebar'];?></span>px</b>
     	<b id="totalb"> <?php _e("Total width =","parabola");?> <span id="totalsize"><?php echo $parabolas['parabola_sidewidth']+ $parabolas['parabola_sidebar'];?></span>px</b>

     <p> <?php
     echo "<input type='hidden' name='parabola_settings[parabola_sidewidth]' id='parabola_sidewidth' />";
	echo "<input type='hidden' name='parabola_settings[parabola_sidebar]' id='parabola_sidebar' />"; ?>
     </p>
     <div id="slider-range"></div>
     <?php echo "<div><small>".__("Select the width of your <b>content</b> and <b>sidebar(s)</b>. When using a 3 columns layout (with 2 sidebars) they will each have half the configured width.","parabola")."</small></div>"; ?>
     </div>

<?php } // cryout_setting_sidewidth_fn()

//CHECKBOX - Name: parabola_settings[magazinelayout]
function cryout_setting_magazinelayout_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_magazinelayout' name='parabola_settings[parabola_magazinelayout]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_magazinelayout'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Enable the Magazine Layout. This layout applies to pages with posts and shows 2 posts per row.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[mobile]
function cryout_setting_mobile_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_mobile' name='parabola_settings[parabola_mobile]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_mobile'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo " <label id='parabola_zoom' for='parabola_zoom' class='socialsdisplay'><input "; checked($parabolas['parabola_zoom'],'1');
	echo " value='1' id='parabola_zoom' name='parabola_settings[parabola_zoom]' type='checkbox' /> ".__('Allow zoom', 'parabola')." </label>";
	echo "<div><small>".__("Enable to make Parabola fully responsive. The layout and general sizes of your blog will adjust depending on what device and what resolution it is viewed in.<br> Do not disable unless you have a good reason to.","parabola")."</small></div>";
} // cryout_setting_mobile_fn()

////////////////////////////////
//// PRESENTATION SETTINGS /////////////
////////////////////////////////


//CHECKBOX - Name: parabola_settings[frontpage]
function cryout_setting_frontpage_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_frontpage' name='parabola_settings[parabola_frontpage]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_frontpage'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Enable the presentation front-page. This will become your new home page. <br> If you want another page to hold your latest blog posts, choose 'Blog Template (Posts Page)' from Page Templates while creating or editing that page.","parabola")."</small></div>";
	if ($parabolas['parabola_frontpage'] == 'Enable' && get_option('show_on_front') != 'posts') {
		printf ( '<div class="slmini" style="color:#cb5920;">'.__('You have enabled the Presentation Page but your WordPress\' <em>Front page displays</em> option is set to use a static page. WordPress guidelines require that the static page option have priority over theme options.<br> Go to %1$s and set the <em>Front page displays</em> option to <em><strong>Your latest posts</strong></em> to display the Presentation Page.',"parabola").'</div>', '<a href="/wp-admin/options-reading.php" > Settings &raquo; Reading</a>');
	};
} // cryout_setting_frontpage_fn()

function cryout_setting_frontposts_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_frontposts' name='parabola_settings[parabola_frontposts]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_frontposts'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";

	echo "<div class='slmini'><b>".__("Show:","parabola")."</b> ";
	echo "<input type='text' id='parabola_frontpostscount' name='parabola_settings[parabola_frontpostscount]' size='3' value='";
 	echo $parabolas['parabola_frontpostscount']."'> ".__('posts','parabola');
	echo "<div><small>".__("Enable to display latest posts on the presentation page, below the columns. Sticky posts are always displayed and not counted.","parabola")."</small></div><br>";
	echo "</div>";

	echo "<div class='slmini'><b>".__("Posts per row:","parabola")."</b> ";
	$items = array ("1", "2");
	echo "<select id='parabola_frontpostsperrow' name='parabola_settings[parabola_frontpostsperrow]'>";
	foreach($items as $item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_frontpostsperrow'],$item);
		echo ">$item</option>";
	}
	echo "</select></div>";
} // cryout_setting_frontpage_fn()

//CHECKBOX - Name: parabola_settings[frontslider]
function cryout_setting_frontslider_fn() {
	global $parabolas;

	echo "<div class='slmini'><b>".__("Slider and Columns Border:","parabola")."</b> ";
	echo "<input id='parabola_fpborderwidth' name='parabola_settings[parabola_fpborderwidth]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fpborderwidth'] )."' /> ".__("px","parabola");
	echo "<small>".__("Width for the slider and columns border. Set to zero when border and background color are too similar.","parabola")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider Dimensions:","parabola")."</b> ";
	echo "<input id='parabola_fpsliderwidth' name='parabola_settings[parabola_fpsliderwidth]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fpsliderwidth'] )."' /> px (".__("width","parabola").") <strong>X</strong> ";
	echo "<input id='parabola_fpsliderheight' name='parabola_settings[parabola_fpsliderheight]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fpsliderheight'] )."' /> px (".__("height","parabola").")";
	echo "<small>".__("The dimensions of your slider. Make sure your images are of the same size.","parabola")."</small></div>";

	echo "<div class='slmini'><b>".__("Animation:","parabola")."</b> ";
	$items = array ("random" , "fold", "fade", "slideInRight", "slideInLeft", "sliceDown", "sliceDownLeft", "sliceUp", "sliceUpLeft", "sliceUpDown" , "sliceUpDownLeft", "boxRandom", "boxRain", "boxRainReverse", "boxRainGrow" , "boxRainGrowReverse");
	$itemsare = array( __("Random","parabola"), __("Fold","parabola"), __("Fade","parabola"), __("SlideInRight","parabola"), __("SlideInLeft","parabola"), __("SliceDown","parabola"), __("SliceDownLeft","parabola"), __("SliceUp","parabola"), __("SliceUpLeft","parabola"), __("SliceUpDown","parabola"), __("SliceUpDownLeft","parabola"), __("BoxRandom","parabola"), __("BoxRain","parabola"), __("BoxRainReverse","parabola"), __("BoxRainGrow","parabola"), __("BoxRainGrowReverse","parabola"));
	echo "<select id='parabola_fpslideranim' name='parabola_settings[parabola_fpslideranim]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_fpslideranim'],$item);
		echo ">$itemsare[$id]</option>";
	}

	echo "</select>";
	echo "<small>".__("The transition effect of your slides.","parabola")."</small></div>";

	echo "<div class='slmini'><b>".__("Animation Time:","parabola")."</b> ";
	echo "<input id='parabola_fpslidertime' name='parabola_settings[parabola_fpslidertime]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fpslidertime'] )."' /> ".__("milliseconds","parabola");
	echo "<small>".__("The time in which the transition animation will take place.","parabola")."</small></div>";

	echo "<div class='slmini'><b>".__("Pause Time:","parabola")."</b> ";
	echo "<input id='parabola_fpsliderpause' name='parabola_settings[parabola_fpsliderpause]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fpsliderpause'] )."' /> ".__("milliseconds","parabola");
	echo "<small>".__("The time in which a slide will be still and visible.","parabola")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider navigation:","parabola")."</b> ";
	$items = array ("Numbers" , "Bullets" ,"None");
	$itemsare = array( __("Numbers","parabola"), __("Bullets","parabola"), __("None","parabola"));
	echo "<select id='parabola_fpslidernav' name='parabola_settings[parabola_fpslidernav]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_fpslidernav'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<small>".__("Your slider navigation type. Shown under the slider.","parabola")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider arrows:","parabola")."</b> ";
	$items = array ("Always Visible" , "Visible on Hover" ,"Hidden");
	$itemsare = array( __("Always Visible","parabola"), __("Visible on Hover","parabola"), __("Hidden","parabola"));
	echo "<select id='parabola_fpsliderarrows' name='parabola_settings[parabola_fpsliderarrows]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_fpsliderarrows'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<small>".__("The Left and Right arrows on your slider","parabola")."</small></div>";

?>

<?php /*
// reserved for future use
<script type="text/javascript">
var $categoryName;

jQuery(document).ready(function(){
	jQuery('#categ-dropdown').change(function(){
			$categoryName=this.options[this.selectedIndex].value.replace(/\/category\/archives\//i,"");
			doAjaxRequest();
	});

});
function doAjaxRequest(){
// here is where the request will happen
	jQuery.ajax({
          url: ajaxurl,
          data:{
               'action':'do_ajax',
               'fn':'get_latest_posts',
               'count':10,
				'categName':$categoryName
               },
          dataType: 'JSON',
          success:function(data){
		 jQuery('#post-dropdown').html(data);


                             },
          error: function(errorThrown){
               alert('error');
               console.log(errorThrown);
          }

     });

}
</script>
<!--
<select name="categ-dropdown" id="categ-dropdown" multiple='multiple' >
 <option value=""><?php echo esc_attr(__('Select Category','parabola')); ?></option>
 <?php
  $categories=  get_categories();
  foreach ($categories as $category) {
  	$option = '<option value="/category/archives/'.$category->category_nicename.'">';
	$option .= $category->cat_name;
	$option .= ' ('.$category->category_count.')';
	$option .= '</option>';
	echo $option;
  }
 ?>
</select>
<select name="post-dropdown" id="post-dropdown">
</select>
--> */ ?>

<?php
} // cryout_setting_frontslider_fn()

//CHECKBOX - Name: parabola_settings[frontslider2]
function cryout_setting_frontslider2_fn() {
	global $parabolas;

    $items = array("Custom Slides", "Latest Posts", "Random Posts", "Sticky Posts", "Latest Posts from Category" , "Random Posts from Category", "Specific Posts", "Disabled");
	$itemsare = array( __("Custom Slides","parabola"), __("Latest Posts","parabola"), __("Random Posts","parabola"),__("Sticky Posts","parabola"), __("Latest Posts from Category","parabola"), __("Random Posts from Category","parabola"), __("Specific Posts","parabola"), __("Disabled","parabola"));
	echo "<strong> Slides content: </strong> ";
	echo "<select id='parabola_slideType' name='parabola_settings[parabola_slideType]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_slideType'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Only the slides with a defined image will become active and visible in the live slider.<br>When using slides from posts, make sure the selected posts have featured images.<br>Read the FAQs for more info.","parabola")."</small></div>";
     ?>

     <div class="underSelector">
          <div id="sliderLatestPosts" class="slideDivs">
               <span><?php _e('Latest posts will be loaded into the slider.','parabola'); ?> </span>
          </div>

          <div id="sliderRandomPosts" class="slideDivs">
               <span><?php _e('Random posts will be loaded into the slider.','parabola'); ?> </span>
          </div>

          <div id="sliderLatestCateg" class="slideDivs">
               <span><?php _e('Latest posts from the category you choose will be loaded in the slider.','parabola'); ?> </span>
          </div>

          <div id="sliderRandomCateg" class="slideDivs">
               <span><?php _e('Random posts from the category you choose will be loaded into the slider.','parabola'); ?> </span>
          </div>

          <div id="sliderStickyPosts" class="slideDivs">
               <span><?php _e('Only sticky posts will be loaded into the slider.','parabola'); ?> </span>
          </div>

          <div id="sliderSpecificPosts" class="slideDivs">
               <span><?php _e('List the post IDs you want to display (separated by a comma): ','parabola'); ?> </span>
               <input id='parabola_slideSpecific' name='parabola_settings[parabola_slideSpecific]' size='44' type='text' value='<?php echo esc_attr( $parabolas['parabola_slideSpecific'] ) ?>' />
          </div>

          <div id="slider-category">
               <span><?php _e('<br> Choose the category: ','parabola'); ?> </span>
               <select id="parabola_slideCateg" name='parabola_settings[parabola_slideCateg]'>
               <option value=""><?php echo esc_attr(__('Select Category','parabola')); ?></option>
               <?php echo $parabolas["parabola_slideCateg"];
               $categories = get_categories();
               foreach ($categories as $category) {
                 	$option = '<option value="'.$category->category_nicename.'" ';
               	$option .= selected($parabolas["parabola_slideCateg"], $category->category_nicename, false).' >';
               	$option .= $category->cat_name;
               	$option .= ' ('.$category->category_count.')';
               	$option .= '</option>';
               	echo $option;
               } ?>
               </select>
          </div>

          <span id="slider-post-number"><?php _e('Number of posts to show:','parabola'); ?>
               <input id='parabola_slideNumber' name='parabola_settings[parabola_slideNumber]' size='3' type='text' value='<?php echo esc_attr( $parabolas['parabola_slideNumber'] ) ?>' />
          </span>

          <div id="sliderCustomSlides" class="slideDivs">

          <?php
          for ($i=1;$i<=5;$i++):
          // let's generate the slides
          ?>
               <div class="slidebox">
               <h4 class="slidetitle" ><?php _e("Slide","parabola");?> <?php echo $i; ?></h4>
               <div class="slidercontent">
                    <h5><?php _e("Image","parabola");?></h5>
                    <input type="text" value="<?php echo esc_url($parabolas['parabola_sliderimg'.$i]); ?>" name="parabola_settings[parabola_sliderimg<?php echo $i; ?>]"
                         id="parabola_sliderimg<?php echo $i; ?>" class="slideimages" />
                    <span class="description"><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'parabola' );?></a> </span>
                    <h5> <?php _e("Title","parabola");?> </h5>
                    <input id='parabola_slidertitle<?php echo $i; ?>' name='parabola_settings[parabola_slidertitle<?php echo $i; ?>]' size='50' type='text'
                         value='<?php echo esc_attr( $parabolas['parabola_slidertitle'.$i] ) ?>' />
                    <h5> <?php _e("Text","parabola");?> </h5>
                    <textarea id='parabola_slidertext<?php echo $i; ?>' name='parabola_settings[parabola_slidertext<?php echo $i; ?>]' rows='3' cols='50'
                         type='textarea'><?php echo esc_attr($parabolas['parabola_slidertext'.$i]) ?></textarea>
                    <h5> <?php _e("Link","parabola");?> </h5>
                    <input id='parabola_sliderlink<?php echo $i; ?>' name='parabola_settings[parabola_sliderlink<?php echo $i; ?>]' size='50' type='text'
                         value='<?php echo esc_url( $parabolas['parabola_sliderlink'.$i] ) ?>' />
               </div>
               </div>

          <?php endfor; ?>
          </div> <!-- customSlides -->
     </div>
<?php
} // cryout_setting_frontslider2_fn()

// parabola_settings[frontcolumns]
function cryout_setting_frontcolumns_fn() {
	global $parabolas;

	echo "<div class='slmini'>";
	$items = array("Custom Columns", "Widget Columns", "Latest Posts", "Random Posts", "Sticky Posts", "Latest Posts from Category" , "Random Posts from Category", "Specific Posts", "Disabled");
	$itemsare = array( __("Custom Columns",'parabola'), __("Widget Columns","parabola"), __("Latest Posts","parabola"), __("Random Posts","parabola"),__("Sticky Posts","parabola"), __("Latest Posts from Category","parabola"), __("Random Posts from Category","parabola"), __("Specific Posts","parabola"), __("Disabled","parabola"));
	echo __("<strong>Columns content:</strong>","parabola");
	echo "<select id='parabola_columnType' name='parabola_settings[parabola_columnType]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_columnType'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Only the columns with a defined image will become active and visible on the presentation page.<br>When using columns from posts, make sure the selected posts have featured images.<br>Read the FAQs for more info.","parabola")."</small></div>";
     ?>



     <div class="underSelector">
		<div id="columnCustom" class="columnDivs">
			<span><?php _e('Custom columns are limited to a maximum of 4.','parabola'); ?> </span>
			<?php for ($i=1;$i<=4;$i++): // custom column fields ?>
			 <div class="slidebox">
				  <h4 class="slidetitle" > <?php _e("Column","parabola");?> <?php echo $i; ?></h4>
				  <div class="slidercontent">
					   <h5><?php _e("Image","parabola");?></h5>
					   <input type="text" value="<?php echo esc_url($parabolas['parabola_columnimg'.$i]); ?>" name="parabola_settings[parabola_columnimg<?php echo $i; ?>]"
							id="parabola_columnimg<?php echo $i; ?>" class="slideimages" />
					   <span class="description"><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'parabola' );?></a> </span>
					   <h5> <?php _e("Title","parabola");?> </h5>
					   <input id='parabola_columntitle<?php echo $i; ?>' name='parabola_settings[parabola_columntitle<?php echo $i; ?>]' size='50' type='text'
							value='<?php echo esc_attr( $parabolas['parabola_columntitle'.$i] ) ?>' />
					   <h5> <?php _e("Text","parabola");?> </h5>
					   <textarea id='parabola_columntext<?php echo $i; ?>' name='parabola_settings[parabola_columntext<?php echo $i; ?>]' rows='3' cols='50'
							type='textarea'><?php echo esc_attr($parabolas['parabola_columntext'.$i]) ?></textarea>
					   <h5> <?php _e("Link","parabola");?> </h5>
					   <input id='parabola_columnlink<?php echo $i; ?>' name='parabola_settings[parabola_columnlink<?php echo $i; ?>]' size='50' type='text'
							value='<?php echo esc_url( $parabolas['parabola_columnlink'.$i] ) ?>' />
				  </div>
			 </div>
			 <?php endfor;  ?>
		</div>

          <div id="columnLatestPosts" class="columnDivs">
               <span><?php _e('Latest posts will be loaded into the columns.','parabola'); ?> </span>
          </div>

          <div id="columnRandomPosts" class="columnDivs">
               <span><?php _e('Random posts will be loaded into the columns.','parabola'); ?> </span>
          </div>

          <div id="columnLatestCateg" class="columnDivs">
               <span><?php _e('Latest posts from the category you choose will be loaded in the columns.','parabola'); ?> </span>
          </div>

          <div id="columnRandomCateg" class="columnDivs">
               <span><?php _e('Random posts from the category you choose will be loaded into the columns.','parabola'); ?> </span>
          </div>

          <div id="columnStickyPosts" class="columnDivs">
               <span><?php _e('Only sticky posts will be loaded into the columns.','parabola'); ?> </span>
          </div>

          <div id="columnSpecificPosts" class="columnDivs">
               <span><?php _e('List the post IDs you want to display (separated by a comma): ','parabola'); ?> </span>
               <input id='parabola_columnSpecific' name='parabola_settings[parabola_columnSpecific]' size='44' type='text' value='<?php echo esc_attr( $parabolas['parabola_columnSpecific'] ) ?>' />
          </div>

		  <div id="columnWidgets" class="columnDivs">
			  <span><?php _e('Load your custom Widgets as columns. Go to <a>Appearance >> Widgets</a> and create your custom columns using the Columns widget. You can use as many as you want.','parabola'); ?> </span>
          </div>
		  <script>jQuery('#columnWidgets span a').attr('href','<?php echo esc_url(get_admin_url());?>widgets.php');</script>

          <div id="column-category">
               <span><?php _e('<br> Choose the category: ','parabola'); ?> </span>
               <select id="parabola_columnCateg" name='parabola_settings[parabola_columnCateg]'>
               <option value=""><?php echo esc_attr(__('Select Category','parabola')); ?></option>
               <?php echo $parabolas["parabola_columnCateg"];
               $categories = get_categories();
               foreach ($categories as $category) {
                 	$option = '<option value="'.$category->category_nicename.'" ';
               	$option .= selected($parabolas["parabola_columnCateg"], $category->category_nicename, false).' >';
               	$option .= $category->cat_name;
               	$option .= ' ('.$category->category_count.')';
               	$option .= '</option>';
               	echo $option;
               } ?>
               </select>
          </div>
		   <span id="column-post-number"><?php _e('Number of posts to show:','parabola'); ?>
               <input id='parabola_columnNumber' name='parabola_settings[parabola_columnNumber]' size='3' type='text' value='<?php echo esc_attr( $parabolas['parabola_columnNumber'] ) ?>' />
          </span>

     </div>
</div>

<?php
	echo "<div class='slmini'><b>".__("Columns per row:","parabola")."</b> ";
	$items = array ("0" ,"1", "2" , "3" , "4");
	echo "<select id='parabola_nrcolumns' name='parabola_settings[parabola_nrcolumns]'>";
	foreach($items as $item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_nrcolumns'],$item);
		echo ">$item</option>";
	}
	echo "</select></div>";

	echo "<div class='slmini'><b>".__("Image Size:","parabola")."</b> ";
	echo __("Height: ","parabola")."<input id='parabola_colimageheight' name='parabola_settings[parabola_colimageheight]' size='4' type='text' value='".esc_attr( $parabolas['parabola_colimageheight'] )."' /> px &nbsp;&nbsp;";
	echo __("Width: ","parabola")."<input id='parabola_colimagewidth' name='parabola_settings[parabola_colimagewidth]' size='4' type='text' value='".esc_attr( $parabolas['parabola_colimagewidth'] )."' readonly/> px";
	echo "<small>".__("The sizes for your column images. The width is dependent on total site width and not configurable.","parabola")."</small></div>";
     ?>
     <div class='slmini'><b><?php _e("Read more text:","parabola");?></b>
     <input id='parabola_columnreadmore' name='parabola_settings[parabola_columnreadmore]' size='30' type='text' value='<?php echo esc_attr( $parabolas['parabola_columnreadmore'] ) ?>' />
     <?php
	echo "<small>".__("The linked text that appears at the bottom of each column. Leave empty to hide the link.","parabola")."</small></div>";
} // cryout_setting_frontcolumns_fn()


//CHECKBOX - Name: parabola_settings[fronttext]
function cryout_setting_fronttext_fn() {
	global $parabolas;

     echo "<div class='slidebox'><h4 class='slidetitle'> ".__("Extra Text","parabola")." </h4><div class='slidercontent'>";

     echo "<div style='width:100%;'><span>".__("Text for the Presentation Page","parabola")."</span><small>".
          __("More text for your front page. The top title is above the slider, the second title between the slider and the columns and 2 more rows of text under the columns.<br>It's all optional so leave any input field empty to not dispaly it.","parabola")."</small></div>";

	echo "<h5>".__("Top Title","parabola")."</h5><br>";
     echo "<input id='parabola_fronttext1' name='parabola_settings[parabola_fronttext1]' size='50' type='text' value='".esc_attr( $parabolas['parabola_fronttext1'] )."' />";
     echo "<h5>".__("Second Title","parabola")."</h5> ";
	echo "<input id='parabola_fronttext2' name='parabola_settings[parabola_fronttext2]' size='50' type='text' value='".esc_attr( $parabolas['parabola_fronttext2'] )."' />";

     echo "<h5>".__("Bottom Text 1","parabola")."</h5> ";
	echo "<textarea id='parabola_fronttext3' name='parabola_settings[parabola_fronttext3]' rows='3' cols='50' type='textarea' >".esc_attr($parabolas['parabola_fronttext3'])." </textarea>";
     echo "<h5>".__("Bottom Text 2","parabola")." </h5> ";
	echo "<textarea id='parabola_fronttext4' name='parabola_settings[parabola_fronttext4]' rows='3' cols='50' type='textarea' >".esc_attr($parabolas['parabola_fronttext4'])." </textarea></div></div>";

     echo "<div class='slidebox'><h4 class='slidetitle'>".__("Hide areas","parabola")." </h4><div  class='slidercontent'>";

     echo "<div style='width:100%;'>".__("Choose the areas to hide on the first page.","parabola")."</div>";

		$items = array( "FrontHeader", "FrontMenu", "FrontWidget" , "FrontFooter","FrontBack");

		$checkedClass0 = ($parabolas['parabola_fronthideheader']=='1') ? ' checkedClass0' : '';
		$checkedClass1 = ($parabolas['parabola_fronthidemenu']=='1') ? ' checkedClass1' : '';
		$checkedClass2 = ($parabolas['parabola_fronthidewidget']=='1') ? ' checkedClass2' : '';
		$checkedClass3 = ($parabolas['parabola_fronthidefooter']=='1') ? ' checkedClass3' : '';
		$checkedClass4 = ($parabolas['parabola_fronthideback']=='1') ? ' checkedClass4' : '';

	echo " <label id='$items[0]' for='$items[0]$items[0]' class='hideareas $checkedClass0'><input "; checked($parabolas['parabola_fronthideheader'],'1');
	echo " value='1' id='$items[0]$items[0]'  name='parabola_settings[parabola_fronthideheader]' type='checkbox' /> ".__("Hide the header area (logo/title and/or image/empty area).","parabola")." </label>";

	echo " <label id='$items[1]' for='$items[1]$items[1]' class='hideareas $checkedClass1'><input "; checked($parabolas['parabola_fronthidemenu'],'1');
	echo " value='1' id='$items[1]$items[1]'  name='parabola_settings[parabola_fronthidemenu]' type='checkbox' /> ".__("Hide the main menu and the top menu.","parabola")." </label>";

	echo " <label id='$items[2]' for='$items[2]$items[2]' class='hideareas $checkedClass2'><input "; checked($parabolas['parabola_fronthidewidget'],'1');
	echo " value='1' id='$items[2]$items[2]'  name='parabola_settings[parabola_fronthidewidget]' type='checkbox' /> ".__("Hide the footer widgets. ","parabola")." </label>";

	echo " <label id='$items[3]' for='$items[3]$items[3]' class='hideareas $checkedClass3'><input "; checked($parabolas['parabola_fronthidefooter'],'1');
	echo " value='1' id='$items[3]$items[3]'  name='parabola_settings[parabola_fronthidefooter]' type='checkbox' /> ".__("Hide the footer (copyright area).","parabola")." </label>";

     echo "</div></div>";
}

//////////////////////////////
/////HEADER SETTINGS//////////
/////////////////////////////

 //SELECT - Name: parabola_settings[hheight]
function cryout_setting_hheight_fn() {
	global $parabolas; ?>
	<input id='parabola_hheight' name='parabola_settings[parabola_hheight]' size='4' type='text' value='<?php echo esc_attr( intval($parabolas['parabola_hheight'] )) ?>'  />  px
	<?php $totally = $parabolas['parabola_sidebar']+$parabolas['parabola_sidewidth'];
	echo "<div><small>".__("Select the header's height. After saving the settings go and upload your new header image. The header's width will be ","parabola")."<strong>".$totally."px</strong>.</small></div>";
}

function cryout_setting_himage_fn() {
	global $parabolas;
	$checkedClass = ($parabolas['parabola_hcenter']=='1') ? ' checkedClass' : '';
	echo "<a href=\"?page=custom-header\" class=\"button\" target=\"_blank\">".__('Define header image','parabola')."</a><br>";
	echo " <label id='hcenter' for='parabola_hcenter' class='socialsdisplay $checkedClass'><input ";
		 checked($parabolas['parabola_hcenter'],'1');
	echo " value='1' id='parabola_hcenter' name='parabola_settings[parabola_hcenter]' type='checkbox' /> ".__('Center the header image horizontally', 'parabola')." </label>";
	echo " <label id='hratio' for='parabola_hratio' class='socialsdisplay $checkedClass'><input ";
		 checked($parabolas['parabola_hratio'],'1');
	echo " value='1' id='parabola_hratio' name='parabola_settings[parabola_hratio]' type='checkbox' style='margin-left:20px;'/> ".__('Keep header image aspect ratio.', 'parabola')." </label>";
	echo "<div><small>".__("By default the header has a minimum height set. This option removes that and the header becomes fully responsive, scalling to any size.<br> Only enable this if you're <b>not</b> using a logo or site title and description in the header. ","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[linkheader]
function cryout_setting_siteheader_fn() {
	global $parabolas;
	$items = array ("Site Title and Description" , "Custom Logo" , "Clickable header image" , "Empty");
	$itemsare = array( __("Site Title and Description","parabola"), __("Custom Logo","parabola"), __("Clickable header image","parabola"), __("Empty","parabola"));
	echo "<select id='parabola_siteheader' name='parabola_settings[parabola_siteheader]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_siteheader'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Choose what to display inside your header area.","parabola")."</small></div>";
}

// TEXTBOX - Name: parabola_settings[favicon]
function cryout_setting_logoupload_fn() {
	global $parabolas;
	echo '<div>';
?>
 <img  src='<?php echo  ($parabolas['parabola_logoupload']!='')? esc_url($parabolas['parabola_logoupload']):get_template_directory_uri().'/admin/images/placeholder.gif'; ?>' class="imagebox" style="display:;max-height:60px" /><br>
<input type="text" size='60' value="<?php echo  esc_url($parabolas['parabola_logoupload']); ?>" name="parabola_settings[parabola_logoupload]" id="parabola_logoupload" class="header_upload_inputs slideimages" />
<?php echo "<div><small>".__("Custom Logo upload. The logo will appear over the header image if you have used one.","parabola")."</small></div>"; ?>
<span class="description"><br><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'parabola' );?></a> </span>
</div>

<?php
}

function  cryout_setting_headermargin_fn() {
	global $parabolas;?>
<input id='parabola_headermargintop' name='parabola_settings[parabola_headermargintop]' size='4' type='text' value='<?php echo esc_attr( intval($parabolas['parabola_headermargintop'] )) ?>'  />  px <?php echo __("top","parabola");?> &nbsp; &nbsp;
<input id='parabola_headermarginleft' name='parabola_settings[parabola_headermarginleft]' size='4' type='text' value='<?php echo esc_attr( intval($parabolas['parabola_headermarginleft'] )) ?>'  />  px <?php echo __("left","parabola");?>
<?php

echo "<div><small>".__("Select the top and left spacing for the header content. Use it to better position your site title and description or custom logo inside the header. ","parabola")."</small></div>";
}

// TEXTBOX - Name: parabola_settings[favicon]
function cryout_setting_favicon_fn() {
	global $parabolas;
	echo '<div>';
?>
 <img src='<?php echo  ($parabolas['parabola_favicon']!='')? esc_url($parabolas['parabola_favicon']):get_template_directory_uri().'/admin/images/placeholder.gif'; ?>' class="imagebox" width="64" height="64"/><br>
<input type="text" size='60' value="<?php echo  esc_url($parabolas['parabola_favicon']); ?>" name="parabola_settings[parabola_favicon]" id="parabola_favicon" class="header_upload_inputs slideimages" />
<?php echo "<div><small>".__("Limitations: It has to be an image. It should be max 64x64 pixels in dimensions. Recommended file extensions .ico and .png. <br> <strong>Note that some browsers do not display the changed favicon instantly.</strong>","parabola")."</small></div>"; ?>
<span class="description"><br><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'parabola' );?></a> </span>
</div>
<?php
}

// SELECT - Name: parabola_settings[headerwidgetwidth]
function cryout_setting_headerwidgetwidth_fn() {
	global $parabolas;
	$items = array("60%" , "50%" , "33%" , "25%");
	$itemsare = array( __("60%","parabola"), __("50%","parabola"), __("33%","parabola"), __("25%","parabola"));
	echo "<select id='parabola_headerwidgetwidth' name='parabola_settings[parabola_headerwidgetwidth]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_headerwidgetwidth'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Limit the header widget area max width as percentage of the entire header width.","parabola")."</small></div>";
}


////////////////////////////////
//// TEXT SETTINGS /////////////
////////////////////////////////

//SELECT - Name: parabola_settings[fontfamily]
function  cryout_setting_fontfamily_fn() {
	global $parabolas;
	global $fonts;
	$sizes = array ("12px", "13px" , "14px" , "15px" , "16px", "17px", "18px", "19px", "20px");
	cryout_font_selector(
		$fonts,
		$sizes,
		$parabolas['parabola_fontsize'],
		$parabolas['parabola_fontfamily'],
		$parabolas['parabola_googlefont'],
		'parabola_fontsize',
		'parabola_fontfamily',
		'parabola_googlefont'
	);
	echo "<div><small>".__("Select the general font family and size or insert the Google Font name you'll use in your blog. This will affect all text except the one controlled by the options below. ","parabola")."</small></div><br>";
}

//SELECT - Name: parabola_settings[fonttitle]
function  cryout_setting_fonttitle_fn() {
	global $parabolas;
	global $fonts;
	$sizes = array ( "14px" , "16px" , "18px" , "20px", "22px" , "24px" , "26px" , "28px" , "30px" , "32px" , "34px" , "36px", "38px" , "40px");
	cryout_font_selector(
		$fonts,
		$sizes,
		$parabolas['parabola_headfontsize'],
		$parabolas['parabola_fonttitle'],
		$parabolas['parabola_googlefonttitle'],
		'parabola_headfontsize',
		'parabola_fonttitle',
		'parabola_googlefonttitle',
		__('General Font','parabola')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want for your titles. It will affect post titles and page titles. Leave 'General Font' and the general font values you selected will be used.","parabola")."</small></div><br>";
}

//SELECT - Name: parabola_settings[fontside]
function  cryout_setting_fontside_fn() {
	global $parabolas;
	global $fonts;
	for ($i=14;$i<31;$i+=2): $sizes[] = "${i}px"; endfor;
	cryout_font_selector(
		$fonts,
		$sizes,
		$parabolas['parabola_sidefontsize'],
		$parabolas['parabola_fontside'],
		$parabolas['parabola_googlefontside'],
		'parabola_sidefontsize',
		'parabola_fontside',
		'parabola_googlefontside',
		__('General Font','parabola')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your widget titles to have. Leave 'General Font' and the general font values you selected will be used.","parabola")."</small></div><br>";
}

function  cryout_setting_sitetitlefont_fn() {
	global $parabolas;
	global $fonts;
	for ($i=30;$i<51;$i+=2): $sizes[] = "${i}px"; endfor;
	cryout_font_selector(
		$fonts,
		$sizes,
		$parabolas['parabola_sitetitlesize'],
		$parabolas['parabola_sitetitlefont'],
		$parabolas['parabola_sitetitlegooglefont'],
		'parabola_sitetitlesize',
		'parabola_sitetitlefont',
		'parabola_sitetitlegooglefont',
		__('General Font','parabola')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your site title and tagline to use. Leave 'General Font' and the general font values you selected will be used.","parabola")."</small></div><br>";
}

function  cryout_setting_menufont_fn() {
	global $parabolas;
	global $fonts;
	$sizes = array ( "8px" , "9px" , "10px" , "11px", "12px" , "13px" , "14px" , "15px" , "16px" , "17px" , "18px", "19px", "20px");
	cryout_font_selector(
		$fonts,
		$sizes,
		$parabolas['parabola_menufontsize'],
		$parabolas['parabola_menufont'],
		$parabolas['parabola_menugooglefont'],
		'parabola_menufontsize',
		'parabola_menufont',
		'parabola_menugooglefont',
		__('General Font','parabola')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your main menu to use. Leave 'General Font' and the general font values you selected will be used.","parabola")."</small></div><br>";
}


//SELECT - Name: parabola_settings[fontsubheader]
function  cryout_setting_fontheadings_fn() {
	global $parabolas;
	global $fonts;
	//$sizes = array ( "0.8em", "0.9em","1em","1.1em","1.2em","1.3em","1.4em","1.5em","1.6em","1.7em","1.8em","1.9em","2em");
	$sizes = array("60%","70%","80%","90%","100%","110%","120%","130%","140%","150%");
	cryout_font_selector(
		$fonts,
		$sizes,
		$parabolas['parabola_headingsfontsize'],
		$parabolas['parabola_headingsfont'],
		$parabolas['parabola_headingsgooglefont'],
		'parabola_headingsfontsize',
		'parabola_headingsfont',
		'parabola_headingsgooglefont',
		__('General Font','parabola')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your headings to have (h1 - h6 tags will be affected). Leave 'General Font' and the general font values you selected will be used.","parabola")."</small></div><br>";
}

//SELECT - Name: parabola_settings[textalign]
function  cryout_setting_textalign_fn() {
	global $parabolas;
	$items = array ("Default" , "Left" , "Right" , "Justify" , "Center");
	$itemsare = array( __("Default","parabola"), __("Left","parabola"), __("Right","parabola"), __("Justify","parabola"), __("Center","parabola"));
	echo "<select id='parabola_textalign' name='parabola_settings[parabola_textalign]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_textalign'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("This overwrites the text alignment in posts and pages. Leave 'Default' for normal settings (alignment will remain as declared in posts, comments etc.).","parabola")."</small></div>";
}

//SELECT - Name: parabola_settings[parindent]
function  cryout_setting_parindent_fn() {
	global $parabolas;
	$items = array ("0px" , "5px" , "10px" , "15px" , "20px");
	echo "<select id='parabola_parindent' name='parabola_settings[parabola_parindent]'>";
foreach($items as $item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_parindent'],$item);
	echo ">$item</option>";
}
	echo "</select>";
	echo "<div><small>".__("Choose the indent for your paragraphs.","parabola")."</small></div>";
}


//CHECKBOX - Name: parabola_settings[headerindent]
function cryout_setting_headingsindent_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_headingsindent' name='parabola_settings[parabola_headingsindent]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_headingsindent'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Disable the default headings indent (left margin).","parabola")."</small></div>";
}

//SELECT - Name: parabola_settings[lineheight]
function  cryout_setting_lineheight_fn() {
	global $parabolas;
	$items = array ("0.8em" , "0.9em", "1.0em" , "1.1em" , "1.2em" , "1.3em", "1.4em" , "1.5em" , "1.6em" , "1.7em" , "1.8em" , "1.9em" , "2.0em");
	$itemsare = array( "0.8em" , "0.9em", "1.0em" , "1.1em" , "1.2em" , "1.3em", "1.4em" , "1.5em" , "1.6em" , "1.7em" , "1.8em" , "1.9em" , "2.0em");
	echo "<select id='parabola_lineheight' name='parabola_settings[parabola_lineheight]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_lineheight'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Text line height. The height between 2 rows of text.","parabola")."</small></div>";
}

//SELECT - Name: parabola_settings[wordspace]
function  cryout_setting_wordspace_fn() {
	global $parabolas;
	$items = array ("Default" ,"-3px" , "-2px", "-1px" , "0px" , "1px" , "2px", "3px" , "4px" , "5px" , "10px");
	$itemsare = array( __("Default","parabola"),"-3px" , "-2px", "-1px" , "0px" , "1px" , "2px", "3px" , "4px" , "5px" , "10px");
	echo "<select id='parabola_wordspace' name='parabola_settings[parabola_wordspace]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_wordspace'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("The space between <i>words</i>. Leave 'Default' for normal settings (size value will be as set in the CSS).","parabola")."</small></div>";
}

//SELECT - Name: parabola_settings[letterspace]
function  cryout_setting_letterspace_fn() {
	global $parabolas;
	$items = array ("Default" ,"-0.05em" , "-0.04em", "-0.03em" , "-0.02em" , "-0.01em" , "0.01em", "0.02em" , "0.03em" , "0.04em" , "0.05em");
	$itemsare = array( __("Default","parabola"),"-0.05em" , "-0.04em", "-0.03em" , "-0.02em" , "-0.01em" , "0.01em", "0.02em" , "0.03em" , "0.04em" , "0.05em");
	echo "<select id='parabola_letterspace' name='parabola_settings[parabola_letterspace]'>";
     foreach($items as $id=>$item) {
     	echo "<option value='$item'";
     	selected($parabolas['parabola_letterspace'],$item);
     	echo ">$itemsare[$id]</option>";
     }
	echo "</select>";
	echo "<div><small>".__("The space between <i>letters</i>. Leave 'Default' for normal settings (size value will be as set in the CSS).","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[textshadow]
function cryout_setting_paragraphspace_fn() {
	global $parabolas;
	$items[]="0.0em";
	for ($i=0.5;$i<=1.5;$i+=0.1) {
		$items[] = number_format($i,1)."em";
		}
	echo "<select id='parabola_paragraphspace' name='parabola_settings[parabola_paragraphspace]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_paragraphspace'],$item);
	echo ">$items[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Select the spacing between the paragraphs.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[headerindent]
function cryout_setting_uppercasetext_fn() {
	global $parabolas;
	$items = array (1, 0);
	$itemsare = array( __("Default (enabled)","parabola"), __("Disable everywhere","parabola"));
	echo "<select id='parabola_uppercasetext' name='parabola_settings[parabola_uppercasetext]'>";
     foreach($items as $id=>$item) {
     	echo "<option value='$item'";
     	selected($parabolas['parabola_uppercasetext'],$item);
     	echo ">$itemsare[$id]</option>";
     }
	echo "</select>";
	echo "<div><small>".__("Disable the built-in theme uppercase text styling. Note that some bult-in fonts only support upercase text and these will not be affected.","parabola")."</small></div>";
}

////////////////////////////////
//// APPEREANCE SETTINGS ///////
////////////////////////////////

function cryout_setting_sitebackground_fn() {
     echo "<a href=\"?page=custom-background\" class=\"button\" target=\"_blank\">".__('Define background image','parabola')."</a>";
} // cryout_setting_sitebackground_fn()

function  cryout_setting_generalcolors_fn() {
	global $parabolas;
	echo '<h4>'.__('Background:','parabola').'</h4>';
	cryout_color_field('parabola_backcolorheader',__('Header Background','parabola'),$parabolas['parabola_backcolorheader']);
	cryout_color_field('parabola_backcolormain',__('Main Site Background','parabola'),$parabolas['parabola_backcolormain']);
	cryout_color_field('parabola_backcolorfooterw',__('Footer Widgets Area Background','parabola'),$parabolas['parabola_backcolorfooterw']);
	cryout_color_field('parabola_backcolorfooter',__('Footer Background','parabola'),$parabolas['parabola_backcolorfooter']);
	echo '<br class="colors-br" /><h4>'.__('Text:','parabola').'</h4>';
	cryout_color_field('parabola_contentcolortxt',__('General Text','parabola'),$parabolas['parabola_contentcolortxt']);
	cryout_color_field('parabola_contentcolortxtlight',__('General Lighter Text','parabola'),$parabolas['parabola_contentcolortxtlight']);
	cryout_color_field('parabola_footercolortxt',__('Footer Text','parabola'),$parabolas['parabola_footercolortxt']);
	echo "<div><small>".__("The site background features 4 separately coloured areas.<br />The general text colour applies to all text on the website that is not controlled by any other option.","parabola")."</small></div>";
}

function  cryout_setting_accentcolors_fn() {
	global $parabolas;
	cryout_color_field('parabola_accentcolora',__('Accent Color #1','parabola'),$parabolas['parabola_accentcolora']);
	cryout_color_field('parabola_accentcolorb',__('Accent Color #2','parabola'),$parabolas['parabola_accentcolorb']);
	cryout_color_field('parabola_accentcolorc',__('Accent Color #3','parabola'),$parabolas['parabola_accentcolorc']);
	cryout_color_field('parabola_accentcolord',__('Accent Color #4','parabola'),$parabolas['parabola_accentcolord']);
	cryout_color_field('parabola_accentcolore',__('Accent Color #5','parabola'),$parabolas['parabola_accentcolore']);
	echo "<div><small>".__("Accents #1 and #2 should either be the same as the link colours or be separate from all other colours on the site.","parabola");
	echo __("Accent #5 is used for input fields and buttons backgrounds, borders and lines.","parabola")."<br />";
    echo __("Accents #3 and #4 should be the lighter/darker than the content background colour, being used as borders/shades on elements where accent #5 is background colour.","parabola")."</small></div>";
}

function  cryout_setting_titlecolors_fn() {
	global $parabolas;
	echo '<h4>'.__('Background:','parabola').'</h4>';
	cryout_color_field('parabola_descriptionbg',__('Site Description Background','parabola'),$parabolas['parabola_descriptionbg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','parabola').'</h4>';
	cryout_color_field('parabola_titlecolor',__('Site Title','parabola'),$parabolas['parabola_titlecolor']);
	cryout_color_field('parabola_descriptioncolor',__('Site Description','parabola'),$parabolas['parabola_descriptioncolor']);
//	echo "<div><small>".."</small></div>";
}

function  cryout_setting_menucolors_fn() {
	global $parabolas;
	echo '<h4>'.__('Background:','parabola').'</h4>';
	cryout_color_field('parabola_menucolorbgdefault',__('Menu Item Background','parabola'),$parabolas['parabola_menucolorbgdefault']);
	cryout_color_field('parabola_menucolorbghover',__('Menu Item Background on Hover','parabola'),$parabolas['parabola_menucolorbghover']);
	cryout_color_field('parabola_menucolorbgactive',__('Active Menu Item Background','parabola'),$parabolas['parabola_menucolorbgactive']);
	cryout_color_field('parabola_menucolorshadow',__('Submenu Shadow','parabola'),$parabolas['parabola_menucolorshadow']);
	echo '<br class="colors-br" /><h4>'.__('Text:','parabola').'</h4>';
	cryout_color_field('parabola_menucolortxtdefault',__('Menu Item Text','parabola'),$parabolas['parabola_menucolortxtdefault']);
	cryout_color_field('parabola_menucolortxthover',__('Menu Item Text on Hover','parabola'),$parabolas['parabola_menucolortxthover']);
	cryout_color_field('parabola_menucolortxtactive',__('Active Menu Item Text','parabola'),$parabolas['parabola_menucolortxtactive']);
	//cryout_color_field('',__('','parabola'),$parabolas[''],__("","parabola"));
	echo "<div><small>".__("These colours apply to the main site menu (and dropdown elements).","parabola")."</small></div>";
}

function  cryout_setting_topmenucolors_fn() {
	global $parabolas;
	echo '<h4>'.__('Background:','parabola').'</h4>';
	cryout_color_field('parabola_topmenucolorbg',__('Top Menu Background','parabola'),$parabolas['parabola_topmenucolorbg']);
	cryout_color_field('parabola_topmenucolorbghover',__('Top Menu Background Hover','parabola'),$parabolas['parabola_topmenucolorbghover']);
	echo '<br class="colors-br" /><h4>'.__('Text:','parabola').'</h4>';
	cryout_color_field('parabola_topmenucolortxt',__('Top Menu Link','parabola'),$parabolas['parabola_topmenucolortxt']);
	cryout_color_field('parabola_topmenucolortxthover',__('Top Menu Link Hover','parabola'),$parabolas['parabola_topmenucolortxthover']);
	echo "<div><small>".__("These colours apply to the top site menu (appears above the header when enabled).","parabola")."</small></div>";
}

function  cryout_setting_contentcolors_fn() {
	global $parabolas;
	cryout_color_field('parabola_contentcolorbg',__('Content Background','parabola'),$parabolas['parabola_contentcolorbg']);
	cryout_color_field('parabola_contentcolortxttitle',__('Page/Post Title','parabola'),$parabolas['parabola_contentcolortxttitle']);
	cryout_color_field('parabola_contentcolortxttitlehover',__('Page/Post Title Hover','parabola'),$parabolas['parabola_contentcolortxttitlehover']);
	cryout_color_field('parabola_contentcolortxtheadings',__('Content Headings','parabola'),$parabolas['parabola_contentcolortxtheadings']);
	echo "<div><small>".__("Content colours apply to post and page areas of the site.","parabola")."</small></div>";
}

function  cryout_setting_frontpagecolors_fn(){
     global $parabolas;
     cryout_color_field('parabola_fronttitlecolor',__('Titles Color','parabola'),$parabolas['parabola_fronttitlecolor']);
	cryout_color_field('parabola_fpsliderbordercolor',__('Slider Border Color','parabola'),$parabolas['parabola_fpsliderbordercolor']);
	cryout_color_field('parabola_fpslidercaptioncolor',__('Slider Caption Text Color','parabola'),$parabolas['parabola_fpslidercaptioncolor']);
	cryout_color_field('parabola_fpslidercaptionbg',__('Slider Caption Background','parabola'),$parabolas['parabola_fpslidercaptionbg']);
	//echo "<div class='slmini'><b>".__("Border Color:","parabola")."</b> ";
	//echo '<input type="text" id="parabola_fpsliderbordercolor" class="colorthingy" name="parabola_settings[parabola_fpsliderbordercolor]"  style="width:100px;" value="'.esc_attr( $parabolas['parabola_fpsliderbordercolor'] ).'" />';
	//echo '<div id="parabola_fpsliderbordercolor2"></div></div>';
     echo "<div><small>".__("These colours apply to specific areas of the presentation page.","parabola")."</small></div>";
}

function  cryout_setting_sidecolors_fn() {
	global $parabolas;
	echo '<h4>'.__('Background:','parabola').'</h4>';
	cryout_color_field('parabola_sidebg',__('Sidebars Background','parabola'),$parabolas['parabola_sidebg']);
	cryout_color_field('parabola_sidetitlebg',__('Sidebars Widget Title Background','parabola'),$parabolas['parabola_sidetitlebg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','parabola').'</h4>';
	cryout_color_field('parabola_sidetxt',__('Sidebars Text','parabola'),$parabolas['parabola_sidetxt']);
	cryout_color_field('parabola_sidetitletxt',__('Sidebars Widget Title Text','parabola'),$parabolas['parabola_sidetitletxt']);
	echo "<div><small>".__("These colours apply to the widgets placed in either sidebar.","parabola")."</small></div>";
}


function  cryout_setting_widgetcolors_fn() {
	global $parabolas;
	echo '<h4>'.__('Background:','parabola').'</h4>';
	cryout_color_field('parabola_widgetbg',__('Footer Widgets Background','parabola'),$parabolas['parabola_widgetbg']);
	cryout_color_field('parabola_widgettitlebg',__('Footer Widgets Title Background','parabola'),$parabolas['parabola_widgettitlebg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','parabola').'</h4>';
	cryout_color_field('parabola_widgettxt',__('Footer Widget Text','parabola'),$parabolas['parabola_widgettxt']);
	cryout_color_field('parabola_widgettitletxt',__('Footer Widgets Title Text','parabola'),$parabolas['parabola_widgettitletxt']);
	echo "<div><small>".__("These colours apply to the widgets in the footer area.","parabola")."</small></div>";
}

function  cryout_setting_linkcolors_fn() {
	global $parabolas;
	echo '<h4>'.__('General:','parabola').'</h4>';
	cryout_color_field('parabola_linkcolortext',__('General Links','parabola'),$parabolas['parabola_linkcolortext']);
	cryout_color_field('parabola_linkcolorhover',__('General Links Hover','parabola'),$parabolas['parabola_linkcolorhover']);
	echo '<br class="colors-br" /><h4>'.__('Sidebar Widgets:','parabola').'</h4>';
	cryout_color_field('parabola_linkcolorside',__('Sidebar Widgets Links','parabola'),$parabolas['parabola_linkcolorside']);
	cryout_color_field('parabola_linkcolorsidehover',__('Sidebar Widgets Links Hover','parabola'),$parabolas['parabola_linkcolorsidehover']);
	echo '<br class="colors-br" /><h4>'.__('Footer Widgets:','parabola').'</h4>';
	cryout_color_field('parabola_linkcolorwooter',__('Footer Widgets Links','parabola'),$parabolas['parabola_linkcolorwooter']);
	cryout_color_field('parabola_linkcolorwooterhover',__('Footer Widgets Links Hover','parabola'),$parabolas['parabola_linkcolorwooterhover']);
	echo '<br class="colors-br" /><h4>'.__('Footer:','parabola').'</h4>';
	cryout_color_field('parabola_linkcolorfooter',__('Footer Links','parabola'),$parabolas['parabola_linkcolorfooter']);
	cryout_color_field('parabola_linkcolorfooterhover',__('Footer Links Hover','parabola'),$parabolas['parabola_linkcolorfooterhover']);
	echo "<div><small>".__("Footer colours include the footer menu colours.","parabola")."</small></div>";
}

function  cryout_setting_caption_fn() {
     global $parabolas;
	$items = array ("caption-clear" , "caption-light" , "caption-accented" , "caption-dark");
	$itemsare = array( __("Clear","parabola"), __("Light","parabola"), __("Accented","parabola"), __("Dark","parabola"));
	echo "<select id='parabola_caption' name='parabola_settings[parabola_caption]'>";
     foreach($items as $id=>$item):
     	echo "<option value='$item'";
     	selected($parabolas['parabola_caption'],$item);
     	echo ">$itemsare[$id]</option>";
     endforeach;
	echo "</select>";
	echo "<div><small>".__("This setting changes the look of your captions. Images that are not inserted through captions will not be affected.","parabola")."</small></div>";
}

function cryout_setting_metaback_fn() {
	global $parabolas;
	$items = array ("meta-clear", "meta-border" , "meta-accented", "meta-light", "meta-dark");
	$itemsare = array(__("Clear","parabola"), __("Border only","parabola"), __("Accented","parabola"), __("Light","parabola"), __("Dark","parabola"));
	echo "<select id='parabola_metaback' name='parabola_settings[parabola_metaback]'>";
     foreach($items as $id=>$item):
     	echo "<option value='$item'";
     	selected($parabolas['parabola_metaback'],$item);
     	echo ">$itemsare[$id]</option>";
     endforeach;
	echo "</select>";
	echo "<div><small>".__("The background for your meta areas (author, date, category, tags and edit button).","parabola")."</small></div>";
}

////////////////////////////////
//// GRAPHICS SETTINGS /////////
////////////////////////////////

//CHECKBOX - Name: parabola_settings[breadcrumbs]
function cryout_setting_breadcrumbs_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_breadcrumbs' name='parabola_settings[parabola_breadcrumbs]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_breadcrumbs'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show breadcrumbs at the top of the content area. Breadcrumbs are a form of navigation that keeps track of your location withtin the site.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[pagination]
function cryout_setting_pagination_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_pagination' name='parabola_settings[parabola_pagination]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_pagination'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show numbered pagination. Where there is more than one page, instead of the bottom <b>Older Posts</b> and <b>Newer posts</b> links you have a numbered pagination. ","parabola")."</small></div>";
}

function cryout_setting_menualign_fn() {
	global $parabolas;
	$items = array ("left" , "center", "right", "rightmulti");
	$itemsare = array( __("Left","parabola"), __("Center","parabola"), __("Right", "parabola"), __("Right (multiline)", "parabola"));
	echo "<select id='parabola_menualign' name='parabola_settings[parabola_menualign]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_menualign'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Select the desired main menu items alignment. Center option is only valid for single line menus.","parabola")."</small></div>";
}

function cryout_setting_triangles_fn() {
	global $parabolas;
	$items = array (1,0);
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_triangles' name='parabola_settings[parabola_triangles]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_triangles'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Please, please... do not push this button. You have no idea what it...","parabola")."</small></div>";
}

// RADIO-BUTTON - Name: parabola_settings[image]
function cryout_setting_image_fn() {
	global $parabolas;
	$items = array("parabola-image-none", "parabola-image-one", "parabola-image-two", "parabola-image-three","parabola-image-four","parabola-image-five");
	echo "<div style='background:#FFF;'>";
	foreach($items as $item) {
		$checkedClass = ($parabolas['parabola_image_style']==$item) ? ' checkedClass' : '';
		echo " <label id='$item' for='$item$item' class='images $checkedClass'><input ";
			checked($parabolas['parabola_image_style'],$item);
		echo " value='$item' id='$item$item' onClick=\"changeBorder('$item','images');\" name='parabola_settings[parabola_image_style]' type='radio' /><img class='$item'  src='".get_template_directory_uri()."/admin/images/testimg.jpg'/></label>";
	}
	echo "</div>";
	echo "<div><small>".__("The border style for your images. Only images inserted in your posts and pages will be affected. ","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[contentlist]
function cryout_setting_contentlist_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_contentlist' name='parabola_settings[parabola_contentlist]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_contentlist'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show bullets next to lists in your content area (posts, pages etc.).","parabola")."</small></div>";

}


//CHECKBOX - Name: parabola_settings[pagetitle]
function cryout_setting_pagetitle_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_pagetitle' name='parabola_settings[parabola_pagetitle]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_pagetitle'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show titles on pages.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[categtitle]
function cryout_setting_categtitle_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_categtitle' name='parabola_settings[parabola_categtitle]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_categtitle'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show titles on Categories and Archives.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[tables]
function cryout_setting_tables_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_tables' name='parabola_settings[parabola_tables]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_tables'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide table borders and background color.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[comtext]
function cryout_setting_comtext_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_comtext' name='parabola_settings[parabola_comtext]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_comtext'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the explanatory text under the comments form (starts with  <i>You may use these HTML tags and attributes:...</i>).","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[comclosed]
function cryout_setting_comclosed_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide in posts", "Hide in pages", "Hide everywhere");
	$itemsare = array( __("Show","parabola"), __("Hide in posts","parabola"), __("Hide in pages","parabola"), __("Hide everywhere","parabola"));
	echo "<select id='parabola_comclosed' name='parabola_settings[parabola_comclosed]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_comclosed'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the <b>Comments are closed</b> text that by default shows up on pages or posts with comments disabled.","parabola")."</small></div>";
}


//CHECKBOX - Name: parabola_settings[comoff]
function cryout_setting_comoff_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_comoff' name='parabola_settings[parabola_comoff]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_comoff'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the <b>Comments off</b> text next to posts that have comments disabled.","parabola")."</small></div>";
}


//CHECKBOX - Name: parabola_settings[backtop]
function cryout_setting_backtop_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_backtop' name='parabola_settings[parabola_backtop]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_backtop'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Enable the Back to Top button. The button appears after scrolling the page down.","parabola")."</small></div>";
}


////////////////////////////////
//// POST SETTINGS /////////////
////////////////////////////////

//CHECKBOX - Name: parabola_settings[postdate]
function cryout_setting_postcomlink_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_postcomlink' name='parabola_settings[parabola_postcomlink]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_postcomlink'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show the <strong>Leave a comment</strong> or <strong>x Comments</strong> next to posts or post excerpts.","parabola")."</small></div>";
}

function cryout_setting_postdatetime_fn() {
	global $parabolas;
	$items = array ("datetime", "date", "time", "hide");
	$itemsare = array( __("Date and time","parabola"), __("Date only","parabola"), __("Time only","parabola"), __("Hide both","parabola"));
	echo "<select id='parabola_postdatetime' name='parabola_settings[parabola_postdatetime]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_postdatetime'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show the post date and time.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[postauthor]
function cryout_setting_postauthor_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_postauthor' name='parabola_settings[parabola_postauthor]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_postauthor'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show the post author.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[postcateg]
function cryout_setting_postcateg_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_postcateg' name='parabola_settings[parabola_postcateg]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_postcateg'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the post category.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[posttag]
function cryout_setting_posttag_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_posttag' name='parabola_settings[parabola_posttag]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_posttag'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the post tags.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[postbook]
function cryout_setting_postbook_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","parabola"), __("Hide","parabola"));
	echo "<select id='parabola_postbook' name='parabola_settings[parabola_postbook]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_postbook'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the 'Bookmark permalink'.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[postmetas]
function cryout_setting_postmetas_fn() {
	global $parabolas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show enabled","parabola"), __("Hide all","parabola"));
	echo "<select id='parabola_postmetas' name='parabola_settings[parabola_postmetas]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_postmetas'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the meta bar. All meta info/areas listed below will also be hidden.","parabola")."</small></div>";
}


////////////////////////////////
//// EXCERPT SETTINGS /////////////
////////////////////////////////


//CHECKBOX - Name: parabola_settings[excerpthome]
function cryout_setting_excerpthome_fn() {
	global $parabolas;
	$items = array ("Excerpt" , "Full Post");
	$itemsare = array( __("Excerpt","parabola"), __("Full Post","parabola"));
	echo "<select id='parabola_excerpthome' name='parabola_settings[parabola_excerpthome]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_excerpthome'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Excerpts on the main page. Only standard posts will be affected. All other post formats (aside, image, chat, quote etc.) have their specific formating.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[excerptsticky]
function cryout_setting_excerptsticky_fn() {
	global $parabolas;
	$items = array ("Excerpt" , "Full Post");
	$itemsare = array( __("Excerpt","parabola"), __("Full Post","parabola"));
	echo "<select id='parabola_excerptsticky' name='parabola_settings[parabola_excerptsticky]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_excerptsticky'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Choose if you want the sticky posts on your home page to be visible in full or just the excerpts. ","parabola")."</small></div>";
}


//CHECKBOX - Name: parabola_settings[excerptarchive]
function cryout_setting_excerptarchive_fn() {
	global $parabolas;
	$items = array ("Excerpt" , "Full Post");
	$itemsare = array( __("Excerpt","parabola"), __("Full Post","parabola"));
	echo "<select id='parabola_excerptarchive' name='parabola_settings[parabola_excerptarchive]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_excerptarchive'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Excerpts on archive, categroy and search pages. Same as above, only standard posts will be affected.","parabola")."</small></div>";
}


// TEXTBOX - Name: parabola_settings[excerptwords]
function cryout_setting_excerptwords_fn() {
	global $parabolas;
	echo "<input id='parabola_excerptwords' name='parabola_settings[parabola_excerptwords]' size='6' type='text' value='".esc_attr( $parabolas['parabola_excerptwords'] )."'  />";
	echo "<div><small>".__("The number of words for excerpts. When that number is reached the post will be interrupted by a <i>Continue reading</i> link that will take the reader to the full post page.","parabola")."</small></div>";
}

// TEXTBOX - Name: parabola_settings[excerptdots]
function cryout_setting_excerptdots_fn() {
	global $parabolas;
	echo "<input id='parabola_excerptdots' name='parabola_settings[parabola_excerptdots]' size='40' type='text' value='".esc_attr( $parabolas['parabola_excerptdots'] )."'  />";
	echo "<div><small>".__("Replaces the three dots ('[...])' that are appended automatically to excerpts.","parabola")."</small></div>";
}

// TEXTBOX - Name: parabola_settings[excerptcont]
function cryout_setting_excerptcont_fn() {
	global $parabolas;
	echo "<input id='parabola_excerptcont' name='parabola_settings[parabola_excerptcont]' size='40' type='text' value='".esc_attr( $parabolas['parabola_excerptcont'] )."'  />";
	echo "<div><small>".__("Edit the 'Continue Reading' link added to your post excerpts.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[excerpttags]
function cryout_setting_excerpttags_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_excerpttags' name='parabola_settings[parabola_excerpttags]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_excerpttags'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("By default WordPress excerpts remove all HTML tags (&lt;pre&gt;, &lt;a&gt;, &lt;b&gtl') and all others) and only clean text is left in the excerpt.
Enabling this option allows HTML tags to remain in excerpts so all your default formating will be kept.<br /> <b>Just a warning: </b>If HTML tags are enabled, you have to make sure
they are not left open. So if within your post you have an opened HTML tag but the except ends before that tag closes, the rest of the site will be contained in that HTML tag. -- Leave 'Disable' if unsure -- ","parabola")."</small></div>";
}


////////////////////////////////
/// FEATURED IMAGE SETTINGS ////
////////////////////////////////


//CHECKBOX - Name: parabola_settings[fpost]
function cryout_setting_fpost_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_fpost' name='parabola_settings[parabola_fpost]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_fpost'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	$checkedClass = ($parabolas['parabola_fpostlink']=='1') ? ' checkedClass' : '';
	echo " <label style='border:none;margin-left:10px;' id='$items[0]' for='$items[0]$items[0]' class='socialsdisplay $checkedClass'><input type='hidden' name='parabola_settings[parabola_fpostlink]' value='0' /><input ";
		 checked($parabolas['parabola_fpostlink'],'1');
	echo " value='1' id='$items[0]$items[0]'  name='parabola_settings[parabola_fpostlink]' type='checkbox' /> ".
	__("Link the thumbail to the post","parabola")."</label>";
	echo "<div><small>".__("Show featured images as thumbnails on posts. The images must be selected for each post in the Featured Image section.","parabola")."</small></div>";
}

//CHECKBOX - Name: parabola_settings[fauto]
function cryout_setting_fauto_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_fauto' name='parabola_settings[parabola_fauto]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_fauto'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show the first image that you inserted in a post as a thumbnail. If there is a Featured Image selected for that post, it will have priority.","parabola")."</small></div>";
}


//CHECKBOX - Name: parabola_settings[falign]
function cryout_setting_falign_fn() {
	global $parabolas;
	$items = array ("Left" , "Center", "Right");
	$itemsare = array( __("Left","parabola"), __("Center","parabola"), __("Right","parabola"));
	echo "<select id='parabola_falign' name='parabola_settings[parabola_falign]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_falign'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Thumbnail alignment.","parabola")."</small></div>";
}


// TEXTBOX - Name: parabola_settings[fwidth]
function cryout_setting_fsize_fn() {
	global $parabolas;
	echo "<input id='parabola_fwidth' name='parabola_settings[parabola_fwidth]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fwidth'] )."'  />px ".__("(width)","parabola")." <b>X</b> ";
	echo "<input id='parabola_fheight' name='parabola_settings[parabola_fheight]' size='4' type='text' value='".esc_attr( $parabolas['parabola_fheight'] )."'  />px ".__("(height)","parabola")."";

	$checkedClass = ($parabolas['parabola_fcrop']=='1') ? ' checkedClass' : '';

		echo " <label id='fcrop' for='parabola_fcrop' class='socialsdisplay $checkedClass'><input  ";
		 checked($parabolas['parabola_fcrop'],'1');
	echo "value='1' id='parabola_fcrop'  name='parabola_settings[parabola_fcrop]' type='checkbox' /> ".
	__("Crop images to exact size.","parabola")." </label>";


	echo "<div><small>".__("The size (in pixels) for your thumbnails. By default imges will be scaled with aspect ratio kept. Choose to crop the images if you want the exact size.","parabola")."</small></div>";
}


//CHECKBOX - Name: parabola_settings[fheader]
function cryout_setting_fheader_fn() {
	global $parabolas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_fheader' name='parabola_settings[parabola_fheader]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($parabolas['parabola_fheader'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show featured images on headers. The header will be replaced with a featured image if you selected it as a Featured Image in the post and if it is bigger or at least equal to the current header size.","parabola")."</small></div>";
}


////////////////////////
/// SOCIAL SETTINGS ////
////////////////////////

// TEXTBOX - Name: parabola_settings[socialX]
function cryout_setting_social_master($i) {
	$cryout_special_keys = array('Mail', 'Skype');
	$cryout_social_small = array (
		'',__('Select your desired Social network from the left dropdown menu and insert your corresponding address in the right input field. (ex: <i>http://www.facebook.com/yourname</i> )','parabola'),
		'',__("You can also choose if you want the link to open in a new window and what title to dispaly while hovering over the icon.",'parabola'),
		'',__("You can show up to 5 different social icons from over 35 social networks.",'parabola'),
		'',__("You can leave any number of inputs empty.",'parabola'),
		'',__("You can change the background for your social colors from the colors settings section.",'parabola')
		);
	$j=$i+1;
	global $parabolas, $socialNetworks;
	echo "<select id='parabola_social$i' name='parabola_settings[parabola_social$i]'>";
	foreach($socialNetworks as $item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_social'.$i],$item);
		echo ">$item</option>";
	}
	echo "</select><span class='address_span'> &raquo; </span>";

	if (in_array($parabolas['parabola_social'.$i],$cryout_special_keys)) :
		$cryout_current_social = esc_html( $parabolas['parabola_social'.$j] );
	else :
		$cryout_current_social = esc_url( $parabolas['parabola_social'.$j] );
	endif;
	// Social Link
	echo "<input id='parabola_social$j' placeholder='".__("Social Network Link","parabola")."' name='parabola_settings[parabola_social$j]' size='32' type='text'  value='$cryout_current_social' />";
	// Social Open in new window
	$checkedClass = ($parabolas['parabola_social_target'.$i]=='1') ? ' checkedClass' : '';
	echo " <label id='parabola_social_target$i' for='parabola_social_target$i$i' class='$checkedClass'><input ";
	 checked($parabolas['parabola_social_target'.$i],'1');
	echo " value='1' id='parabola_social_target$i$i' name='parabola_settings[parabola_social_target$i]' type='checkbox' /> ".__("Open in new window","parabola")." </label>";
	// Social Title
	echo "<input id='parabola_social_title$i$i' name='parabola_settings[parabola_social_title$i]' size='32' type='text' placeholder='".__("Social Network Title","parabola")."' value='".$parabolas['parabola_social_title'.$i]."' />";

	echo "<div><small>".$cryout_social_small[$i]."</small></div>";
}



function cryout_setting_socials1_fn() {
	cryout_setting_social_master(1);
}

function cryout_setting_socials2_fn() {
	cryout_setting_social_master(3);
}

// TEXTBOX - Name: parabola_settings[social3]
function cryout_setting_socials3_fn() {
cryout_setting_social_master(5);
}

// TEXTBOX - Name: parabola_settings[social4]
function cryout_setting_socials4_fn() {
cryout_setting_social_master(7);
}

// TEXTBOX - Name: parabola_settings[social5]
function cryout_setting_socials5_fn() {
cryout_setting_social_master(9);
}

// TEXTBOX - Name: parabola_settings[socialsdisplay]
function cryout_setting_socialsdisplay_fn() {
global $parabolas;
		$items = array( "Header", "CLeft", "CRight" , "Footer" ,"SLeft", "SRight");

		$checkedClass0 = ($parabolas['parabola_socialsdisplay0']=='1') ? ' checkedClass0' : '';
		$checkedClass1 = ($parabolas['parabola_socialsdisplay1']=='1') ? ' checkedClass1' : '';
		$checkedClass2 = ($parabolas['parabola_socialsdisplay2']=='1') ? ' checkedClass2' : '';
		$checkedClass3 = ($parabolas['parabola_socialsdisplay3']=='1') ? ' checkedClass3' : '';
		$checkedClass4 = ($parabolas['parabola_socialsdisplay4']=='1') ? ' checkedClass4' : '';
		$checkedClass5 = ($parabolas['parabola_socialsdisplay5']=='1') ? ' checkedClass5' : '';

	echo " <label id='$items[0]' for='$items[0]$items[0]' class='socialsdisplay $checkedClass0'><input ";
		 checked($parabolas['parabola_socialsdisplay0'],'1');
	echo " value='1' id='$items[0]$items[0]' name='parabola_settings[parabola_socialsdisplay0]' type='checkbox' /> ".__("Header","parabola")."</label>";

	echo " <label id='$items[3]' for='$items[3]$items[3]' class='socialsdisplay $checkedClass3'><input ";
		 checked($parabolas['parabola_socialsdisplay3'],'1');
	echo " value='1' id='$items[3]$items[3]' name='parabola_settings[parabola_socialsdisplay3]' type='checkbox' /> ".__("Footer","parabola")." </label>";

	echo " <label id='$items[4]' for='$items[4]$items[4]' class='socialsdisplay $checkedClass4'><input ";
		 checked($parabolas['parabola_socialsdisplay4'],'1');
	echo " value='1' id='$items[4]$items[4]' name='parabola_settings[parabola_socialsdisplay4]' type='checkbox' /> ".__("Left side","parabola")." </label>";

	echo " <label id='$items[5]' for='$items[5]$items[5]' class='socialsdisplay $checkedClass5'><input ";
		 checked($parabolas['parabola_socialsdisplay5'],'1');
	echo " value='1' id='$items[5]$items[5]' name='parabola_settings[parabola_socialsdisplay5]' type='checkbox' /> ".__("Right side","parabola")." </label>";

	echo "<br/>";

	echo " <label id='$items[1]' for='$items[1]$items[1]' class='socialsdisplay $checkedClass1'><input ";
		 checked($parabolas['parabola_socialsdisplay1'],'1');
	echo " value='1' id='$items[1]$items[1]' name='parabola_settings[parabola_socialsdisplay1]' type='checkbox' /> ".__("Left Sidebar","parabola")." </label>";

	echo " <label id='$items[2]' for='$items[2]$items[2]' class='socialsdisplay $checkedClass2'><input ";
		 checked($parabolas['parabola_socialsdisplay2'],'1');
	echo " value='1' id='$items[2]$items[2]' name='parabola_settings[parabola_socialsdisplay2]' type='checkbox' /> ".__("Right Sidebar","parabola")." </label>";

	echo "<div><small>".__("Choose the <b>areas</b> where to display the social icons.","parabola")."</small></div>";
}


////////////////////////
/// MISC SETTINGS ////
////////////////////////


// TEXTBOX - Name: parabola_settings[copyright]
function cryout_setting_copyright_fn() {
	global $parabolas;
	echo "<textarea id='parabola_copyright' name='parabola_settings[parabola_copyright]' rows='3' cols='70' type='textarea' >".esc_textarea($parabolas['parabola_copyright'])." </textarea>";
	echo "<div><small>".__("Insert custom text or HTML code that will appear in you footer. <br /> You can use HTML to insert links, images and special characters like &copy;.","parabola")."</small></div>";
}


// TEXTBOX - Name: parabola_settings[customcss]
function cryout_setting_customcss_fn() {
	global $parabolas;
	echo "<textarea id='parabola_customcss' name='parabola_settings[parabola_customcss]' rows='8' cols='70' type='textarea' >".esc_textarea(htmlspecialchars_decode($parabolas['parabola_customcss'], ENT_QUOTES))." </textarea>";
	echo "<div><small>".__("Insert your custom CSS here. Any CSS declarations made here will overwrite Parabola's (even the custom options specified right here in the Parabola Settings page). <br /> Your custom CSS will be preserved when updating the theme.","parabola")."</small></div>";
}

// TEXTBOX - Name: parabola_settings[customjs]
function cryout_setting_customjs_fn() {
	global $parabolas;
	echo "<textarea id='parabola_customjs' name='parabola_settings[parabola_customjs]' rows='8' cols='70' type='textarea' >".esc_textarea(htmlspecialchars_decode($parabolas['parabola_customjs']))." </textarea>";
	echo "<div><small>".__("Insert your custom Javascript code here. (Google Analytics and any other forms of Analytic software).","parabola")."</small></div>";
}

function cryout_setting_iecompat_fn() {
	global $parabolas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_iecompat' name='parabola_settings[parabola_iecompat]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_iecompat'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Enable extra compatibility tag for older Internet Explorer versions. Turning this option on will trigger W3C validation errors.","parabola")."</small></div>";
}

function cryout_setting_masonry_fn() {
	global $parabolas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_masonry' name='parabola_settings[parabola_masonry]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_masonry'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Disable to troubleshoot compatibility with plugins that dynamically add content to post lists and change length.","parabola")."</small></div>";
}

function cryout_setting_fitvids_fn() {
	global $parabolas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","parabola"), __("Disable","parabola"));
	echo "<select id='parabola_fitvids' name='parabola_settings[parabola_fitvids]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($parabolas['parabola_fitvids'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<input type='hidden' name='parabola_settings[parabola_current_admin_menu]' id='parabola_current' value='". $parabolas['parabola_current_admin_menu']."'>";
	echo "<div><small>".__("Disable to troubleshoot embedded video resize issues.","parabola")."</small></div>";
}

// FIN