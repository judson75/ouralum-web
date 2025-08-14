var $j = jQuery.noConflict();

$j(function(){	
	$j(document).ready(function(){
		callCalendar();
		$j('body').delegate('.ajax-navigation', 'click', function(e){
			e.preventDefault();
			//console.log($j(this).attr('href'));
			callCalendar($j(this).attr('href'));
		});
		
		$j(document).on('click', '[data-toggle="modal"]', function(event) {
			var modal_id = $j(this).data('target').replace('#', '');
			//console.log("DID: " + modal_id);
			modal(modal_id);
		});
		
		$j(document).on('click', '[data-dismiss="modal"]', function(event) {
			close_modal();
		});
		
		$j(document).on('click', '.sipopup', function() {
			//console.log("HERE");
			var id = $j(this).data('id');
			var group_id = $j(this).data('group');
			var email = $j(this).data('email');
			var phone = $j(this).data('phone');
			$j('#invite-member-frm input[name="alumn_id"]').val(id);
			$j('#invite-member-frm input[name="group_id"]').val(group_id);
			$j('#invite-member-frm input[name="member_email"]').val(email);
			$j('#invite-member-frm input[name="member_phone"]').val(phone);
			//$('#send_invite_modal').modal('show');
			//modal('send_invite_modal');
		});
		
		$j(document).on('click', '.sendInviteBtn', function() {
			var alum_id = $j('input[name="alumn_id"]').val();
			var sender_id = $j('input[name="sender_id"]').val();
			var email = $j('input[name="member_email"]').val();
			var phone = $j('input[name="member_phone"]').val();
			var group_id = $j('input[name="group_id"]').val();
			$j('.sendInviteBtn').prepend('<i class="fa fa-spinner fa-lg fa-pulse fa-fw"></i>');
			$j('.sendInviteBtn').prop("disabled",true);
			//Ajax
			$j.post(ajaxurl, { action: 'send_alum_invite', alum_id: alum_id, sender_id: sender_id, email: email, phone: phone, group_id: group_id}, function(data) {
			    console.log("DAtA: " + data);
				var obj = $j.parseJSON(data);
				var response = obj.resp;
				if(response == 'success') {
					$j('.sendInviteBtn').find('svg').remove(); 
					$j('.sendInviteBtn').prop("disabled",false);
					close_modal();
					$j('body').prepend('<div class="page-alert success">Your invitation has been sent!</div>');
					setTimeout(function() {
						$j(".page-alert").fadeOut(300, function() { 
					   		$j(this).remove(); 
					   	});
					}, 5000);
					//scroll to top
					//$('#invite-member-frm').before('<div class="alert alert-success" style="font-weight: normal; font-size: 14px; padding: 6px 10px; max-width: 380px !important; margin: 0 auto 15px 0; ">Your invitation has been sent</div>');
					//$('#invite-member-frm').hide();
					//$("html, body").animate({ scrollTop: 0 }, "fast");
				}
				else {
	
				}
			});
		});
		
		if($j('#members-block').length) {
			display_members_table();
		}
		
	});
	
	$j('#ap-photos').magnificPopup({
		delegate: 'a',
		type:'image',
		gallery: {
	    	enabled: true
	  	},
	});
	
	$j('#ap-slider').magnificPopup({
		delegate: 'a',
		type:'image',
		gallery: {
	    	enabled: true
	  	},
	});
	
	$j('#group-photos').magnificPopup({
		delegate: 'a',
		type:'image',
		gallery: {
	    	enabled: true
	  	},
	});

	
	$j(document).on('click', '.loginBtn', function() {
		var err_count = 0;
		var notice_count = 0;
		$j('.helper').remove();
		$j('.alert').remove();
		$j('div').removeClass('hasError');
		var user_login = $j('input[name="user_login"]').val();
		var user_password = $j('input[name="user_password"]').val();
		if(user_login  == '') {
			$j('input[name="user_login"]').parent('div').addClass('hasError');
			$j('input[name="user_login"]').after('<div class="helper error">Email is required</div>');
			err_count++;
		}
		if(user_password == '') {
			$j('input[name="user_password"]').parent('div').addClass('hasError');
			$j('input[name="user_password"]').after('<div class="helper error">Password is required</div>');
			err_count++;
		}

		if(err_count > 0) {
			return false;
		}
		$j('#login-form').submit();
	});
	
	$j(document).on('click', '.deletePhoto', function() {
		$j('.alert').remove();
		var id = $j(this).attr('data-id');
		if (confirm("Are you sure you want to delete this photo?") == true) {
			$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
			$j.post(ajaxurl, { action: 'delete_group_photo', id: id }, function(data) {
				console.log("DATA: " + data);
				var obj = $j.parseJSON(data);
				var response = obj.resp;
				if(response == 'success') {
					$j('#group-photos-content h3').after('<div class="alert alert-success">Your photo has been deleted</div>')
					$j('#group-photo-' + id).remove();
				}
				else {

				}
				$j('.page-overlay').remove();
				return false;
			});
		}
	});
	
	$j(document).on('click', '.editPhoto', function() {
		//$j('#submit_photo_modal').
		var photo_id = $j(this).data('id');
		$j('#editPhotoFrm input[name="photo_id"]').val(photo_id);
		//get data
		$j.post(ajaxurl, { action: 'get_photo_data', id: photo_id }, function(data) {
			console.log("DATA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				//$j('#editPhotoFrm select[name="pledge_class"] select').val(obj.photo.pledge_class);
				$j('#editPhotoFrm select[name="pledge_class"] option[value=' + obj.photo.pledge_class + ']').attr('selected','selected');
				$j('#editPhotoFrm input[name="caption"]').val(obj.photo.caption);
			}
			else {

			}
			return false;
		});
	});

	$j(document).on('click', '.deleteAd', function() {
		$j('.alert').remove();
		var id = $j(this).attr('data-id');
		if (confirm("Are you sure you want to delete this ad?") == true) {
			$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
			$j.post(ajaxurl, { action: 'delete_group_ad', id: id }, function(data) {
				console.log("DATA: " + data);
				var obj = $j.parseJSON(data);
				var response = obj.resp;
				if(response == 'success') {
					$j('#group-ad-' + id).before('<div class="alert alert-success">Your ad has been deleted</div>')
					$j('#group-ad-' + id).remove();
				}
				else {

				}
				$j('.page-overlay').remove();
				return false;
			});
		}
	});
	
	$j(document).on('click', '.sendJoinRequestBtn', function() {
		var error_count = 0;
		var group_id = $j('#joinGrpFrm input[name="group_id"]').val();
		var college = $j('#joinGrpFrm input[name="college"]').val();
		var frat = $j('#joinGrpFrm input[name="frat"]').val();
		var name = $j('#joinGrpFrm input[name="name"]').val();
		var email = $j('#joinGrpFrm input[name="email"]').val();
		var comments = $j('#joinGrpFrm textarea[name="comments"]').val();
		if(name == '' || name == null) {
			$j('#joinGrpFrm input[name="name"]').parent('div').addClass('hasError');
			$j('#joinGrpFrm input[name="name"]').after('<div class="helper error">Please enter your name</div>');
			error_count++;
		}
		if(email == '' || email == null) {
			$j('#joinGrpFrm input[name="email"]').parent('div').addClass('hasError');
			$j('#joinGrpFrm input[name="email"]').after('<div class="helper error">Please enter your email address</div>');
			error_count++;
		}
		else if(ValidateEmail(user_login) != true) {
			$j('#joinGrpFrm input[name="email"]').parent('div').addClass('hasError');
			$j('#joinGrpFrm input[name="email"]').after('<div class="helper error">Please enter a valid email address</div>');
			error_count++;
		}
		/*
		if(comments == '' || comments == null) {
			$j('#joinGrpFrm textarea[name="comments"]').parent('div').addClass('hasError');
			$j('#joinGrpFrm textarea[name="comments"]').after('<div class="helper error">Please enter a valid email address</div>');
			error_count++;
		}
		*/
		if(error_count > 0) {
			return false;
		}
		//ajax send request
		$j.post(ajaxurl, { action: 'send_group_join_request', group_id: group_id, college: college, frat: frat, name: name, email: email, comments: comments }, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
							
			}
			else {
				
			}
			
			$j('.page-overlay').remove();
			/*
			$j('.editDetailsBtn').show();
			$j('html,body').animate({
			  scrollTop: $j('#profile-data').offset().top
			}, 500);
			*/
			return false;
		});
	});
	
	
	$j(document).on('click', '.saveGroupDescBtn, .saveGroupLinkBtn, .savePromoLinkBtn', function() {
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
	});
	
	$j(document).on('click', '.sendJobListingBtn', function() {
		var error_count = 0;
		var user_id = $j('#submitJobFrm input[name="user_id"]').val();
		var group_id = $j('#submitJobFrm input[name="group_id"]').val();
		var job_title = $j('#submitJobFrm input[name="job_title"]').val();
		var city = $j('#submitJobFrm input[name="city"]').val();
		var state = $j('#submitJobFrm select[name="state"]').val();
		var job_desc = $j('#submitJobFrm textarea[name="job_desc"]').val();
		var contact_info = $j('#submitJobFrm textarea[name="contact_info"]').val();
		if(job_title === '' || job_title === null) {
			$j('#submitJobFrm input[name="job_title"]').parent('div').addClass('hasError');
			$j('#submitJobFrm input[name="job_title"]').after('<div class="helper error">Please enter job title</div>');
			error_count++;
		}
		if(job_desc == '' || job_desc == null) {
			$j('#submitJobFrm textarea[name="job_desc"]').parent('div').addClass('hasError');
			$j('#submitJobFrm textarea[name="job_desc"]').after('<div class="helper error">Please enter job description</div>');
			error_count++;
		}
		if(city == '' || city == null) {
			$j('#submitJobFrm input[name="city"]').parent('div').addClass('hasError');
			$j('#submitJobFrm input[name="city"]').after('<div class="helper error">Please enter city</div>');
			error_count++;
		}
		if(state == '' || state == null) {
			$j('#submitJobFrm select[name="state"]').parent('div').addClass('hasError');
			$j('#submitJobFrm select[name="state"]').after('<div class="helper error">Please enter state</div>');
			error_count++;
		}
		if(contact_info == '' || contact_info == null) {
			$j('#submitJobFrm textarea[name="contact_info"]').parent('div').addClass('hasError');
			$j('#submitJobFrm textarea[name="contact_info"]').after('<div class="helper error">Please enter contact information</div>');
			error_count++;
		}

		if(error_count > 0) {
			return false;
		}
		
		//ajax send request
		$j.post(ajaxurl, { action: 'send_group_job_request', group_id: group_id, user_id: user_id, job_title: job_title, job_desc: job_desc, city: city, state: state, contact_info: contact_info}, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				//$j('#submit_job_modal').modal('hide');
				modal('#submit_job_modal');
				$j('#group-jobs h3').after(obj.mssg);
			
			}
			else {
				
			}
			
			/*
			$j('.editDetailsBtn').show();
			$j('html,body').animate({
			  scrollTop: $j('#profile-data').offset().top
			}, 500);
			*/
			return false;
		});
	});
	
	$j(document).on('click', '.showPostFrmButton', function() {
		$j('#blogFormContainer').toggle();
	});
	
	$j(document).on('click', '.selectPhotoBtn', function() {
		$j('input[name="photo"]').click();
	});
	
	$j(document).on('click', '.showCompFrmButton', function() {
		$j('#compFormContainer').toggle();
	});
	
	$j(document).on('click', '.selectPhotoBtn2', function() {
		$j('input[name="composite_img"]').click();
	});
	
	$j(document).on('click', '.sendAlumContact', function() {
		var error_count = 0;
		var group_id = $j('#alum-contact-frm input[name="group_id"]').val();
		var sender_name = $j('#alum-contact-frm input[name="sender_name"]').val();
		var sender_email = $j('#alum-contact-frm input[name="sender_email"]').val();
		var sender_comments = $j('#alum-contact-frm textarea[name="sender_comments"]').val();

		if(sender_name == '' || sender_name == null) {
			$j('#alum-contact-frm input[name="sender_name"]').parent('div').addClass('hasError');
			$j('#alum-contact-frm input[name="sender_name"]').after('<div class="helper error">Please enter your name</div>');
			error_count++;
		}
		if(sender_email == '' || sender_email == null) {
			$j('#alum-contact-frm input[name="sender_email"]').parent('div').addClass('hasError');
			$j('#alum-contact-frm input[name="sender_email"]').after('<div class="helper error">Please enter your email</div>');
			error_count++;
		}
		else if(ValidateEmail(sender_email) != true) {
			$j('#alum-contact-frm input[name="sender_email"]').parent('div').addClass('hasError');
			$j('#alum-contact-frm input[name="sender_email"]').after('<div class="helper error">Please enter a valid email address</div>');
			err_count++;
		}
		if(sender_comments == '' || sender_comments == null) {
			$j('#alum-contact-frm textarea[name="sender_comments"]').parent('div').addClass('hasError');
			$j('#alum-contact-frm textarea[name="sender_comments"]').after('<div class="helper error">Please enter your message</div>');
			error_count++;
		}

		if(error_count > 0) {
			return false;
		}
		
		//ajax send request
		$j.post(ajaxurl, { action: 'send_alum_contact', group_id: group_id, sender_name: sender_name, sender_email: sender_email, sender_comments: sender_comments}, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('#alum-contact-frm').html('<div class="alert alert-success">' + obj.mssg + '</div>');
			
			}
			else {
				
			}
			
			/*
			$j('.editDetailsBtn').show();
			$j('html,body').animate({
			  scrollTop: $j('#profile-data').offset().top
			}, 500);
			*/
			return false;
		});

	});
	
	
	function readURLPhoto(input) {
		//console.log(input);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
			
            reader.onload = function (e) {
         		//console.log(e.target.result);
                $j('#photoPreview')
                    .attr('src', e.target.result)
                    .width(200)
                    .height(200)
                    .css('display', 'block');
          
            };
	
            reader.readAsDataURL(input.files[0]);
        }
    }
    
	$j('input[name="photo"]').change(function() {
		readURLPhoto(this);
        var filename = $j('input[name="photo"]').val();
        //console.log(filename);
        //$j('.selectPhotoBtn').before('<div id="photoFilename">' + filename + '</div>');
    });
	
	
	function readURLPhoto2(input) {
		//console.log(input);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
			
            reader.onload = function (e) {
         		//console.log(e.target.result);
                $j('#photoPreview2')
                    .attr('src', e.target.result)
                    .width(200)
                    .height(200)
                    .css('display', 'block');
          
            };
	
            reader.readAsDataURL(input.files[0]);
        }
    }
    
	$j('input[name="composite_img"]').change(function() {
		readURLPhoto2(this);
        var filename = $j('input[name="composite_img"]').val();
        //console.log(filename);
        //$j('.selectPhotoBtn').before('<div id="photoFilename">' + filename + '</div>');
    });
    
    $j(document).on('click', '.editPhoto', function() {
		var year = $j(this).data('year');
		console.log(year);
		$j('select[name="photo_year"] option[value="' + year + '"]').prop('selected', true);
    });
    
    
	$j(document).on('click', '.sendPhotoRequestBtn', function() {
		//check required
		var errCnt = 0;
		if($j('#submitPhotoFrm select[name="photo_year"]').val() == '') {
			$j('#submitPhotoFrm select[name="photo_year"]').after('<div class="helper error">Photo year is required</div>');
			errCnt++;
		}
		if($j('#submitPhotoFrm input[name="photo"]').val() == '') {
			$j('#submitPhotoFrm input[name="photo"]').after('<div class="helper error">Please upload a photo</div>');
			errCnt++;
		}
		if(errCnt > 0) {
			
			return false;
		}
		$j('.page-overlay').remove();
		$j('.modal').hide();
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
		//modal('#submit_photo_modal');
		$j('#submitPhotoFrm').submit();
	});
	
	$j(document).on('click', '.editPhotoRequestBtn', function() {
		$j('.page-overlay').remove();
		$j('.modal').hide();
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
		//modal('#edit_photo_modal'); 		
		$j('#editPhotoFrm').submit();
	});

	
	
	$j(document).on('click', '.sendBlogReplyBtn', function() {
		if($j('#single-post-reply').hasClass('open')) {
			$j('#single-post-reply').removeClass('open');
			$j('#single-post-reply').hide();
			$j('.sendBlogReplyBtn').show();
		}
		else {
			$j('#single-post-reply').addClass('open');
			$j('#single-post-reply').show();
			$j('.sendBlogReplyBtn').hide();
		}
	});
	
	$j(document).on('click', '.closeCommentReplyBtn', function() {
		$j('#single-post-reply').removeClass('open');
		$j('#single-post-reply').hide();
		$j('.sendBlogReplyBtn').show();
	});
	
	$j(document).on('click', '.cancelBlogRequestBtn', function() {
		$j('#blogFormContainer').toggle();
	});
	
	$j(document).on('click', '.sendBlogRequestBtn', function() {
		var err_count = 0;
		var group_id = $j('#submitBlogFrm input[name="group_id"]').val();
		var user_id = $j('#submitBlogFrm input[name="user_id"]').val();
		var post_title = $j('#submitBlogFrm input[name="post_title"]').val();
		var post_content = $j('#submitBlogFrm textarea[name="post_content"]').val();
		if(post_title  == '') {
			$j('#submitBlogFrm input[name="post_title"]').parent('div').addClass('hasError');
			$j('#submitBlogFrm input[name="post_title"]').after('<div class="helper error">Title is required</div>');
			err_count++;
		}
		if(post_content  == '') {
			$j('#submitBlogFrm textarea[name="post_content"]').parent('div').addClass('hasError');
			$j('#submitBlogFrm textarea[name="post_content"]').after('<div class="helper error">Comments are required</div>');
			err_count++;
		}
		if(err_count > 0) {
			return false;
		}
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
		$j.post(ajaxurl, { action: 'send_group_post', group_id: group_id, user_id: user_id, post_title: post_title, post_content: post_content }, function(data) {
		    console.log("DATA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('#blogFormContainer').hide();
				$j('#group-blog').prepend('<div class="alert alert-success">' + obj.mssg + '</div>');
			}
			else {
				
			}
			
			$j('.page-overlay').remove();
			/*
			$j('.editDetailsBtn').show();
			$j('html,body').animate({
			  scrollTop: $j('#profile-data').offset().top
			}, 500);
			*/
			return false;
		});
	});
	
	
	$j(document).on('click', '.sendCompositeRequestBtn', function() {
		var err_count = 0;
		var group_id = $j('#submitCompFrm input[name="group_id"]').val();
		var post_title = $j('#submitCompFrm input[name="post_title"]').val();
		var init_year = $j('#submitCompFrm input[name="init_year"]').val();
		var img = $j('#submitCompFrm input[name="composite_img"]').val();
		//console.log("ID: " + group_id);
		//var post_content = $j('#submitBlogFrm textarea[name="post_content"]').val();
		if(post_title  == '') {
			$j('#submitCompFrm input[name="post_title"]').parent('div').addClass('hasError');
			$j('#submitCompFrm input[name="post_title"]').after('<div class="helper error">Title is required</div>');
			err_count++;
		}
		if(init_year  == '') {
			$j('#submitCompFrm input[name="init_year"]').parent('div').addClass('hasError');
			$j('#submitCompFrm input[name="init_year"]').after('<div class="helper error">Initiation year is required</div>');
			err_count++;
		}
		if(img  == '') {
			$j('#submitCompFrm #photoPreview2').parent('div').addClass('hasError');
			$j('#submitCompFrm #photoPreview2').after('<div class="helper error">Image are required</div>');
			err_count++;
		}
		if(err_count > 0) {
			return false;
		}
		$j('#submitCompFrm').submit();
		/*
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
		$j.post(ajaxurl, { action: 'send_composite', group_id: group_id, post_title: post_title, init_year: init_year, image: img }, function(data) {
		    console.log("DATA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('#blogFormContainer').hide();
				$j('#group-blog').prepend('<div class="alert alert-success">' + obj.mssg + '</div>');
			}
			else {
				
			}
			
			$j('.page-overlay').remove();

			return false;
			
		});*/
	});
	
	$j(document).on('click', '.cancelCompositeRequestBtn', function() {
		$j('#compFormContainer').toggle();
	});
	
	$j(document).on('click', '.add-event-cell', function() {
		var date = $j(this).attr('data-date');
		//$j('#submit_event_modal').modal('show');
		modal('#submit_event_modal');
		$j('#submitEventFrm input[name="event_date"]').val(date);
	});
	
	$j(document).on('click', '.sendEventListingBtn', function() {
		var err_count = 0;
		var group_id = $j('#submitEventFrm input[name="group_id"]').val();
		var user_id = $j('#submitEventFrm input[name="user_id"]').val();
		var event_title = $j('#submitEventFrm input[name="event_title"]').val();
		var event_date = $j('#submitEventFrm input[name="event_date"]').val();
		var event_time = $j('#submitEventFrm select[name="event_time"]').val();
		if(event_title  == '') {
			$j('#submitEventFrm input[name="event_title"]').parent('div').addClass('hasError');
			$j('#submitEventFrm input[name="event_title"]').after('<div class="helper error">Title is required</div>');
			err_count++;
		}
		if(event_date  == '') {
			$j('#submitEventFrm input[name="event_date"]').parent('div').addClass('hasError');
			$j('#submitEventFrm input[name="event_date"]').after('<div class="helper error">Event date is required</div>');
			err_count++;
		}
		if(event_time  == '') {
			$j('#submitEventFrm select[name="event_time"]').parent('div').addClass('hasError');
			$j('#submitEventFrm select[name="event_time"]').after('<div class="helper error">Event time is required</div>');
			err_count++;
		}

		if(err_count > 0) {
			return false;
		}
		//console.log("Ys");
		//$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
		$j.post(ajaxurl, { action: 'send_calendar_event', group_id: group_id, user_id: user_id, event_title: event_title, event_date: event_date, event_time: event_time }, function(data) {
		    console.log("DATA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				//$j('#blogFormContainer').hide();
				$j('#group-events').prepend('<div class="alert alert-success">' + obj.mssg + '</div>');
				//$j('#submit_event_modal').modal('hide');
				modal('#submit_event_modal');
			}
			else {
				
			}
		});
	});


	$j(document).on('click', '.sendCommentReplyBtn', function() {
		var err_count = 0;
		var group_id = $j('#single-post-reply input[name="group_id"]').val();
		var user_id = $j('#single-post-reply input[name="user_id"]').val();
		var post_id = $j('#single-post-reply input[name="post_id"]').val();
		var comments = $j('#single-post-reply input[name="comment_reply"]').val();
		if(comments  == '') {
			$j('#single-post-reply input[name="comment_reply"]').parent('div').addClass('hasError');
			$j('#single-post-reply input[name="comment_reply"]').after('<div class="helper error">Please enter reply</div>');
			err_count++;
		}
		if(err_count > 0) {
			return false;
		}
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Submitting...</span></div></div>');
		$j.post(ajaxurl, { action: 'send_group_post_reply', group_id: group_id, user_id: user_id, post_id: post_id, reply: comments }, function(data) {
		    console.log("DATA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('#single-post-reply').hide();
				$j('#single-post-reply').before('<div class="alert alert-success">' + obj.mssg + '</div>');
			}
			else {
				$j('#single-post-reply').prepend('<div class="alert alert-danger">' + data + '</div>');
			}
			
			$j('.page-overlay').remove();
			/*
			$j('.editDetailsBtn').show();
			$j('html,body').animate({
			  scrollTop: $j('#profile-data').offset().top
			}, 500);
			*/
			return false;
		});
	});
	
	
	$j(document).on('click', '.sendMessageBtn', function() {
		var error_count = 0;
		var user_id = $j('#submitMessageFrm input[name="user_id"]').val();
		var group_id = $j('#submitMessageFrm input[name="group_id"]').val();
		var pledge_class = $j('#submitMessageFrm input[name="pledge_class"]').val();
		var message = $j('#submitMessageFrm textarea[name="message"]').val();
		if(message == '' || message == null) {
			$j('#submitMessageFrm textarea[name="message"]').parent('div').addClass('hasError');
			$j('#submitMessageFrm textarea[name="message"]').after('<div class="helper error">Please enter message</div>');
			error_count++;
		}
		if(error_count > 0) {
			return false;
		}
		
		//ajax send request
		$j.post(ajaxurl, { action: 'send_message_request', group_id: group_id, user_id: user_id, pledge_class: pledge_class, message: message}, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				//$j('#submit_message_modal').modal('hide'); 
				modal('#submit_message_modal');
				$j('#group-messages h3').after('<div class="alert alert-success">' + obj.mssg + '</div>');
				$j('#submitMessageFrm textarea[name="message"]').val('');
			}
			else {
				
			}
			return false;
		});
	});


	$j(document).on('click', '.registerBtn', function() {
		var err_count = 0;
		var notice_count = 0;
		$j('.helper').remove();
		$j('.alert').remove();
		$j('div').removeClass('hasError');
		var user_login = $j('input[name="user_login"]').val();
		var user_password = $j('input[name="user_password"]').val();
		var user_password2 = $j('input[name="user_password2"]').val();
		var first_name = $j('input[name="first_name"]').val();
		var last_name = $j('input[name="last_name"]').val();
		var middle_name = $j('input[name="middle_name"]').val();
		
		var update_email = ($j('input[name="update_email"]').val() != '') ? $j('input[name="update_email"]').val() : '';
		var update_phone = ($j('input[name="update_phone"]').val() != '') ? $j('input[name="update_phone"]').val() : '';
		
		//var pledge_class = $j('input[name="pledge_class"]').val();
		var initiation_date = $j('input[name="initiation_date"]').val();
		if(first_name  == ''  && $j('input[name="first_name"]').length) {
			$j('input[name="first_name"]').parent('div').addClass('hasError');
			$j('input[name="first_name"]').after('<div class="helper error">First name is required</div>');
			err_count++;
		}
		if(last_name  == '' && $j('input[name="last_name"]').length) {
			$j('input[name="last_name"]').parent('div').addClass('hasError');
			$j('input[name="last_name"]').after('<div class="helper error">Last name is required</div>');
			err_count++;
		}
		if(middle_name  == '' && $j('input[name="middle_name"]').length) {
			$j('input[name="middle_name"]').parent('div').addClass('hasError');
			$j('input[name="middle_name"]').after('<div class="helper error">Middle initial is required</div>');
			err_count++;
		}
		if(user_login  == '') {
			$j('input[name="user_login"]').parent('div').addClass('hasError');
			$j('input[name="user_login"]').after('<div class="helper error">Email is required</div>');
			err_count++;
		}
		else if(ValidateEmail(user_login) != true) {
			$j('input[name="user_login"]').parent('div').addClass('hasError');
			$j('input[name="user_login"]').after('<div class="helper error">Please enter a valid email address</div>');
			err_count++;
		}
		if(user_password == '') {
			$j('input[name="user_password"]').parent('div').addClass('hasError');
			$j('input[name="user_password"]').after('<div class="helper error">Password is required</div>');
			err_count++;
		}
		else if(user_password != user_password2)  {
			$j('input[name="user_password"]').parent('div').addClass('hasError');
			$j('input[name="user_password"]').after('<div class="helper error">Passwords do not match is required</div>');
			err_count++;
		}
		/*
		if(pledge_class  == '') {
			$j('input[name="pledge_class"]').parent('div').addClass('hasError');
			$j('input[name="pledge_class"]').after('<div class="helper error">Pledge class is required</div>');
			err_count++;
		}
		*/
		if(initiation_date == '' && $j('input[name="initiation_date"]').length) {
			$j('input[name="initiation_date"]').parent('div').addClass('hasError');
			$j('input[name="initiation_date"]').after('<div class="helper error">intitiation date is required</div>');
			err_count++;
		}

		if(err_count > 0) {
			return false;
		}
		
		$j('#register-form').submit();
		
	});
	
	$j(document).on('click', '#settings_password', function() {
		$j(this).attr("readonly", false);
	});
	
	$j(document).on('click', '.updateSettingsBtn', function() {
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Saving...</span></div></div>');
		
	});
	
	$j(document).on('change', 'select[name="occupation"]', function() {
		var profession = $j(this).val();
		$j.post(ajaxurl, { action: 'get_occ2', profession: profession }, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('select[name="occupation2"]').html('');
				var select_options = '';
				$j.each( obj.occ2, function( key, value ) {
				    console.log(value);
				    select_options += '<option value="' + value + '">' + value + '</option>'
				});
				if(select_options != '') {
					$j('.occ2').show();
					$j('select[name="occupation2"]').append(select_options);
				}
				else {
					$j('.occ2').hide();
				}
			}
			else {
				
			}
			$j('.page-overlay').remove();
		});
	});
	
	$j(document).on('click', '.editDetailsBtn', function() {
		location.href = '/profile/edit/';
		return false;
		//if(!$j('#profile-data-sub .form-group').hasClass('occ2')) {
		//	$j('#profile-data-sub .form-group').show();
		//}
		/*
		$j.each( $j('#profile-data-sub .form-group'), function() {
		    if(!$j(this).hasClass('occ2')) {
				$j(this).show();
			}
			else if($j(this).hasClass('occ2') && $j(this).data('display') == 'block') {
				$j(this).show();
			}
		});
		$j('#profile-data-sub .profile-email').hide(); 
		$j('#profile-data-sub .profile-phone').hide(); 
		$j('#profile-data-sub .profile-address').hide();
		$j('#profile-data-sub #profile-work').hide();
		$j('#profile-data-sub #profile-birthdate').hide();
		$j('#profile-data-sub #profile-spouse').hide();
		$j('.editDetailsBtn').hide();
		*/
	});

	$j(document).on('click', '.cancelUserDataBtn', function() {
		location.href = '/profile/';
		return false;
		/*
		$j('#profile-data-sub .form-group').hide();
		$j('#profile-data-sub .profile-email').show(); 
		$j('#profile-data-sub .profile-phone').show(); 
		$j('#profile-data-sub .profile-address').show();
		$j('#profile-data-sub #profile-work').show();
		$j('#profile-data-sub #profile-birthdate').show();
		$j('#profile-data-sub #profile-spouse').show();

		$j('.editDetailsBtn').show();
		*/
	});
	
	$j(document).on('click', '.updateUserDataBtn', function() {
		$j('.alert').remove();
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div></div>');
		var user_name = $j('input[name="user_name"]').val();
		var display_name = $j('input[name="display_name"]').val();
		var email = $j('input[name="user_email"]').val();
		var phone = $j('input[name="phone"]').val();
		var address = $j('input[name="address"]').val();
		var city = $j('input[name="city"]').val();
		var state = $j('select[name="state"]').val();
		var zipcode = $j('input[name="zipcode"]').val();
		var birthdate = $j('input[name="birthdate"]').val();
		var spouse_name = $j('input[name="spouse_name"]').val();
		var mobile_phone = $j('input[name="mobile_phone"]').val();
		var home_phone = $j('input[name="home_phone"]').val();
		var user_id = $j('input[name="user_id"]').val();
		
		var occupation = $j('select[name="occupation"]').val();
		var occupation2 = $j('select[name="occupation2"]').val();
		var occupation_description = $j('textarea[name="occupation_description"]').val();
		var employer_name = $j('input[name="employer_name"]').val();
		var employer_address = $j('input[name="employer_address"]').val();
		var work_phone = $j('input[name="work_phone"]').val();
		var group_id = '';
		if($j('input[name="group_id"]').length) {
			group_id = $j('input[name="group_id"]').val();
		}
		
		var are_you_hiring = $j('input[name="are_you_hiring"]:checked').val();
		var hiring_position = $j('textarea[name="hiring_position"]').val();
		var seeking_employment = $j('input[name="seeking_employment"]:checked').val();
		var type_employment_seeking = $j('textarea[name="type_employment_seeking"]').val();
		
		var formData = new FormData();
		formData.append('action', 'update_user_data');
		formData.append('ad', $j('#ad')[0].files[0]);
		
		formData.append('user_id', user_id);
		formData.append('email', email);
		formData.append('phone', phone);
		formData.append('city', city);
		formData.append('state', state);
		formData.append('zipcode', zipcode);
		formData.append('address', address);
		formData.append('occupation', occupation);
		formData.append('occupation2', occupation2);
		formData.append('occupation_description', occupation_description);
		formData.append('display_name', display_name);
		formData.append('username', user_name);
		formData.append('employer_name', employer_name);
		formData.append('employer_address', employer_address);
		formData.append('birthdate', birthdate);
		formData.append('spouse_name', spouse_name);
		formData.append('mobile_phone', mobile_phone);
		formData.append('home_phone', home_phone);
		formData.append('work_phone', work_phone);
		formData.append('are_you_hiring', are_you_hiring);
		formData.append('hiring_position', hiring_position);
		formData.append('seeking_employment', seeking_employment);
		formData.append('type_employment_seeking', type_employment_seeking);
		if(group_id != '') {
			formData.append('group_id', group_id);
		}
		/*
		/*{ 
		action: 'update_user_data', 
		user_id: user_id, 
		email: email, 
		phone: phone, 
		city: city, 
		state: state, 
		zipcode: zipcode, 
		address: address, 
		occupation: occupation,
		occupation2: occupation2,
		occupation_description: occupation_description, 
		display_name: display_name, 
		username: user_name,
		employer_name: employer_name,
		employer_address: employer_address,
		birthdate: birthdate,
		spouse_name: spouse_name,
		mobile_phone: mobile_phone,
		home_phone: home_phone,
		work_phone: work_phone,
		are_you_hiring: are_you_hiring,
		hiring_position: hiring_position,
		seeking_employment: seeking_employment,
		type_employment_seeking: type_employment_seeking
	}
	*/
		
		var request = $j.ajax({
			url: ajaxurl,
		  	method: "POST",
		  	data: formData,
		  	processData: false,  // tell jQuery not to process the data
       		contentType: false,
		});

		request.done(function( data ) {
		  	console.log( data );
			console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				location.href = '/profile/';
				return false;
				/*
				$j('#profile-data').prepend('<div class="alert alert-success">Your  information has been updated</div>');
				$j('#profile-data-sub .form-group').hide();
				$j('#profile-data-sub .profile-email').show(); 
				$j('#profile-data-sub .profile-phone').show(); 
				$j('#profile-data-sub .profile-address').show();
				$j('#profile-data-sub #profile-work').show();
				$j('#profile-data-sub #profile-birthdate').show();
				$j('#profile-data-sub #profile-spouse').show();

				if(occupation != '') {
					$j('.profile-occupation').html(occupation);
				}
				if(user_name != '') {
					$j('#profile-data-sub .profile-username').html(user_name);
				}
				if(display_name != '') {
					$j('.profile-displayname .displayname-display').html(display_name);
				}
				if(phone != '') {
					$j('#profile-data-sub .profile-phone').html(phone);
				}
				if(address != '') {
					$j('#profile-data-sub .profile-street').html(address);
				}
				var addy = ''
				if(city != '') {
					addy += city;
				}
				if(state != '') {
					addy += ', ' + state;
				}
				if(zipcode != '') {
					addy += ' ' + zipcode;
				}
				$j('.profile-address').html(addy);
				
				var work = ''
				
				if(employer_name != '') {
					work += '<h4>Currently Works At</h4>';
					work += employer_name + '<br>';
				}
				
				if(occupation != '') {
					work += occupation + '<br>';
				}
				
				if(employer_address != '') {
					work += employer_address + '<br>';
				}

				if(work_phone != '') {
					work += work_phone + '<br>';
				}

				$j('#profile-work').html(work);
				
				if(birthdate != '') {
					$j('#profile-data-sub #profile-birthdate').html('Bithdate: ' + birthdate);
				}
				
				if(spouse_name != '') {
					$j('#profile-data-sub #profile-spouse').html('<h4>Married to ' + spouse_name + '</h4>');
				}
				*/
		
			}
			else {
				$j('#profile-data').prepend('<div class="alert alert-danger">There was an error. Your information has not been updated</div>');
				$j('.page-overlay').remove();
				$j('.editDetailsBtn').show();
				$j('html,body').animate({
				  scrollTop: $j('#edit-profile-wrapper').offset().top
				}, 500);
				return false;
			
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});

/*
		$j.post(ajaxurl, formData, function(data) {
		    
			
			
		});
*/
		
	});

	$j(document).on('click', '.lostPassBtn', function() {
		var email = $j('input[name="user_login"]').val();
		var err_count = 0;
		//console.log(")");
		$j('.helper').remove();
		$j('.alert').remove();
		$j('div').removeClass('hasError');
		if(email  == '') {
			$j('input[name="user_login"]').parent('div').addClass('hasError');
			$j('input[name="user_login"]').after('<div class="helper error">Email is required</div>');
			err_count++;
		}
		else if(ValidateEmail(email) != true) {
			$j('input[name="user_login"]').parent('div').addClass('hasError');
			$j('input[name="user_login"]').after('<div class="helper error">Please enter a valid email address</div>');
			err_count++;
		}
		if(err_count > 0) {
			return false;
		}
		//console.log("P");
		$j('#lost-password-form').submit();
		/*
		$j.post(ajaxurl, { action: 'send_reset_link', email: email }, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				
			}
		});		
		*/
	});
	
	$j(document).on('click', '.resetPassBtn', function() {
		var password = $j('input[name="password"]').val();
		var err_count = 0;
		//console.log(")");
		$j('.helper').remove();
		$j('.alert').remove();
		$j('div').removeClass('hasError');
		if(password  == '') {
			$j('input[name="password"]').parent('div').addClass('hasError');
			$j('input[name="password"]').after('<div class="helper error">Please enter a password</div>');
			err_count++;
		}
		if(err_count > 0) {
			return false;
		}
		//console.log("P");
		$j('#new-password-form').submit();
	});
	
	
	$j(document).on('click', '.editUsernameBtn', function() {
		$j('.nicename-display').hide();
		$j('.edit-nicename').show(); 
		$j('.updateUserNameBtn').show(); 
		$j('.cancelUserNameBtn').show(); 
	});

	$j(document).on('click', '.cancelUserNameBtn', function() {
		$j('.nicename-display').show();
		$j('.edit-nicename').hide(); 
		$j('.updateUserNameBtn').hide(); 
		$j('.cancelUserNameBtn').hide(); 
	});
	
	$j(document).on('click', '.updateUserNameBtn', function() {
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div></div>');
		var user_name = $j('input[name="user_nicename"]').val();
		var user_id = $j('input[name="user_id"]').val();
		$j.post(ajaxurl, { action: 'update_user_name', user_name: user_name, user_id: user_id }, function(data) {
		    console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('.nicename-display').show();
				$j('.edit-nicename').hide(); 
				$j('.updateUserNameBtn').hide(); 
				$j('.cancelUserNameBtn').hide();
				$j('.nicename-display').html(user_name);
			}
			else {
				
			}
			$j('.page-overlay').remove();
		});
	});
	
	
	$j(document).on('click', '.editAvatarBtn', function() {
		$j('.profile-avatar-wrapper').hide(); 
		$j('.profile-image-upload').show();  
	});
	
	$j(document).on('click', '.pr123', function() {
		$j('.alert').remove();
		var claim_profile = $j('input[name="claim_profile"]:checked').val();
		//console.log(claim_profile);
		if(claim_profile == '' || claim_profile == undefined) {
			$j('.pr123').before('<div class="alert alert-danger">Please choose whether to claim or not</div>');
			return false;
		}
		
		$j('#found-profile-form').submit();
	});
	
	
	$j(document).on('click', '.cancelAvatarBtn ', function(event) {
		event.stopPropagation();
		$j('.profile-image-upload').find('img').remove();
		$j('.profile-avatar-wrapper').show(); 
		$j('.profile-image-upload').hide();
		$j('.profile-image-upload').html('<i class="fa fa-camera" aria-hidden="true"></i>CLICK HERE TO UPLOAD IMAGE<button type="button" class="cancelAvatarUpdateBtn">Cancel</button>');
		$j('.cancelAvatarBtn').remove();
		$j('.saveAvatarBtn').remove();
	});
	
	$j(document).on('click', '.cancelAvatarUpdateBtn ', function(event) {
		event.stopPropagation();
		$j('.profile-avatar-wrapper').show(); 
		$j('.profile-image-upload').hide();  
	});
	
	$j(document).on('click', '.saveAvatarBtn ', function(event) {
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div></div>');
		event.stopPropagation();
		//page overlay and spinner?
		$j('#avatar-update-frm').submit();
	});

	$j(document).on('click', '.profile-image-upload', function() {
		$j('#user_avatar').click();  
	});
	
	$j(document).on('change', '#user_avatar', function(e) {
		readURL(this);
	});
	
	
	
	function display_members_table() {
		//console.log("HERE");
		
		var init_year = $j('#init_year_filter').val();
		var profession = $j('#occupation_filter').val();
		var group_id = $j('input[name="group_id"]').val();
		var user_id = $j('input[name="user_id"]').val();
		var search = $j('.dataTables_filter input').val();
		if(search === '') {
			$j('#members-block').prepend('<div class="div-overlay"><div class="div-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><h4>Loading Alum Members</h4><span class="sr-only">Loading...</span></div></div>');
		}
		//console.log("SEARCH: " + search);
		//return false;
		//console.log(init_year);
		//Ajax
		$j.post(ajaxurl, { action: 'get_members_table', group_id: group_id, user_id, user_id, init_year: init_year, profession: profession, search: search}, function(data) {
		    //console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('#members-block').html(obj.html);
				var table = $j('#members_table').DataTable({
					"pageLength": 50,
					"aaSorting": [[ 5, "desc" ]],
					"search": {
				       "search": search
				    }
				//	"processing": true,
       		 	//	"serverSide": true,
				});
				//console.log(search );
				if(search !== '' && search !== undefined) { 
					$j('.dataTables_filter input').focus();
				//	table.api().search(search).draw();
				}
				
				$j('.dataTables_filter input', table.table().container())
			    .off('.DT')
			    .on('keyup.DT cut.DT paste.DT input.DT search.DT', function (e) {
			       //console.log(this.value);
			       if(this.value.length >= 3 || e.keyCode == 13) {
			       	  //Change init year????
			          $j('#init_year_filter').val('all');
			          //Delay a micro second
			          //Disable the search for a minute
			          $j('.dataTables_filter input').attr('disabled', true);
			          display_members_table();
			       }
			
			       // Ensure we clear the search if they backspace far enough
			       if(this.value === "") {
			          table.search("").draw();
			       }
			    });
				
				
 //$j('#members-block').prepend('<div class="div-overlay"><div class="div-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><h4>Loading Alum Members</h4><span class="sr-only">Loading...</span></div></div>');
				$j('#occupation_filter').on('change', function(){
				   table.search(this.value).draw();   
				});
				
				$j('#init_year_filter').on('change', function(){
					display_members_table();
				});
			}
			else {
	
			}
		});
	}
	
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var img = document.createElement("img");
            reader.onload = function (e) {
            	img.src = e.target.result;
                //$j('#blah').attr('src', e.target.result);
				$j('.profile-image-upload').html(img)
				$j('.profile-image-upload').append('<button class="saveAvatarBtn"><i class="far fa-save" aria-hidden="true"></i></button> <button class="cancelAvatarBtn"><i class="fa fa-times" aria-hidden="true"></i></button>');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
	
	function ValidateEmail(email) {  
 		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {  
    		return (true)  
  		}  
    	return (false)  
	}
	
	function callCalendar(url) {
		var group_id = $j('#cal_group').val();
		var month = (getQueryVariable('mo', url) != false) ? getQueryVariable('mo', url) : null;
		var year = (getQueryVariable('yr', url) != false) ? getQueryVariable('yr', url) : null;
		//console.log("M: " + month + " - Y: " + year);
		$j.post(ajaxurl, { action: 'show_calendar', month: month, year: year, group_id: group_id }, function(data) {
		    //console.log("DAtA: " + data);
			var obj = $j.parseJSON(data);
			var response = obj.resp;
			if(response == 'success') {
				$j('.alum-calendar').html(obj.html);
			}
			else {
				
			}
		});
	}
	
	function getQueryVariable(variable, url) {
		if(url != null && url != undefined) {
			url = url.replace('?', '');
			var query = url;
		}	
		else {
       		var query = window.location.search.substring(1);
       	}
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
	}
	
	function modal(modal_id) {
		var modal_id = modal_id.replace('#', '');
		var wh = $j(window).height();
		var ww = $j(window).width();
		if($j('#' + modal_id).hasClass('open')) {
			$j('#' + modal_id).removeClass('open');
			$j('#' + modal_id).animate({
				'top': '-100%',
				'opacity': 0,
			});
			$j('.page-overlay').remove();
		}
		else {
			if(ww <= 478) {
				var mtop = '5%';
			}
			else {
				var mtop = '10%';
			}
			$j('#' + modal_id).addClass('open');
			$j('#' + modal_id).animate({
				'top': mtop,
				'opacity': 1,
			});
			$j('body').prepend('<div class="page-overlay"></div>');
		}
	}
	
	function close_modal() {
		$j('.modal').removeClass('open');
		$j('.modal').animate({
			'top': '-100%',
			'opacity': 0,
		});
		$j('.page-overlay').remove();
	}
});