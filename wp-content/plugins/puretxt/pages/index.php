


<?php


if(PT_API_KEY == '') {
    echo '<div class="api-key-form">
            <div class="api-key-title">Enter API Key</div>
            <input name="api_key" class="api_key_field"> 
            <button class="button button-primary pt-key-btn">Verify Key</button>
            <a href="https://puretxt.com">Click Here To Get AN API KEY</a>
          </div>';
}
else {
    $pt_api_key = get_option('_pt_api_key');
	echo '<i class="fa fa-refresh refreshData" aria-hidden="true"></i>';
	
    echo '<div class="api-key-display">API KEY';
    echo '<div class="api-key-title">' . $pt_api_key . '</div></div>';
    echo '<div class="pt-row pt-content-top">
                <div class="pt-col-12">
                    <div id="dbs1"></div>
                </div>
                <div class="clr"></div>
                <div class="pt-col-3">
                    <div class="pt-widget-block">
                        <div class="" style="text-align: center;">
                            <div class=pt-widget blue">
                                <h2>9999</h2>
                                <p>Text Remaining</p>
                            </div>
                        </div>		
                    </div>
                </div>
                <div class="pt-col-3">
                    <div class="pt-widget-block">
                        <div class="">
                            <div class="pt-widget blue">
                                <div class="pt-widget-left">
                                    <h2>0</h2>
                                    <p>Text Sent This Week</p>
                                </div>
                                <div class="pt-widget-right">
                                    <p><i class="fa fa-mobile fa-5x" aria-hidden="true"></i></p>
                                </div>
                                <div class="clr"></div>
                            </div>
                        </div> 		
                    </div>
                </div>
                <div class="pt-col-3">
                    <div class="pt-widget-block">
                        <div class="">
                            <div class="pt-widget blue">
                                <div class="pt-widget-left">
                                    <h2>0</h2>
                                    <p>Replies This Week</p>
                                </div>
                                <div class="pt-widget-right">
                                    <p><i class="fa fa-mobile fa-5x" aria-hidden="true"></i></p>
                                </div>
                                <div class="clr"></div>
                            </div>
                        </div> 		
                    </div>
                </div>
                <div class="pt-col-3">
                    <div class="pt-widget-block">
                        <div class="">
                            <div class="pt-widget blue">
                                <div class="pt-widget-left">
                                    <h2>0</h2>
                                    <p>New Subscribers This Week</p>
                                </div>
                                <div class="pt-widget-right">
                                    <p><i class="fa fa-user-plus fa-5x" aria-hidden="true"></i></p>
                                </div>
                                <div class="clr"></div>
                            </div>
                        </div> 		
                    </div>
                </div>
                <div class="clr"></div>
            </div>';

}

?>