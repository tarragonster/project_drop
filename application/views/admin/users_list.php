<div class="search-container">
  	<i class="fa fa-search"></i>
    <input type="search" id="search_text" class="form-control" placeholder="Search Users" onkeyup="searchUser();">
</div>
<div class="row">
	<div class="col-xs-12" style='margin-top: 10px'>
		<div class="box">
			<div class="card-box table-responsive">
				<div class="filter-container">
					<label>Status:</label>
					<select class="form-control status-user">
						<option value="2" selected>All</option>
						<option value="1">Active Users</option>
						<option value="0">Inactive Users</option>
					</select>
				</div>
				<div id="user_table"></div>
			</div>
		</div>
	</div>
</div>
