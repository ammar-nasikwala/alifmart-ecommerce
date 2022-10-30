<?php
////////// MASTER CUSTOM STYLE FUNCTION //////////

function parabola_body_classes($classes) {
	$parabolas = parabola_get_theme_options();
	$classes[] = $parabolas['parabola_image_style'];
	$classes[] = $parabolas['parabola_caption'];
	$classes[] = $parabolas['parabola_metaback'];
	if ($parabolas['parabola_triangles']) $classes[] = 'parabola_triagles'; else $classes[] = 'parabola_notriangles';

	$magazine_layout = FALSE;
	if ($parabolas['parabola_magazinelayout'] == "Enable") {
		if (is_front_page()) {
			if ( ($parabolas['parabola_frontpage'] == "Enable") && (intval($parabolas['parabola_frontpostsperrow']) == 1) ) { /* no magazine layout */ }
																											 else { $magazine_layout = TRUE; }
		} else {
			$magazine_layout = TRUE;
		}
	}
	if ( is_front_page() && ($parabolas['parabola_frontpage'] == "Enable") && (intval($parabolas['parabola_frontpostsperrow']) == 2) ) { $magazine_layout = TRUE; }

	if ($magazine_layout) $classes[] = 'magazine-layout';

	switch ($parabolas['parabola_menualign']):
		case "center": $classes[] = 'parabola-menu-center'; break;
		case "right": $classes[] = 'parabola-menu-right'; break;
		case "rightmulti": $classes[] = 'parabola-menu-rightmulti'; break;
		default: $classes[] = 'parabola-menu-left'; break;
	  endswitch;

	return $classes;
}
add_filter('body_class','parabola_body_classes');

function parabola_custom_styles() {
	$parabolas= parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = esc_attr($value) ; }
	$totalwidth= $parabola_sidewidth+$parabola_sidebar;
	$contentSize = $parabola_sidewidth;
	$sidebarSize= $parabola_sidebar;
	ob_start();

?>
<style type="text/css">
<?php
////////// LAYOUT DIMENSIONS. //////////
?>
#header, #main, .topmenu, #colophon, #footer2-inner { <?php echo (($parabola_mobile == 'Enable') ? 'max-' : '');?>width: <?php echo ($totalwidth); ?>px; }
<?php
////////// COLUMNS //////////

$colPadding = 20;
?>
#container.one-column { }
#container.two-columns-right #secondary { width:<?php echo $sidebarSize; ?>px; float:right; }
#container.two-columns-right #content { width:<?php echo $contentSize-$colPadding; ?>px; float:left; } /*fallback*/
#container.two-columns-right #content { width:calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); float:left; }
#container.two-columns-left #primary { width:<?php echo $sidebarSize; ?>px; float:left; }
#container.two-columns-left #content { width:<?php echo $contentSize-$colPadding; ?>px; float:right; } /*fallback*/
#container.two-columns-left #content { 	width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); float:right;
										width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding; ?>px);
										width:calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); }

#container.three-columns-right .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-right #primary { margin-left:<?php echo $colPadding; ?>px; margin-right:<?php echo $colPadding; ?>px; }
#container.three-columns-right #content { width:<?php echo $contentSize-$colPadding*2; ?>px; float:left; } /*fallback*/
#container.three-columns-right #content { 	width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:left;
											width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px);
											width:calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px);}

#container.three-columns-left .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-left #secondary {margin-left:<?php echo $colPadding; ?>px; margin-right:<?php echo $colPadding; ?>px; }
#container.three-columns-left #content { width:<?php echo $contentSize-$colPadding*2; ?>px; float:right;} /*fallback*/
#container.three-columns-left #content { width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
										 width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px);
										 width:calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); }

#container.three-columns-sided .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-sided #secondary { float:right; }
#container.three-columns-sided #content { width:<?php echo $contentSize-$colPadding*2; ?>px; float:right; /*fallback*/
										  width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
										  width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
										  width:calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
		                                  margin: 0 <?php echo ($sidebarSize/2)+$colPadding;?>px 0 <?php echo -($contentSize+$sidebarSize); ?>px; }

<?php
////////// FONTS //////////
$parabola_googlefont = str_replace('+',' ',preg_replace('/:.*/i','',$parabola_googlefont));
$parabola_googlefonttitle = str_replace('+',' ',preg_replace('/:.*/i','',$parabola_googlefonttitle));
$parabola_googlefontside = str_replace('+',' ',preg_replace('/:.*/i','',$parabola_googlefontside));
$parabola_headingsgooglefont = str_replace('+',' ',preg_replace('/:.*/i','',$parabola_headingsgooglefont));
$parabola_sitetitlegooglefont = str_replace('+',' ',preg_replace('/:.*/i','',$parabola_sitetitlegooglefont));
$parabola_menugooglefont = str_replace('+',' ',preg_replace('/:.*/i','',$parabola_menugooglefont));
$parabola_fontfamily = cryout_fontname_cleanup($parabola_fontfamily);
$parabola_fonttitle = cryout_fontname_cleanup($parabola_fonttitle);
$parabola_fontside = cryout_fontname_cleanup($parabola_fontside);
$parabola_headingsfont = cryout_fontname_cleanup($parabola_headingsfont);
$parabola_sitetitlefont = cryout_fontname_cleanup($parabola_sitetitlefont);
$parabola_menufont = cryout_fontname_cleanup($parabola_menufont);
?>
body { font-family: <?php echo ((!$parabola_googlefont)?$parabola_fontfamily:"\"$parabola_googlefont\""); ?>; }
#content h1.entry-title a, #content h2.entry-title a, #content h1.entry-title , #content h2.entry-title, #frontpage .nivo-caption h3, .column-header-image, .column-header-noimage, .columnmore {
		font-family: <?php echo ((!$parabola_googlefonttitle)?(($parabola_fonttitle == 'General Font')?'inherit':$parabola_fonttitle):"\"$parabola_googlefonttitle\""); ?>; }
.widget-title, .widget-title a { line-height: normal;
		font-family: <?php echo ((!$parabola_googlefontside)?(($parabola_fontside == 'General Font')?'inherit':$parabola_fontside):"\"$parabola_googlefontside\"");  ?>;  }
.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6, h3#comments-title, #comments #reply-title  {
		font-family: <?php echo ((!$parabola_headingsgooglefont)?(($parabola_headingsfont == 'General Font')?'inherit':$parabola_headingsfont):"\"$parabola_headingsgooglefont\"");  ?>; }
#site-title span a {
		font-family: <?php echo ((!$parabola_sitetitlegooglefont)?(($parabola_sitetitlefont == 'General Font')?'inherit':$parabola_sitetitlefont):"\"$parabola_sitetitlegooglefont\"");  ?>; }
#access ul li a, #access ul li a span {
		font-family: <?php echo ((!$parabola_menugooglefont)?(($parabola_menufont == 'General Font')?'inherit':$parabola_menufont):"\"$parabola_menugooglefont\"");  ?>; }

<?php
////////// COLORS //////////
?>
body { color: <?php echo $parabola_contentcolortxt; ?>; background-color: <?php echo $parabola_backcolormain; ?> }
a { color: <?php echo $parabola_linkcolortext; ?>; }
a:hover { color: <?php echo $parabola_linkcolorhover; ?>; }
#header-full { background-color: <?php echo $parabola_backcolorheader; ?>; }
#site-title span a { color:<?php echo $parabola_titlecolor; ?>; }
#site-description { color:<?php echo $parabola_descriptioncolor; ?>; <?php if(cryout_hex2rgb($parabola_descriptionbg)): ?>background-color: rgba(<?php echo cryout_hex2rgb($parabola_descriptionbg); ?>,0.3); padding-left: 6px; <?php endif; ?>}

.socials a { background-color: <?php echo $parabola_accentcolora; ?>; }
.socials-hover { background-color: <?php echo $parabola_menucolorbgdefault; ?>; }
.breadcrumbs:before { border-color: transparent transparent transparent <?php echo $parabola_contentcolortxt; ?>; }

#access a, #nav-toggle span { color: <?php echo $parabola_menucolortxtdefault; ?>; background-color: <?php echo $parabola_menucolorbgdefault; ?>; }
<?php if ($parabola_mobile=="Enable") { ?>
@media (max-width: 650px) { #access, #nav-toggle { background-color: <?php echo $parabola_menucolorbgdefault; ?>; } }
<?php }; ?>
#access a:hover { color: <?php echo $parabola_menucolortxthover; ?>; background-color: <?php echo $parabola_menucolorbghover; ?>; }
#access > .menu > ul > li > a:after, #access > .menu > ul ul:after { border-color: transparent transparent <?php echo $parabola_accentcolora; ?> transparent; }
#access ul li:hover a:after { border-bottom-color:<?php echo $parabola_accentcolorb; ?>;}
#access ul li.current_page_item > a, #access ul li.current-menu-item > a,
/*#access ul li.current_page_parent > a, #access ul li.current-menu-parent > a,*/
#access ul li.current_page_ancestor > a, #access ul li.current-menu-ancestor > a {
      color: <?php echo $parabola_menucolortxtactive; ?>; background-color: <?php echo $parabola_menucolorbgactive; ?>; }
#access ul li.current_page_item > a:hover, #access ul li.current-menu-item > a:hover,
/*#access ul li.current_page_parent > a:hover, #access ul li.current-menu-parent > a:hover,*/
#access ul li.current_page_ancestor > a:hover, #access ul li.current-menu-ancestor > a:hover {
      color: <?php echo $parabola_menucolortxthover; ?>; }
<?php if (cryout_hex2rgb($parabola_menucolorshadow)): ?>#access ul ul { box-shadow: 3px 3px 0 rgba(<?php echo cryout_hex2rgb($parabola_menucolorshadow); ?>,0.3); }<?php endif; ?>

<?php if ($parabola_topmenucolorbg): ?>
.topmenu ul li { background-color:  <?php echo $parabola_topmenucolorbg; ?>; }
<?php endif; ?>
.topmenu ul li a { color: <?php echo $parabola_topmenucolortxt; ?>; }
.topmenu ul li a:before { border-color: <?php echo $parabola_accentcolora; ?> transparent transparent transparent; }
.topmenu ul li a:hover:before{border-top-color:<?php echo $parabola_accentcolorb; ?>}
.topmenu ul li a:hover { color: <?php echo $parabola_topmenucolortxthover; ?>; background-color: <?php echo $parabola_topmenucolorbghover; ?>; }

div.post, div.page, div.hentry, div.product, div[class^='post-'],
#comments, .comments, .column-text, .column-image, #srights, #slefts, #front-text3, #front-text4, .page-title, .page-header, article.post, article.page, article.hentry,
.contentsearch, #author-info, #nav-below, .yoyo > li, .woocommerce #mainwoo
     { background-color: <?php echo $parabola_contentcolorbg; ?>; }
div.post, div.page, div.hentry, .sidey .widget-container, #comments, .commentlist .comment-body, article.post, article.page, article.hentry, #nav-below, .page-header, .yoyo > li,
#front-text3, #front-text4
     { border-color: <?php echo $parabola_accentcolorc; ?>; }
#author-info, #entry-author-info { border-color: <?php echo $parabola_accentcolore; ?>; }
#entry-author-info #author-avatar, #author-info #author-avatar { border-color: <?php echo $parabola_accentcolorc; ?>; }
<?php if (cryout_hex2rgb($parabola_accentcolorb)): ?>article.sticky:hover { border-color: rgba(<?php echo cryout_hex2rgb($parabola_accentcolorb); ?>,.6); } <?php endif; ?>

.sidey .widget-container { color: <?php echo $parabola_sidetxt; ?>; background-color: <?php echo $parabola_sidebg; ?>; }
.sidey .widget-title { color: <?php echo $parabola_sidetitletxt; ?>; background-color: <?php echo $parabola_sidetitlebg; ?>; }
.sidey .widget-title:after { border-color: transparent transparent <?php echo $parabola_accentcolora; ?>; }

.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6 {
     color: <?php echo $parabola_contentcolortxtheadings; ?>; }
.entry-title, .entry-title a, .page-title { color: <?php echo $parabola_contentcolortxttitle; ?>; }
.entry-title a:hover { color: <?php echo $parabola_contentcolortxttitlehover; ?>; }
#content h3.entry-format { color: <?php echo $parabola_menucolortxtdefault; ?>; background-color: <?php echo $parabola_menucolorbgdefault; ?>; border-color: <?php echo $parabola_menucolorbgdefault; ?>; }
#content h3.entry-format { color: <?php echo $parabola_menucolortxtdefault; ?>; background-color: <?php echo $parabola_menucolorbgdefault; ?>; border-color: <?php echo $parabola_menucolorbgdefault; ?>; }
.comments-link { background-color: <?php echo $parabola_accentcolore; ?>; }
.comments-link:before { border-color: <?php echo $parabola_accentcolore; ?> transparent transparent; }

#footer { color: <?php echo $parabola_footercolortxt; ?>; background-color: <?php echo $parabola_backcolorfooterw; ?>; }
#footer2 { color: <?php echo $parabola_footercolortxt; ?>; background-color: <?php echo $parabola_backcolorfooter; ?>;
		   <?php if(cryout_hex2rgb($parabola_contentcolorbg)): ?>border-color:rgba(<?php echo cryout_hex2rgb($parabola_contentcolorbg);?>,.1); <?php endif; ?>  }
#footer a { color: <?php echo $parabola_linkcolorwooter; ?>; }
#footer a:hover { color: <?php echo $parabola_linkcolorwooterhover; ?>; }
#footer2 a { color: <?php echo $parabola_linkcolorfooter; ?>; }
#footer2 a:hover { color: <?php echo $parabola_linkcolorfooterhover; ?>; }
#footer .widget-container { color: <?php echo $parabola_widgettxt; ?>; background-color: <?php echo $parabola_widgetbg; ?>; }
#footer .widget-title { color: <?php echo $parabola_widgettitletxt; ?>; background-color: <?php echo $parabola_widgettitlebg; ?>; }
#footer .widget-title:after { border-color: transparent transparent <?php echo $parabola_accentcolora; ?>; }

.footermenu ul li a:after { border-color: transparent transparent <?php echo $parabola_accentcolora; ?> transparent; }

a.continue-reading-link { color:<?php echo $parabola_menucolortxtdefault; ?> !important; background:<?php echo $parabola_menucolorbgdefault; ?>; border-color:<?php echo $parabola_accentcolorc; ?>; }
a.continue-reading-link:hover { background-color:<?php echo $parabola_accentcolora; ?>; }

.button, #respond .form-submit input#submit {
	background-color: <?php echo $parabola_accentcolore; ?>;
	border-color: <?php echo $parabola_accentcolorc; ?>; }
.button:hover, #respond .form-submit input#submit:hover {
	border-color: <?php echo $parabola_accentcolord; ?>; }
.entry-content tr th, .entry-content thead th {
	color: <?php echo $parabola_contentcolorbg; ?>;
	background-color: <?php echo $parabola_contentcolortxtheadings; ?>; }
.entry-content fieldset, #content tr td { border-color: <?php echo $parabola_accentcolord; ?>; }
hr { background-color: <?php echo $parabola_accentcolord; ?>; }
input[type="text"], input[type="password"], input[type="email"], textarea, select,
input[type="color"],input[type="date"],input[type="datetime"],input[type="datetime-local"],input[type="month"],input[type="number"],input[type="range"],
input[type="search"],input[type="tel"],input[type="time"],input[type="url"],input[type="week"] {
	background-color: <?php echo $parabola_accentcolore; ?>;
    border-color: <?php echo $parabola_accentcolord; ?> <?php echo $parabola_accentcolorc; ?> <?php echo $parabola_accentcolorc; ?> <?php echo $parabola_accentcolord; ?>;
	color: <?php echo $parabola_contentcolortxt; ?>; }
input[type="submit"], input[type="reset"] {
	color: <?php echo $parabola_contentcolortxt; ?>;
	background-color: <?php echo $parabola_accentcolore; ?>;
	border-color: <?php echo $parabola_accentcolorc; ?>;}
input[type="text"]:hover, input[type="password"]:hover, input[type="email"]:hover, textarea:hover,
input[type="color"]:hover, input[type="date"]:hover, input[type="datetime"]:hover, input[type="datetime-local"]:hover, input[type="month"]:hover, input[type="number"]:hover, input[type="range"]:hover,
input[type="search"]:hover, input[type="tel"]:hover, input[type="time"]:hover, input[type="url"]:hover, input[type="week"]:hover {
	<?php if(cryout_hex2rgb($parabola_accentcolore)): ?>background-color: rgb(<?php echo cryout_hex2rgb($parabola_accentcolore); ?>); background-color: rgba(<?php echo cryout_hex2rgb($parabola_accentcolore); ?>,0.4); <?php endif; ?> }
.entry-content code {
	border-color: <?php echo $parabola_accentcolord; ?>;
	background-color: <?php echo $parabola_accentcolore; ?>; }
.entry-content pre { background-color: <?php echo $parabola_accentcolore; ?>; }
.entry-content blockquote {
	border-color: <?php echo $parabola_accentcolora; ?>;
	background-color: <?php echo $parabola_accentcolore; ?>; }
abbr, acronym { border-color: <?php echo $parabola_contentcolortxt; ?>; }
span.edit-link {
	color: <?php echo $parabola_contentcolortxt; ?>;
	background-color: <?php echo $parabola_accentcolorc; ?>;
	border-color: <?php echo $parabola_accentcolore; ?>;  }
.meta-border .entry-meta span, .meta-border .entry-utility span.bl_posted {
	border-color: <?php echo $parabola_accentcolore; ?>; }
.meta-accented .entry-meta span, .meta-accented .entry-utility span.bl_posted {
	background-color: <?php echo $parabola_accentcolorc; ?>;
	border-color: <?php echo $parabola_accentcolore; ?>; }
.comment-meta a { color: <?php echo $parabola_contentcolortxtlight; ?>; }
.comment-author { background-color: <?php echo $parabola_accentcolore; ?>; }
.comment-details:after { border-color: transparent transparent transparent <?php echo $parabola_accentcolore; ?>; }
.comment:hover > div > .comment-author { background-color: <?php echo $parabola_accentcolorc; ?>; }
.comment:hover > div > .comment-author .comment-details:after { border-color: transparent transparent transparent <?php echo $parabola_accentcolorc; ?>; }
#respond .form-allowed-tags { color: <?php echo $parabola_contentcolortxtlight; ?>; }
.comment .reply a { border-color: <?php echo $parabola_accentcolorc; ?>;   color: <?php echo $parabola_contentcolortxt; ?>;}
.comment .reply a:hover { background-color: <?php echo $parabola_accentcolorc; ?> }

.nav-next a:hover, .nav-previous a:hover {background: <?php echo $parabola_accentcolore; ?>}
.pagination .current { font-weight: bold; }
.pagination span, .pagination a { background-color: <?php echo $parabola_contentcolorbg; ?>; }
.pagination a:hover { background-color: <?php echo $parabola_menucolorbgdefault; ?>;color:<?php echo $parabola_linkcolortext; ?>}
.page-link em {background-color: <?php echo $parabola_menucolorbgdefault; ?>; color:<?php echo $parabola_menucolortxtdefault; ?>; color:<?php echo $parabola_accentcolora; ?>;}
.page-link a em {color:<?php echo $parabola_accentcolorb; ?>;}
.page-link a:hover em {background-color:<?php echo $parabola_menucolorbghover; ?>;}

#searchform input[type="text"] {color:<?php echo $parabola_contentcolortxtlight; ?>;}
#toTop {border-color:transparent transparent <?php echo $parabola_backcolorfooter; ?>;}
#toTop:after {border-color:transparent transparent <?php echo $parabola_contentcolorbg; ?>;}
#toTop:hover:after {border-bottom-color:<?php echo $parabola_accentcolora; ?>;}

.caption-accented .wp-caption {<?php if(cryout_hex2rgb($parabola_accentcolora)):?> background-color:rgb(<?php echo cryout_hex2rgb($parabola_accentcolora);?>); background-color:rgba(<?php echo cryout_hex2rgb($parabola_accentcolora);?>,0.8); <?php endif; ?>
	color:<?php echo $parabola_contentcolorbg;?>}

.meta-themed .entry-meta span { color: <?php echo $parabola_contentcolortxtlight; ?>; background-color: <?php echo $parabola_accentcolore; ?>; border-color: <?php echo $parabola_accentcolorc; ?>; }
.meta-themed .entry-meta span:hover { background-color: <?php echo $parabola_accentcolorc; ?>; }
.meta-themed .entry-meta span a:hover { color: <?php echo $parabola_contentcolortxt; ?>; }

<?php
////////// LAYOUT //////////
?>
#content p, #content ul, #content ol, #content, #front-text3, #front-text4 { text-align:<?php echo $parabola_textalign;  ?> ; }
html {
                                font-size:<?php echo $parabola_fontsize ?>;
								word-spacing:<?php echo $parabola_wordspace ?>; letter-spacing:<?php echo $parabola_letterspace ?>; }
html { line-height:<?php echo $parabola_lineheight ?>; }
<?php if ($parabola_uppercasetext==0): ?> #site-title a, #site-description, #access a, .topmenu ul li a, .footermenu a, .entry-meta span, .entry-utility span, #content h3.entry-format,
span.edit-link, h3#comments-title, h3#reply-title, .comment-author cite, .comment .reply a, .widget-title, #site-info a, .nivo-caption h2, a.continue-reading-link,
.column-image h3, #front-columns h3.column-header-noimage, .tinynav, .page-link  { text-transform: none; }<?php endif; ?>
<?php if ($parabola_hcenter): ?> #bg_image {display:block;margin:0 auto;} <?php endif; ?>
#content h1.entry-title, #content h2.entry-title { font-size:<?php echo $parabola_headfontsize; ?> ;}
.widget-title, .widget-title a { font-size:<?php echo $parabola_sidefontsize; ?> ;}
<?php $font_root = 36;
for($i=1;$i<=6;$i++):
	echo "#content .entry-content h$i { font-size: ";
	echo round(($font_root-(4*$i))*(preg_replace("/[^\d]/","",$parabola_headingsfontsize)/100),0);
	echo "px;} ";
endfor; ?>
#site-title span a { font-size:<?php echo $parabola_sitetitlesize; ?> ;}
#access ul li a { font-size:<?php echo $parabola_menufontsize; ?> ;}
#access ul ul ul a {font-size:<?php echo (absint($parabola_menufontsize)-2); ?>px;}
<?php /*if ($parabola_postseparator == "Show") { ?> article.post, article.page { padding-bottom: 10px; border-bottom: 3px solid #EEE; } <?php }*/ ?>
<?php if ($parabola_contentlist == "Hide") { ?> #content ul li { background-image: none; padding-left: 0; } <?php } ?>
<?php if ($parabola_comtext == "Hide") { ?> #respond .form-allowed-tags { display:none;} <?php } ?>
<?php switch ($parabola_comclosed) {
	case "Hide in posts": ?> .nocomments { display:none;} <?php break;
	case "Hide in pages": ?> .nocomments2 {display:none;} <?php break;
	case "Hide everywhere": ?> .nocomments, .nocomments2 {display:none;} <?php break;
};//switch ?>
<?php if ($parabola_comoff == "Hide") { ?> .comments-link span { display:none;} <?php } ?>
<?php if ($parabola_tables == "Enable") { ?>
		#content table {border:none;} #content tr {background:none;} #content table {border:none;}
		#content tr th, #content thead th {background:none;} #content tr td {border:none;}
<?php } ?>
<?php if ($parabola_headingsindent == "Enable") { ?>
		#content h1, #content h2, #content h3, #content h4, #content h5, #content h6 { margin-left:20px;}
<?php } ?>
#header-container > div:first-child { margin:<?php echo $parabola_headermargintop; ?>px 0 0 <?php echo $parabola_headermarginleft; ?>px;}
<?php if ($parabola_pagetitle == "Hide") { ?> .page h1.entry-title, .home .page h2.entry-title { display:none; } <?php } ?>
<?php if ($parabola_categtitle == "Hide") { ?> header.page-header, .archive h1.page-title { display:none; }  <?php } ?>
<?php if ($parabola_postcomlink == "Hide") { ?>article.post .comments-link { display:none; } <?php } ?>
<?php if ($parabola_postauthor == "Hide") { ?>.entry-meta .author { display:none; } <?php } ?>
<?php if ($parabola_postcateg == "Hide") { ?>.entry-meta span.bl_categ { display:none; } <?php } ?>
<?php if ($parabola_posttag == "Hide") { ?> .entry-meta .footer-tags, .entry-utility .footer-tags { display:none; } <?php } ?>
<?php if ($parabola_postbook == "Hide") { ?> .entry-utility span.bl_bookmark { display:none; } <?php } ?>
#content p, #content ul, #content ol, #content dd, #content pre, #content hr { margin-bottom: <?php echo $parabola_paragraphspace;?>; }
<?php if ($parabola_parindent != "0px") { ?> #content p { text-indent:<?php echo $parabola_parindent;?>;} <?php } ?>
<?php if ($parabola_postmetas == "Hide") { ?> #content div.entry-meta { display:none; } <?php } ?>
<?php if ($parabola_triangles == 0) { ?>
	.topmenu ul li a:before, #access > .menu > ul > li > a:after, #access > .menu > ul ul:after,
	.widget-title:after, .footermenu ul li a:after	{ display: none; } <?php }; ?>
<?php /*switch ($parabola_menualign):
		case "center": ?> #access > .menu > ul { display: table; margin: 0 auto; text-align: center; } #access > .menu > ul > li { display: inline-block; float: initial; }
			              #access > .menu > ul > * { text-align: initial; } <?php break;
		case "right": ?> #access > .menu > ul { float: right; } <?php break;
		case "rightmulti": ?> #access ul li { float: right; } <?php break;
		default: break;
	  endswitch;*/ ?>
#header-widget-area { width: <?php echo $parabola_headerwidgetwidth; ?>; }
<?php
////////// HEADER IMAGE //////////
?>
#branding { height:<?php echo HEADER_IMAGE_HEIGHT; ?>px; }
<?php if ($parabola_hratio) { ?> @media (max-width: 1920px) {#branding, #bg_image { height:auto; max-width:100%; min-height:inherit !important; } }	<?php }?>
<?php
//////// RESPONSIVENESS ////////
?>
@media (max-width: 800px) {
	#content h1.entry-title, #content h2.entry-title { font-size:<?php echo absint($parabola_headfontsize)*0.95; ?>px ; }
	#site-title span a { font-size:<?php echo absint($parabola_sitetitlesize)*.9; ?>px;}

}
@media (max-width: 650px) {
	#content h1.entry-title, #content h2.entry-title {font-size:<?php echo absint($parabola_headfontsize)*0.9; ?>px ;}
	#site-title span a { font-size:<?php echo absint($parabola_sitetitlesize)*.8; ?>px;}
}

@media (max-width: 480px) {
	#site-title span a { font-size:<?php echo absint($parabola_sitetitlesize)*.6; ?>px;}
}
</style>
<?php
	$parabola_custom_styling = ob_get_contents();
	ob_end_clean();
	return $parabola_custom_styling;
} // parabola_custom_styles()

// Parabola function for inserting the Custom CSS into the header
function parabola_customcss() {
	$parabolas= parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = esc_attr($value) ; }
	if ($parabola_customcss != "") {
		echo '<style type="text/css">'.htmlspecialchars_decode($parabola_customcss, ENT_QUOTES).'</style>';
	}
} // parabola_customcss()

// Parabola function for inseting the Custom JS into the header
function parabola_customjs() {
	$parabolas= parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = esc_attr($value) ; }
	echo '<script type="text/javascript">';
	echo 'var cryout_global_content_width = '.$parabola_sidewidth.';';
	if ($parabola_customjs != "") {
		echo PHP_EOL.htmlspecialchars_decode($parabola_customjs, ENT_QUOTES);
	}
	echo '</script>';
} // parabola_customjs()

////////// FIN //////////
?>
