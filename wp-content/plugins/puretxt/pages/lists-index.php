<?php
global $pt;
$subs = $pt->getSubscribersLists();
//echo '<pre>'; print_r($subs); echo '</pre>';

?>
<div class="pt-page-actions">
    <button type="button" class="button button-primary" onclick="location.href='<?php echo get_bloginfo('url');?>/wp-admin/admin.php?page=puretxt-menu-page&pg=lists&act=create_list';">Create List</button> 
    <button type="button" class="button button-primary" onclick="location.href='<?php echo get_bloginfo('url');?>/wp-admin/admin.php?page=puretxt-menu-page&pg=lists&act=import_list';">Import List</button>
</div>

<h2>Subscribers Lists</h2>

<div class="pt-table-responsive">
	<div id="listsOverviewTable_wrapper" class="pt_tables_wrapper no-footer">
		<div class="pt_tables_length" id="listsOverviewTable_length">
			<label>Show 
				<select name="listsOverviewTable_length" aria-controls="listsOverviewTable" class="">
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select> 
			entries</label>
		</div>
		<div id="listsOverviewTable_filter" class="dataTables_filter">
			<label>Search:<input type="search" class="" placeholder="" aria-controls="listsOverviewTable"></label>
		</div>
		<div class="clr"></div>
		<table class="pt-table" id="listsOverviewTable" role="grid" aria-describedby="listsOverviewTable_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" aria-label="List ID: activate to sort column ascending" width="40"><!--Check ALL-->ID</th>
					<th class="sorting" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" style="width: 162px;" aria-label="List Name: activate to sort column ascending">List Name</th>
					<th class="sorting" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" style="width: 181px;" aria-label="Subscribers: activate to sort column ascending">Subscribers</th>
					<th class="sorting_desc" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" style="width: 377px;" aria-sort="descending" aria-label="Date Created: activate to sort column ascending">Date Created</th>
					<th class="sorting_desc" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" style="width: 377px;" aria-sort="descending" aria-label="Date Created: activate to sort column ascending">Last Updated</th>
					<th class="sorting_desc" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" style="width: 377px;" aria-sort="descending" aria-label="Date Created: activate to sort column ascending">Last Run Date</th>
					<th class="sorting" tabindex="0" aria-controls="listsOverviewTable" rowspan="1" colspan="1" style="width: 59px;" aria-label=": activate to sort column ascending"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!empty($subs['data'])) {
					foreach($subs['data'] as $list) {
						$last_run_date = ($list->last_run_date != '0000-00-00 00:00:00') ? : ;
						$last_updated = ($list->last_updated != '0000-00-00 00:00:00') ? date("F jS, Y", strtotime($list->last_updated)) : 'Never' ;
						echo '<tr id="list-tr-' . $list->id . '" role="row">
								<td id="list-td-' . $list->id . '-check" align="center">' . $list->id . '</td>
								<td id="list-td-' . $list->id . '-name"><a href="admin.php?page=puretxt-menu-page&pg=edit_list&id="' . $list->id . '">' . $list->list_name . '</a></td>
								<td id="list-td-' . $list->id . '-subscribers" align="center">' . $list->subscriber_count . '</td>
								<td id="list-td-' . $list->id . '-date-created" class="sorting_1" align="center">' . date("F jS, Y", strtotime($list->creation_date)) . '</td>
								<td id="list-td-' . $list->id . '-date-updated" class="sorting_1" align="center">' . $last_updated . '</td>
								<td id="list-td-' . $list->id . '-date-run" class="sorting_1" align="center">' . date("F jS, Y", strtotime($list->last_run_date)) . '</td>
								<td>
									<button class="button button-primary" onclick="location.href=\'admin.php?page=puretxt-menu-page&pg=edit_list&id="' . $list->id . '\'">Edit</button>
								</td>
							</tr>';
					}
				}
				else {
					
				}
				?>
			</tbody>
		</table>
		
		<div class="pt_tables_info" id="listsOverviewTable_info" role="status" aria-live="polite">Showing 1 to 1 of 1 entries</div>
		<div class="pt_tables_paginate" id="listsOverviewTable_paginate">
			<a class="paginate_button previous disabled" aria-controls="listsOverviewTable" data-dt-idx="0" tabindex="0" id="listsOverviewTable_previous">Previous</a>
			<span><a class="paginate_button current" aria-controls="listsOverviewTable" data-dt-idx="1" tabindex="0">1</a></span>
			<a class="paginate_button next disabled" aria-controls="listsOverviewTable" data-dt-idx="2" tabindex="0" id="listsOverviewTable_next">Next</a>
		</div>
		<div class="clr"></div>
	</div>
</div>