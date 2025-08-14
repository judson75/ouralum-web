var $j = jQuery.noConflict();

$j(function(){
	
	$j(document).on('click', '.uploadFile', function() {
		$j('#alum_import_file').click();
	});
	
	$j(document).on('change', '#alum_import_file', function() {
		$j('#importListFrm').submit();
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div></div>');
	});
	
	$j(document).on('click', '.runImport, .runImportNoOptions', function() {
		var err_count = 0;
		var notice_count = 0;
		$j('.helper').remove();
		$j('.alert').remove();
		$j('div').removeClass('hasError');
		var college_name = $j('input[name="college_name"]').val();
		var frat_name = $j('input[name="frat_name"]').val();
		//check required
		if(college_name == '') {
			$j('input[name="college_name"]').parent('div').addClass('hasError');
			$j('input[name="college_name"]').after('<div class="helper error">College Name is required</div>');
			err_count++;
		}
		if(frat_name == '') {
			$j('input[name="frat_name"]').parent('div').addClass('hasError');
			$j('input[name="frat_name"]').after('<div class="helper error">Fraternity Name is required</div>');
			err_count++;
		}
		//check optional -- Check dropdowns that do not have a value
		if( !$j(this).hasClass('runImportNoOptions') ) {
			$j(".pfSelect").each(function( index ) {
			  	//console.log( index + ": " + $j( this ).text() + " VAL: " + $j( this ).val() );
				if($j( this ).val() == '') {
					$j( this ).addClass('hasError');
				 	notice_count++;
				}
			});	
		}
		
		if(err_count > 0) {
			$j('.runImport').before('<div class="alert alert-error">You have not completed all required fields. Please correct and resubmit</div>');
			return false;
		}
		
		if($j(this).hasClass('runImportNoOptions') ) {
			console.log("TEST");
			notice_count = 0;
		}
		
		if(notice_count > 0) {
			$j('.runImport').before('<div class="alert alert-error">You have fields that are not mapped. They will be assigned to a catch all. <button class="button-primary runImportNoOptions" type="button">Click here to proceed</button></div>');
			return false;
		}	
		//proceed
		$j('body').prepend('<div class="page-overlay"><div class="page-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div></div>');
		$j('#runImportFrm').submit();
	});
	
    $j("#sidebar li a").hover(function(){
    	$j(this).stop().animate({
    		paddingLeft: "20px&"
    	}, 400);
    }, function() {
    	$j(this).stop().animate({
    		paddingLeft: 0
    	}, 400);
    });

});