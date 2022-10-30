//jQuery start
//rotating banners
function show_snackbar_msg(display_id) {
    var x = document.getElementById(display_id)
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

$(document).ready(function(){
	$("#controls li a").click(function(){
            //Performed when a control is clicked
	    shuffle();
	    var rel = $(this).attr("rel");
	    if ( $("#" + rel).hasClass("current") ){
	        return false;
	    }
	    $("#" + rel).show();
	    $(".current").fadeOut().removeClass("current");
	    $("#" + rel).fadeIn().addClass("current");
	    $(".banneractive").removeClass("banneractive");
	    $(this).parents("li").addClass("banneractive");
	    set_new_interval(3000);
	    return false;
	});
    });
    function banner_switch(){
        //This function is called on to switch the banners out when the time limit is reached
        shuffle();
        var next =  $('.banner.current').next('.banner').length ? $('.banner.current').next('.banner') : $('#banners .banner:first');
        $(next).show();
        $(".current").fadeOut(600).removeClass("current");
        $(next).fadeIn(600).addClass("current");
        var next_link = $(".banneractive").next("li").length ? $('.banneractive').next('li') : $('#controls li:first');
        $(".banneractive").removeClass("banneractive");
        $(next_link).addClass('banneractive');
    }

    $(function() {
        //Initial timer setting
        slide = setInterval( "banner_switch()", 4000 );
    });

    function set_new_interval(interval){
        //Simply clears out the old timer interval and restarts it
        clearInterval(slide);
        slide = setInterval("banner_switch()", interval);
    }

    function shuffle(){
        //This function takes every .banner and changes the z-index to 1, hides them, then takes the ".current" banner and brings it above and shows it
        $(".banner").css("z-index", 1).hide();
        $(".current").css("z-index", 2).show();
    }


//click rotating banners


$(document).ready(function() {
	var loaded = 0;
	var timerId = 0;
	var current = null;

	var stop = function() {
		clearTimeout(timerId);
		timerId = 0;
	};

	var resume = function(func, el) {
		timerId = window.setTimeout(function() { func(el); }, 4000);
	};

	var reset = function(e) {
		loaded = 0;

		if(e)
			e.fadeOut(500);

		original.fadeIn(500, function() { loaded = 1; });
	};

	var original = $('#home_photo');

	reset();

	//
	$('#banner > a').each(function(i) {
		if(i > 0)
		{
			var banner = $(this);

			$(this).mouseover(function() {
				stop();
			}).mouseout(function() {
				resume(reset, banner);
			});
		}
	});

	$('#banner_menu > li > a').each(function(i) {
		i += 1;
		var banner = $('#banner a:eq(' + i + ')');

		$(this).mouseover(function() {
			if(!loaded)
				return;

			original.hide();

			if(current != null)
				$('#banner > a:eq(' + current +')').hide();

			current = i;

			banner.show();
			stop();
		}).mouseout(function() {
			if(!loaded)
				return;

			resume(reset, banner);
		});
	});
});

//show/hide content
$(document).ready(function()
{
	$("div.readmorecontent").hide();
	$("a#readmorelink").show();
	$("a#readmorecloselink").hide();
	$("a#readmorelink").click(function()
	{
		$("div.readmorecontent").show();
		$("a#readmorelink").hide();
		$("a#readmorecloselink").show();
		return false;
	})
	$("a#readmorecloselink").click(function()
	{
		$("div.readmorecontent").hide();
		$("a#readmorelink").show();
		$("a#readmorecloselink").hide();
		return false;
	})
	
	// toggle user menu on header		
	$("#prof-icon").click(function (e) {
		e.stopPropagation();
		if($("#prof_dropdown").css("display") == "flex"){
			$('#prof_dropdown').css("display", "none");
		}else{
			$('#prof_dropdown').css("display", "flex");
		}
		
	});
	$(document).click(function () {
		var $el = $("#prof_dropdown");
		if ($el.is(":visible")) {
			$el.fadeOut(200);
		}
	});	
});
	
//bootstrap popover
$(document).ready(function(){
	$('[data-toggle="popover"]').popover(); 
});		
	
//bootstrap tooltip	
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});	
	
//Go To URL

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

//print window
	
function printWindow(){
   bV = parseInt(navigator.appVersion)
   if (bV >= 4) window.print()
}

//open popup window

var win=null;
function NewWindow(mypage,myname,w,h,scroll,pos){
if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=yes,menubar=no,toolbar=no,resizable=no';
win=window.open(mypage,myname,settings);}

//toggle element

function toggleMe(a){
var e=document.getElementById(a);
if(e.style.display=="none"){
		e.style.display="flex"
		return
	} else {
		e.style.display="none"
	}
}

function OnChangeCheckbox (checkbox, a) {
var e=document.getElementById(a);
	if (checkbox.checked) {
		e.style.display="block"
	}
	else {
		e.style.display="none"
	}
}
function display_gif(){
				(function(){
			var gif_show = document.getElementById("gif_show")
			var content_hide = document.getElementById("content_hide"),
		show = function(){
			gif_show.style.display = "block";
		},
		hide = function(){
			gif_show.style.display = "none";
		};

	show();
  })();
}
function refreshCaptcha(){
	document.getElementById('captchaimg').src = '/captcha.php?' + Math.random();
	document.getElementById('captcha_code').value = '';
}
