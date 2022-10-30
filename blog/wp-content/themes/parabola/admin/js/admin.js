/*
 * Parabola Theme admin scripting
 * http://www.cryoutcreations.eu/
 *
 * Copyright 2013-14, Cryout Creations
 * Free to use and abuse under the GPL v3 license.
 */

function media_upload( button_class) {
	if (!window.wp || !window.wp.media || !window.wp.media.editor || !window.wp.media.editor.send || !window.wp.media.editor.send.attachment) return;
    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;
    jQuery('body').on('click',button_class, function(e) {
		uploadparent = jQuery(this).closest('div');
		var button_id ='#'+jQuery(this).attr('id');
		/* console.log(button_id); */
		var self = jQuery(button_id);
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = jQuery(button_id);
		/* var id = button.attr('id').replace('_button', ''); */
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media  ) {
					/* jQuery('.custom_media_id').val(attachment.id); */
					uploadparent.find('.slideimages').val(attachment.url);
					uploadparent.find('.imagebox').attr('src',attachment.url);
					/* jQuery('.custom_media_image').attr('src',attachment.url).css('display','block');   */
				} else {
					return _orig_send_attachment.apply( button_id, [props, attachment] );
				}
		}
		wp.media.editor.open(button);
		return false;
    });
}


/* Columns image width hint */
function column_image_width_hint(total, colcount) {
	if (colcount==0) var size = 0;
	else
		var size = parseInt((total-(colcount*7*2)-(total*2*(colcount-1)/100))/colcount-14);
	jQuery('#parabola_colimagewidth').val(size);
}

/* Change border for selected inputs */
function changeBorder (idName, className) {
	jQuery('.'+className).removeClass( 'checkedClass' );
	jQuery('.'+className).removeClass( 'borderful' );
	jQuery('#'+idName).addClass( 'borderful' );
	return 0;
}

function startfarb(a,b) {
	jQuery(b).css('display','none');
	jQuery(b).farbtastic(a).addtitle({id: a});

	jQuery(a).click(function() {
			if(jQuery(b).css('display') == 'none')	{
                                        			jQuery(b).parents('div:eq(0)').addClass('ui-accordion-content-overflow');
                                                    jQuery(b).css('display','inline-block').hide().show(150);
                                                    }
	});

	jQuery(document).mousedown( function() {
			jQuery(b).hide(300, function(){ jQuery(b).parents('div:eq(0)').removeClass('ui-accordion-content-overflow'); });
			/* todo: find a better way to remove class after the fade on IEs */
	});
}

function coloursel(el){
	var id = "#"+jQuery(el).attr('id');
	jQuery(id+"2").hide();
	var bgcolor = jQuery(id).val();
	if (bgcolor <= "#666666") { jQuery(id).css('color','#ffffff'); } else { jQuery(id).css('color','#000000'); };
	jQuery(id).css('background-color',jQuery(id).val());
}

function vercomp(ver, req) {
    var v = ver.split('.');
    var q = req.split('.');
    for (var i = 0; i < v.length; ++i) {
        if (q.length == i) { return true; } /* v is bigger */
        if (parseInt(v[i]) == parseInt(q[i])) { continue; } /* nothing to do here, move along */
        else if (parseInt(v[i]) > parseInt(q[i])) { return true; } /* v is bigger */
        else { return false; } /* q is bigger */
    }
    if (v.length != q.length) { return false; } /* q is bigger */
    return true; /* v = q */
}

/* farbtastic title extender */
(function($){
        $.fn.extend({
            addtitle: function(options) {
                var defaults = {
                    id: ''
                }
                var options = $.extend(defaults, options);
            return this.each(function() {
                    var o = options;
					var title = jQuery(o.id).attr('title');
                    if (title===undefined) { } else { jQuery(o.id+'2').children('.farbtastic').append('<span class="mytitle">'+title+'</span>'); }
            });
        }
        });
})(jQuery);


jQuery(document).ready(function() {

	var uploadparent = 0;
	media_upload( '.upload_image_button' );

	/* Show/hide slides */
	jQuery('.slidetitle').click(function() {
			jQuery(this).next().toggle("fast");
	});

	/* Hide or show slider settings */
	jQuery('#parabola_slideType').change(function() {
		jQuery('.slideDivs').hide();
		switch (jQuery('#parabola_slideType option:selected').val()) {

			case "Custom Slides" :
			jQuery('#sliderCustomSlides').show("normal");
			break;

			case "Latest Posts" :
			jQuery('#sliderLatestPosts').show("normal");
			break;

			case "Random Posts" :
			jQuery('#sliderRandomPosts').show("normal");
			break;

			case "Sticky Posts" :
			jQuery('#sliderStickyPosts').show("normal");
			break;

			case "Latest Posts from Category" :
			jQuery('#sliderLatestCateg').show("normal");
			break;

			case "Random Posts from Category" :
			jQuery('#sliderRandomCateg').show("normal");
			break;

			case "Specific Posts" :
			jQuery('#sliderSpecificPosts').show("normal");
			break;

		}/*switch*/

		sliderNr=jQuery('#parabola_slideType').val();
		/* Show category if a category type is selected */
		if (sliderNr=="Latest Posts from Category" || sliderNr=="Random Posts from Category" )
				jQuery('#slider-category').show();
		else 	jQuery('#slider-category').hide();
		/* Show number of slides if that's the case */
		if (sliderNr=="Latest Posts" || sliderNr =="Random Posts" || sliderNr =="Sticky Posts" || sliderNr=="Latest Posts from Category" || sliderNr=="Random Posts from Category" )
				jQuery('#slider-post-number').show();
		else 	jQuery('#slider-post-number').hide();

	});/*function*/

	jQuery('.slideDivs').hide();
	jQuery('#parabola_slideType').trigger('change');

	/* Hide or show column settings */
	jQuery('#parabola_columnType').change(function() {
		jQuery('.columnDivs').hide();
		switch (jQuery('#parabola_columnType option:selected').val()) {

			case "Custom Columns" :
			jQuery('#columnCustom').show("normal");
			break;

			case "Widget Columns" :
			jQuery('#columnWidgets').show("normal");
			break;

			case "Latest Posts" :
			jQuery('#columnLatestPosts').show("normal");
			break;

			case "Random Posts" :
			jQuery('#columnRandomPosts').show("normal");
			break;

			case "Sticky Posts" :
			jQuery('#columnStickyPosts').show("normal");
			break;

			case "Latest Posts from Category" :
			jQuery('#columnLatestCateg').show("normal");
			break;

			case "Random Posts from Category" :
			jQuery('#columnRandomCateg').show("normal");
			break;

			case "Specific Posts" :
			jQuery('#columnSpecificPosts').show("normal");
			break;

		}/*switch*/

		columnNr=jQuery('#parabola_columnType').val();
		/*Show category if a category type is selected*/
		if (columnNr=="Latest Posts from Category" || columnNr=="Random Posts from Category" )
				jQuery('#column-category').show();
		else 	jQuery('#column-category').hide();
		/*Show number of columns if that's the case*/
		if (columnNr=="Latest Posts" || columnNr =="Random Posts" || columnNr =="Sticky Posts" || columnNr=="Latest Posts from Category" || columnNr=="Random Posts from Category" )
				jQuery('#column-post-number').show();
		else 	jQuery('#column-post-number').hide();

	});/*function*/

	jQuery('.columnDivs').hide();
	jQuery('#parabola_columnType').trigger('change');


	/* Backwards compatibility for the accordion's current slide saving */
	var cryout_active_slide = parseInt(jQuery('#parabola_current').val());
	if (vercomp(jQuery.ui.version, '1.11.2')) {
	cryout_active_slide = parseInt((cryout_active_slide-1)/2);
	}

	/* Create accordion from existing settings table */
	jQuery('.form-table').wrap('<div>');
	jQuery(function() {
		if (jQuery( "#accordion h2" ).length > 0) {
			// wordpress 4.4+ changed headings to h2
			jQuery( "#accordion" ).accordion({
				header: 'h2',
				heightStyle: "content",
				collapsible: true,
				navigation: true,
				active: parseInt(cryout_active_slide)
			});
		} else {
			jQuery( "#accordion" ).accordion({
				header: 'h3',
				autoHeight: false, /* for jQueryUI <1.10 */
				heightStyle: "content", /* required in jQueryUI 1.10 */
				collapsible: true,
				navigation: true,
				active: parseInt(cryout_active_slide)
				});
		};
	});

	jQuery("#parabola_nrcolumns").bind('change', function() {
			column_image_width_hint(jQuery("#totalsize").html(),jQuery("#parabola_nrcolumns").val());
	});
	jQuery("#parabola_nrcolumns").trigger('change');

	jQuery('#accordion h3, #accordion h2').on('click',function() {
		var clicked = parseInt(jQuery(this).attr('id').replace( /^\D+/g, ''));
		var current = parseInt(jQuery('#parabola_current').val().replace( /^\D+/g, ''));
		if (clicked == current) jQuery('#parabola_current').val('');
		else jQuery('#parabola_current').val(clicked);
	});

});/* ready */


/* FB like button */
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/* Twitter follow button */
window.twttr = (function (d, s, id) {
  var t, js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src= "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
  return window.twttr || (t = { _e: [], ready: function (f) { t._e.push(f) } });
}(document, "script", "twitter-wjs"));

/* FIN */
