<div class="modal-header" style="background: #EFEFEF; z-index: 1056; padding-bottom: 0; height: 110px">
	<div class="custom-header">
		<div class="row">
			<div class="col-md-8 col-lg-8">
				<h1 class="modal-title-name">Add Featured User</h1>
			</div>
			<div class="col-md-4 col-lg-4">
	            <button type="button" class="btn btn-header" id="add-user-btn" onclick="addUser()">Add</button>
	        </div>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="tab-content custom-tab">
		<h4>Featured user search</h4>
		<div class="form-group" style="margin-top: 20px;width: 100%">
	        <label>Search user</label>
	        <input type="hidden" name="user_id" id="user_id">
	        <input type="text" id="user_key" required class="form-control custom-input" onkeyup='searchUser()' placeholder="Search name, username or email"/>
	        <span class="mess_err" id="user_err"></span>
	        <div id="other_user"></div>
	    </div>
	</div>
</div>
