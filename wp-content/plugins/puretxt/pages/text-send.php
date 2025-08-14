<div class="pt-page-actions">
    <button type="button" class="button button-primary" onclick="location.href='<?php echo get_bloginfo('url');?>/wp-admin/admin.php?page=puretxt-menu-page&pg=text';"><span class="dashicons dashicons-arrow-left-alt"></span> Back to Text</button> 
</div>

<h2>Send Text</h2>


<form method="post" id="sendTxtFrm">
						<input name="send_text" type="hidden" value="1">
						<input name="action" type="hidden" value="sendTxt">
						<input name="user_id" type="hidden" value="1">
						<div id="manual_recepient">
							<div class="form-group">
								<label for="recepientName">Recipient Name:</label>
								<input type="text" class="form-control" id="recepientName" name="recepient_name[0]" placeholder="Name" value="">
							</div>

							<div class="form-group">
								<label for="recepientPhone">Recipient Phone:</label>
								<input type="text" class="form-control" id="recepientPhone" name="recepient_phone[0]" placeholder="555-555-5555" value="">
							</div>
						</div>
						<div id="select_recepient">
							<div class="form-group">
																<!--<select class="c-select form-control" name="recepients">-->
								<select class="c-select form-control" name="list_id">
									<option value="all">Send To All Subscribers</option>
									<option value="">test (2)</option>								</select>
															</div>
						</div>
												<div class="form-group">
							<a href="p/setup_campaign" class="btn btn-sm btn-info">Setup Campaign</a>
						</div>

						<hr>
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
							<!--<button type="button" class="btn btn-xs btn-info" id="addTemplateTagBtn">Add Global Tag</button>--> <button type="button" class="btn btn-xs btn-info" id="saveTemplateBtn">Save As Template</button>
							<select id="template_tag">
								<option value="{recipient_name}">Recipient Name</option>
								<option value="{recipient_first_name}">Recipient First Name</option>
							</select>
						</div>   
						<div class="form-group">
							<input type="file" id="text_file" name="text_file">
							<div id="uploadedImgCtn"></div>
							<button type="button" class="btn btn-primary btn-lg btn-full" id="attachImgBtn"><i class="fa fa-camera fa-lg"></i> Add Image</button>
							<input name="imgUrl" id="imgUrl" type="hidden" value="">
						</div>

						<div class="form-group">
							<button type="button" class="btn btn-primary btn-lg btn-full" id="stBtn">Send Text</button> <button type="button" class="btn btn-info btn-lg btn-full" id="scBtn" style="margin-top: 10px;">Schedule Delivery</button>
						</div>
					</form>