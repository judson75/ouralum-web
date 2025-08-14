<div class="pt-page-actions">
    <button type="button" class="button button-primary" onclick="location.href='<?php echo get_bloginfo('url');?>/wp-admin/admin.php?page=puretxt-menu-page&pg=lists';"><span class="dashicons dashicons-arrow-left-alt"></span> Back to Lists</button> 
</div>

<h2>Create List</h2>



<form id="list_create_frm">
    <p>what to do....</p>
    <div class="pt-form-group">
        <label for="list_name">List Name <span class="required_marker">*</span></label>
        <input name="list_name" id="list_name">
    </div>
    <div class="pt-form-group">
        <select name="list_source">
            <option value="">Choose List Source</option>
            <option value="post_type">Post Type</option>
            <option value="database_table">Database Table</option>
        </select> <i class="fa fa-question-circle fa-2x pt-info" data-tip="list_source" aria-hidden="true"></i>
    </div>

</form>
