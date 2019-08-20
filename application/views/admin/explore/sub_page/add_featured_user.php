<div class="modal-header" style="background: #EFEFEF; z-index: 1056; padding-bottom: 0; height: 110px">
	<div class="custom-header">
		<div class="row">
			<div class="col-md-8 col-lg-8">
				<h1 class="modal-title-name">Add Featured User</h1>
			</div>
			<div class="col-md-4 col-lg-4">
	            <button type="button" class="btn btn-header" id="create-genre-btn" onclick="saveGenre()">Add</button>
	        </div>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="tab-content custom-tab">
		<h4>Featured user search</h4>
		<div class="form-group ui-widget" style="margin-top: 20px">
	        <label>Search user</label>
	        <input type="text" name="user" id="user" required class="form-control custom-input" onkeyup="test(this)" autocomplete="on" placeholder="Search name, username or email"/>
	    </div>
	</div>
</div>

