(function($) {
	var site_path = '/dev/';
 	$(document).ready(function() {
		/*
		var defaults = {
			containerID: 'toTop', // fading element id
			containerHoverID: 'toTopHover', // fading element hover id
			scrollSpeed: 1200,
			easingType: 'linear' 
		};
		*/
	
		
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top -80},1000);
		});
		
		if($('#ap-slider').length) {
			$("#ap-slider").slick({
				slidesToShow: 3,
				slidesToScroll: 3
			  });
		}
		
		
		
		/*
		if($( "#members_table" ).length) {
			 var table = $('#members_table').DataTable({
				"pageLength": 50,
				"aaSorting": [[ 5, "desc" ]],
			});
			
			$('#occupation_filter').on('change', function(){
			   table.search(this.value).draw();   
			});
			

			$('#init_year_filter').on('change', function(){
			   	//Set get
			 	var url = window.location.href;
			 	url = removeURLParameter(url, 'show_class'); 
			 	url += '?show_class=' + $(this).val() + '#members-section';
			 	console.log();
			   	location.href = url;   
			});}
		
		*/
		
		$('input[name="pledge_class"], input[name="initiation_date"]').keyup( function() {
			var val = $(this).val();
			$(this).val($(this).val().replace(/[^\d]/,''));
		});
		
		if($("#init_date").length) {
			$("#init_date" ).datepicker({
				changeMonth: true,
      			changeYear: true,
				yearRange: "-100:+0"
			});	
		}
		
		if($("#event_date").length) {
			$("#event_date.datepicker").datepicker({
				changeMonth: true,
      			changeYear: true,
				yearRange: "-100:+2"
			});	
		}
				
		var type = 'top-classes';
		var year = 'all';
		var group_id = $('input[name="group_id"]').val();
		if($('#member_chart').length) {
			$.post(ajaxurl, { action: 'get_alum_percents', type: type, year: year, group_id: group_id}, function(data) {
			    //console.log("DAtA: " + data);
				var obj = $.parseJSON(data);
				var response = obj.resp;
				if(response == 'success') {
					$('#top-p').html(obj.list_html);
					$('body').append(obj.script);
					$('#member_chart_inner').show();
					$('#member_chart').find('.div-overlay').remove();
				}	
				else {
		
				}
			});
		}	
		
		$(document).on('click', '.tp-selector', function() {
			if($(this).hasClass('open')) {
				$(this).find('.tp-selections').hide();
				$(this).removeClass('open');
				$(this).find('svg').addClass('fa-caret-down');
				$(this).find('svg').removeClass('fa-caret-up');

			}
			else {
				$(this).find('.tp-selections').show();
				$(this).addClass('open');
				$(this).find('svg').removeClass('fa-caret-down');
				$(this).find('svg').addClass('fa-caret-up');
			}
		});
		
		$(document).on('click', '#tp-type li', function() {
			//var val = $(this).data('val');
			$('#tp-type li').removeClass('active');
			$(this).addClass('active');
			var type = $(this).data('val');
			var year = $('#tp-year li.active').data('val');
			var group_id = $('input[name="group_id"]').val();
			$('#sl123 h5 span').html($(this).html());
			$('#alum_chart').remove();
			$('#doughnutChart').html('');
			//console.log("TYPE: " + type + " - YEAR: " + year);
			//add overlay ...
			
			
			//do the ajax to get new values
			$.post(ajaxurl, { action: 'get_alum_percents', type: type, year: year, group_id: group_id}, function(data) {
			    console.log("DAtA: " + data);
				var obj = $.parseJSON(data);
				var response = obj.resp;
				if(response == 'success') {
					$('#top-p').html(obj.list_html);
					$('body').append(obj.script);
					$('#member_chart_inner').show();
					$('#member_chart').find('.div-overlay').remove();
				}
				else {
	
				}
			});
		});
		
		$(document).on('click', '#tp-year li', function() {
			$('#tp-year li').removeClass('active');
			$(this).addClass('active');
			var type = $('#tp-type li.active').data('val');
			var year = $(this).data('val');
			var group_id = $('input[name="group_id"]').val();
			//$('#sr123 h5 span').html($(this).html());
			$('#alum_chart').remove();
			$('#doughnutChart').html('');
			//console.log("TYPE: " + type + " - YEAR: " + year)
			//do the ajax to get new values
			$.post(ajaxurl, { action: 'get_alum_percents', type: type, year: year, group_id: group_id}, function(data) {
			    console.log("DAtA: " + data);
				var obj = $.parseJSON(data);
				var response = obj.resp;
				if(response == 'success') {
					$('#top-p').html(obj.list_html);
					$('body').append(obj.script);
					$('#member_chart').show();
				}
				else {
	
				}
			});
		});
		
		
		
		
		/*
		if($("#birthdate").length) {
			$("#birthdate").datepicker({
				changeMonth: true,
      			changeYear: true,
				yearRange: "-100:+0"
			});	
		}
		*/

	});

	
	$(document).on('click', '#mobile-nav', function() {
		if($('#top-nav ul').hasClass('open')) {
			$('#top-nav ul').removeClass('open');
			$('#mobile-nav i').removeClass('fa-times');
			$('#mobile-nav i').addClass('fa-bars');
			$('#top-nav ul').animate({
				'right' : '-335px',
			});
		}
		else{
			$('#top-nav ul').addClass('open');
			$('#mobile-nav i').removeClass('fa-bars');
			$('#mobile-nav i').addClass('fa-times');
			$('#top-nav ul').animate({
				'right' : '-5%',
			});
		}
	});
	
	$(document).on('click', '.showSearchBtn', function() {
		if($('#top-search').hasClass('show')) {
			$('#top-search').removeClass('show');
			$('#top-search').animate({
				'height': '0px',
				'opacity': 0,
				'top': '-95px'
			})
		}
		else {
			$('#top-search').addClass('show');
			$('#top-search').animate({
				'height': '66px',
				'opacity': 1,
				'top': '35px'
			})
		}
	});
	
	$(document).on('click', '.sendClaimProfile', function() {
		var error_count = 0;
		//Check required
		$('.alert').remove();
		$('.helper').remove();
		$('.sendClaimProfile').find('i').remove();
		$('.sendClaimProfile').prepend('<i class="fa fa-spinner fa-lg fa-pulse fa-fw"></i> ');
		
		var last_name = $('input[name="last_name"]').val();
		var middle_name = $('input[name="middle_name"]').val();
		var first_name = $('input[name="first_name"]').val();
		var initiation_date = $('input[name="initiation_date"]').val();
		if(last_name == '') {
			$('input[name="last_name"]').after('<div class="helper error">Please enter your last name</div>');
			error_count++;
		}
		if(first_name == '') {
			$('input[name="first_name"]').after('<div class="helper error">Please enter your first name</div>');
			error_count++;
		}
		//if(middle_name == '') {
		//	$('input[name="middle_name"]').after('<div class="helper error">Please enter your middle name</div>');
		//	error_count++;
		//}
		if(initiation_date == '') {
			$('input[name="initiation_date"]').after('<div class="helper error">Please enter your initiation date</div>');
			error_count++;
		}
		if(error_count > 0) {
			$('.sendClaimProfile').find('i').remove();
			$('.sendClaimProfile').find('svg').remove();
			return false;
		}
		
		//console.log(ajaxurl);
		//return false;
		
		//Ajax
		$.post(ajaxurl, { action: 'claim_profile', last_name: last_name, initiation_date: initiation_date, first_name: first_name, middle_name: middle_name}, function(data) {
		    console.log("DAtA: " + data);
			var obj = $.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				//$('.sendClaimProfile').before('<div class="alert alert-success" style="font-weight: normal; font-size: 14px; padding: 6px 10px; width: 380px !important; margin: 0 auto; ">Profile Claimed, please enter email address</div>');
				//Show form with email address and password
				//var html = '';
				//html = 
				//forward to page
				$('#claim-form').html('<div class="alert alert-success" style="font-weight: normal; font-size: 14px; padding: 6px 10px; max-width: 380px !important; margin: 0 auto; ">Your profile was found, you are being forwarded to the registration page.</div>');
				window.location = "profile-found";
			}
			else {
				$('.sendClaimProfile').before('<div class="alert alert-danger" style="font-weight: normal; font-size: 14px; padding: 6px 10px; max-width: 380px !important; margin: 0 auto 15px 0; ">' + obj.mssg + '</div>');
			}
 			$('.sendClaimProfile').find('i').remove();
 			$('.sendClaimProfile').find('svg').remove();
		});		
	});
	
	function initTable() {
		$('#members_table').DataTable({
			"pageLength": 50
		});

	}
	
	function ValidateEmail(email)    {  
 		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {  
    		return (true)  
  		}  
    	return (false)  
	}
	
	function removeURLParameter(url, parameter) {
	    //prefer to use l.search if you have a location/link object
	    var urlparts= url.split('?');   
	    if (urlparts.length>=2) {
	
	        var prefix= encodeURIComponent(parameter)+'=';
	        var pars= urlparts[1].split(/[&;]/g);
	
	        //reverse iteration as may be destructive
	        for (var i= pars.length; i-- > 0;) {    
	            //idiom for string.startsWith
	            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
	                pars.splice(i, 1);
	            }
	        }
	
	        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
	        return url;
	    } else {
	        return url;
	    }
	}

	/*
  	doughnutWidget.options = {
		container: $('#member_chart'),
		width: 100,
		height: 100,
		class: 'myClass',
		cutout: 75
  	};

  	doughnutWidget.render(data());



	function data() {
    	var data = {
			Active_Members: {
				val: $('#member_percent').html(),
				color: '#57B4F2',
				click: function(e) {
					console.log('hi');
				}
    		}
  		};

  	return data;
	}
	*/
	
	if($("#member_chart").length) {
	//	var members = $('#member_count').html() },
	//	var non_members = $('#non_member_count').html()
	//	$('.donut-chart.chart1 .chart-center').append('<div id="ttt"></div>');
	
	
	
	}
	
	
	$(function(){
		

		
	});
	
	/*
	var options = {
		animationEnabled: true,
		title: {
			text: "Claimed Profiles"
		},
		data: [{
			type: "doughnut",
			innerRadius: "70%",
			showInLegend: true,
			legendText: "{label}",
			indexLabel: "{label}: #percent%",
			dataPoints: [
				{ label: "Claimed", y: $('#member_count').html() },
				{ label: "Unclaimed", y: $('#non_member_count').html() }
			]
		}]
	};
	
	$("#chartContainer").CanvasJSChart(options);
	*/
	
	$.fn.drawDoughnutChart = function(data, options) {
    var $this = this,
      W = $this.width(),
      H = $this.height(),
      centerX = W/2,
      centerY = H/2,
      cos = Math.cos,
      sin = Math.sin,
      PI = Math.PI,
      settings = $.extend({
        segmentShowStroke : true,
        segmentStrokeColor : "#0C1013",
        segmentStrokeWidth : 1,
        baseColor: "rgba(0,0,0,0.5)",
        baseOffset: 4,
        edgeOffset : 10,//offset from edge of $this
        percentageInnerCutout : 75,
        animation : true,
        animationSteps : 90,
        animationEasing : "easeInOutExpo",
        animateRotate : true,
        tipOffsetX: -8,
        tipOffsetY: -45,
        tipClass: "doughnutTip",
        summaryClass: "doughnutSummary",
        summaryTitle: "Alum Members:",
        summaryTitleClass: "doughnutSummaryTitle",
        summaryNumberClass: "doughnutSummaryNumber",
        beforeDraw: function() {  },
        afterDrawed : function() {  },
        onPathEnter : function(e,data) {  },
        onPathLeave : function(e,data) {  }
      }, options),
      animationOptions = {
        linear : function (t) {
          return t;
        },
        easeInOutExpo: function (t) {
          var v = t<.5 ? 8*t*t*t*t : 1-8*(--t)*t*t*t;
          return (v>1) ? 1 : v;
        }
      },
      requestAnimFrame = function() {
        return window.requestAnimationFrame ||
          window.webkitRequestAnimationFrame ||
          window.mozRequestAnimationFrame ||
          window.oRequestAnimationFrame ||
          window.msRequestAnimationFrame ||
          function(callback) {
            window.setTimeout(callback, 1000 / 60);
          };
      }();

    settings.beforeDraw.call($this);

    var $svg = $('<svg width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>').appendTo($this),
        $paths = [],
        easingFunction = animationOptions[settings.animationEasing],
        doughnutRadius = Min([H / 2,W / 2]) - settings.edgeOffset,
        cutoutRadius = doughnutRadius * (settings.percentageInnerCutout / 100),
        segmentTotal = 0;
	var partsTotal = 0;
    //Draw base doughnut
    var baseDoughnutRadius = doughnutRadius + settings.baseOffset,
        baseCutoutRadius = cutoutRadius - settings.baseOffset;
    $(document.createElementNS('http://www.w3.org/2000/svg', 'path'))
      .attr({
        "d": getHollowCirclePath(baseDoughnutRadius, baseCutoutRadius),
        "fill": settings.baseColor
      })
      .appendTo($svg);

    //Set up pie segments wrapper
    var $pathGroup = $(document.createElementNS('http://www.w3.org/2000/svg', 'g'));
    $pathGroup.attr({opacity: 0}).appendTo($svg);

    //Set up tooltip
    var $tip = $('<div class="' + settings.tipClass + '" />').appendTo('body').hide(),
        tipW = $tip.width(),
        tipH = $tip.height();

    //Set up center text area
    var summarySize = (cutoutRadius - (doughnutRadius - cutoutRadius)) * 2,
        $summary = $('<div class="' + settings.summaryClass + '" />')
                   .appendTo($this)
                   .css({ 
                     width: summarySize + "px",
                     height: summarySize + "px",
                     "margin-left": -(summarySize / 2) + "px",
                     "margin-top": -(summarySize / 2) + "px"
                   });
    var $summaryTitle = $('<p class="' + settings.summaryTitleClass + '">' + settings.summaryTitle + '</p>').appendTo($summary);
    var $summaryNumber = $('<p class="' + settings.summaryNumberClass + '"></p>').appendTo($summary).css({opacity: 0});

    for (var i = 0, len = data.length; i < len; i++) {
    	//console.log(data[i].title);
    	if(data[i].title == 'Members') {
    		var segmentMemberTotal = data[i].value;
    	}
      	partsTotal += data[i].value;
		//segmentTotal = (parseInt(segmentMemberTotal) / parseInt(partsTotal)) * 100 ;
      	segmentTotal += data[i].value;
      $paths[i] = $(document.createElementNS('http://www.w3.org/2000/svg', 'path'))
        .attr({
          "stroke-width": settings.segmentStrokeWidth,
          "stroke": settings.segmentStrokeColor,
          "fill": data[i].color,
          "data-order": i
        })
        .appendTo($pathGroup)
        .on("mouseenter", pathMouseEnter)
        .on("mouseleave", pathMouseLeave)
        .on("mousemove", pathMouseMove);
    }
	
	segmentPercent = (parseInt(segmentMemberTotal) / parseInt(partsTotal)) * 100 
	
    //Animation start
    animationLoop(drawPieSegments);

    //Functions
    function getHollowCirclePath(doughnutRadius, cutoutRadius) {
        //Calculate values for the path.
        //We needn't calculate startRadius, segmentAngle and endRadius, because base doughnut doesn't animate.
        var startRadius = -1.570,// -Math.PI/2
            segmentAngle = 6.2831,// 1 * ((99.9999/100) * (PI*2)),
            endRadius = 4.7131,// startRadius + segmentAngle
            startX = centerX + cos(startRadius) * doughnutRadius,
            startY = centerY + sin(startRadius) * doughnutRadius,
            endX2 = centerX + cos(startRadius) * cutoutRadius,
            endY2 = centerY + sin(startRadius) * cutoutRadius,
            endX = centerX + cos(endRadius) * doughnutRadius,
            endY = centerY + sin(endRadius) * doughnutRadius,
            startX2 = centerX + cos(endRadius) * cutoutRadius,
            startY2 = centerY + sin(endRadius) * cutoutRadius;
        var cmd = [
          'M', startX, startY,
          'A', doughnutRadius, doughnutRadius, 0, 1, 1, endX, endY,//Draw outer circle
          'Z',//Close path
          'M', startX2, startY2,//Move pointer
          'A', cutoutRadius, cutoutRadius, 0, 1, 0, endX2, endY2,//Draw inner circle
          'Z'
        ];
        cmd = cmd.join(' ');
        return cmd;
    };
    function pathMouseEnter(e) {
      var order = $(this).data().order;
      $tip.text(data[order].title + ": " + data[order].value)
          .fadeIn(200);
      settings.onPathEnter.apply($(this),[e,data]);
    }
    function pathMouseLeave(e) {
      $tip.hide();
      settings.onPathLeave.apply($(this),[e,data]);
    }
    function pathMouseMove(e) {
      $tip.css({
        top: e.pageY + settings.tipOffsetY,
        left: e.pageX - $tip.width() / 2 + settings.tipOffsetX
      });
    }
    function drawPieSegments (animationDecimal) {
      var startRadius = -PI / 2,//-90 degree
          rotateAnimation = 1;
      if (settings.animation && settings.animateRotate) rotateAnimation = animationDecimal;//count up between0~1

      drawDoughnutText(animationDecimal, segmentTotal, segmentPercent);

      $pathGroup.attr("opacity", animationDecimal);

      //If data have only one value, we draw hollow circle(#1).
      if (data.length === 1 && (4.7122 < (rotateAnimation * ((data[0].value / segmentTotal) * (PI * 2)) + startRadius))) {
        $paths[0].attr("d", getHollowCirclePath(doughnutRadius, cutoutRadius));
        return;
      }
      for (var i = 0, len = data.length; i < len; i++) {
        var segmentAngle = rotateAnimation * ((data[i].value / segmentTotal) * (PI * 2)),
            endRadius = startRadius + segmentAngle,
            largeArc = ((endRadius - startRadius) % (PI * 2)) > PI ? 1 : 0,
            startX = centerX + cos(startRadius) * doughnutRadius,
            startY = centerY + sin(startRadius) * doughnutRadius,
            endX2 = centerX + cos(startRadius) * cutoutRadius,
            endY2 = centerY + sin(startRadius) * cutoutRadius,
            endX = centerX + cos(endRadius) * doughnutRadius,
            endY = centerY + sin(endRadius) * doughnutRadius,
            startX2 = centerX + cos(endRadius) * cutoutRadius,
            startY2 = centerY + sin(endRadius) * cutoutRadius;
        var cmd = [
          'M', startX, startY,//Move pointer
          'A', doughnutRadius, doughnutRadius, 0, largeArc, 1, endX, endY,//Draw outer arc path
          'L', startX2, startY2,//Draw line path(this line connects outer and innner arc paths)
          'A', cutoutRadius, cutoutRadius, 0, largeArc, 0, endX2, endY2,//Draw inner arc path
          'Z'//Cloth path
        ];
        $paths[i].attr("d", cmd.join(' '));
        startRadius += segmentAngle;
      }
    }
    function drawDoughnutText(animationDecimal, segmentTotal, segmentPercent) {
    /*
      $summaryNumber
        .css({opacity: animationDecimal})
        .text((segmentTotal * animationDecimal).toFixed(1));
    */
    	$summaryNumber
        .css({opacity: animationDecimal})
        .text(Math.ceil((segmentPercent * animationDecimal).toFixed(0)) + '%');
    }
    function animateFrame(cnt, drawData) {
      var easeAdjustedAnimationPercent =(settings.animation)? CapValue(easingFunction(cnt), null, 0) : 1;
      drawData(easeAdjustedAnimationPercent);
    }
    function animationLoop(drawData) {
      var animFrameAmount = (settings.animation)? 1 / CapValue(settings.animationSteps, Number.MAX_VALUE, 1) : 1,
          cnt =(settings.animation)? 0 : 1;
      requestAnimFrame(function() {
          cnt += animFrameAmount;
          animateFrame(cnt, drawData);
          if (cnt <= 1) {
            requestAnimFrame(arguments.callee);
          } else {
            settings.afterDrawed.call($this);
          }
      });
    }
    function Max(arr) {
      return Math.max.apply(null, arr);
    }
    function Min(arr) {
      return Math.min.apply(null, arr);
    }
    function isNumber(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function CapValue(valueToCap, maxValue, minValue) {
      if (isNumber(maxValue) && valueToCap > maxValue) return maxValue;
      if (isNumber(minValue) && valueToCap < minValue) return minValue;
      return valueToCap;
    }
    return $this;
  };
})(jQuery);	



  

