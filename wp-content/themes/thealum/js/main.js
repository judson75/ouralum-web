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
		
		$().UItoTop({ easingType: 'easeOutQuart' });
		
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
		
		$().UItoTop({ easingType: 'easeOutQuart' });
		
		if($( "#members_table" ).length) {
			$('#members_table').DataTable({
				"pageLength": 50,
				"aaSorting": [[ 5, "desc" ]] 
			});
		}
		
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
		
		if($("#birthdate").length) {
			$("#birthdate").datepicker({
				changeMonth: true,
      			changeYear: true,
				yearRange: "-100:+0"
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
		if(middle_name == '') {
			$('input[name="middle_name"]').after('<div class="helper error">Please enter your middle name</div>');
			error_count++;
		}
		if(initiation_date == '') {
			$('input[name="initiation_date"]').after('<div class="helper error">Please enter your initiation date</div>');
			error_count++;
		}
		if(error_count > 0) {
			$('.sendClaimProfile').find('i').remove();
			return false;
		}
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
				$('.sendClaimProfile').before('<div class="alert alert-danger" style="font-weight: normal; font-size: 14px; padding: 6px 10px; max-width: 380px !important; margin: 0 auto; ">' + obj.mssg + '</div>');
			}
 			$('.sendClaimProfile').find('i').remove();
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

})(jQuery);	