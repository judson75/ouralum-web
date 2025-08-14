<div class="pt-page-actions">
    <button type="button" class="button button-primary" onclick="location.href='<?php echo get_bloginfo('url');?>/wp-admin/admin.php?page=puretxt-menu-page&pg=text';"><span class="dashicons dashicons-arrow-left-alt"></span> Back to Text</button> 
</div>

<h2>Text Campaign</h2>


<div class="pt-row">
	<div class="col-md-6">
				
								        	<form method="post" id="saveCampaignFrm">
							<input name="save_campaign" type="hidden" value="1">
							<input name="campaign_id" type="hidden" value="">
							<input name="action" type="hidden" value="saveCampaign">
							<input name="user_id" type="hidden" value="1">
							<div class="form-group">
								<label for="campaignTitle">Campaign Name:</label>
								<input type="text" class="form-control" id="campaignTitle" name="campaign_title" placeholder="Campaign Name" value="">
							</div>
			
							<div class="form-group">
								<label for="subscriberList">Subscriber List:</label>
																<select class="form-control" id="subscriberList" name="list_id" style="">
									<option value="">Choose List</option>
									<option value="1">test</option>								</select>
															</div>
							
							<div class="form-group">
			                    <label for="scheduleDate">Date:</label>
			                    <input type="date" class="form-control" id="scheduleDate" name="scheduleDate" placeholder="Choose Date" value="" min="2020-01-16">
			                </div>
			                <div class="form-group" id="scheduleTimeContainer">
			                    <label for="scheduleTime">Time:</label>
			                    <select class="form-control time-field" id="scheduleHour" name="scheduleHour">
			                    	<option value="">Hour</option>
			                    	<option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>								</select>
			               		<select class="form-control time-field" id="scheduleMin" name="scheduleMin">
			                    	<option value="">Min</option>
			                    	<option value="00">00</option><option value="15">15</option><option value="30">30</option><option value="45">45</option>								</select>
			               		<select class="form-control time-field" id="scheduleMeredian" name="scheduleMeredian">
			                    	<option value="am">AM</option>
			                    	<option value="pm">PM</option>
								</select>
			                </div>
			                <div><small>Times sent are based on Central  Standard Time.</small></div>
			                <div class="form-group" style="padding-top: 20px;">
								<label for="recepientText">Text:</label>
																<select class="t-select " name="templates">
									<option value="">Choose From Template</option>
																			<option value="dsdsdsdsdsdsdsdsd">Test</option>
																	</select>
																<textarea class="form-control" id="recepientText" name="recepient_text"></textarea>
								<div class="toggle" id="use_su">
									Use Short Urls 
									<label class="switch">
									  <input type="checkbox" name="short_url_check">
									  <span class="slider round"></span>
									</label>
								</div>
								<div id="char_count"></div>
							</div>
							<div class="form-group" id="sms_text_btns">
								<button type="button" class="btn btn-xs btn-info" id="addTemplateTagBtn">Add Global Tag</button> <button type="button" class="btn btn-xs btn-info" id="saveTemplateBtn">Save As Template</button>
								<select id="template_tag">
									<option value="{recipient_name}">Recipient Name</option>
									<option value="{recipient_first_name}">Recipient First Name</option>
								</select>
							</div>   
							<div class="form-group">
								<input type="file" id="text_file" name="text_file">
								<div id="uploadedImgCtn">
																	
								</div>
								<button type="button" class="btn btn-primary btn-lg btn-full" id="attachImgBtn"><i class="fa fa-camera fa-lg"></i> Add Image</button>
								<input name="imgUrl" id="imgUrl" type="hidden" value="">
							</div>
							<hr>
							<div class="alert alert-warning"><b>Please Note:</b> If you set up a response, your account will be charged for each incoming text and for the response text sent.</div>
							<div class="form-group">
								<label for="subscriberList">Keyword:</label>
																<select class="form-control" id="keywordList" name="keyword_id" style="">
									<option value="">Choose Keyword</option>
									<option value="4">blah</option>								</select>
															</div>
	
	
							<div class="form-group">
								<label for="responseText">Response:</label>
																<select class="t-select " name="response_templates">
									<option value="">Choose Response</option>
																			<option value="yeah man!!!">Test Campaign Auto Send</option>
																			<option value="">Copy of Test Campaign Auto Send</option>
																	</select>
																<textarea class="form-control" id="responseText" name="response_text"></textarea>
							</div>
							
							<div class="form-group">
								<button type="button" class="btn btn-primary btn-lg btn-full" id="saveCampaignBtn">Save Changes</button>
							</div>
						</form>
											</div>
							</div>