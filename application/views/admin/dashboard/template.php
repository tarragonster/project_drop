<div class="row">
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Users</span>
				<div class="chart-number-box">
					<span id="user_value" class="number-value">&nbsp;</span>

					<div id="user_percent" class="percent-value"></div>
					<div style="clear:both"></div>
				</div>
				<span class="chart-information">Users Over Time</span>
			</div>
			<div class="widget-chart text-center">
				<div id="user-chart" style="height:200px;width:100%;"></div>
			</div>
			<div class="chart-content text-center">
				<div class="chart-label m-l-15">
					<div class="chart-mini-box" style="background-color: #c3cdd4"></div>
					<span class="chart-mini-label chart-label-second"></span>
				</div>
				<div class="chart-label">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label chart-label-first"></span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Comments</span>
				<div class="chart-number-box">
					<span id="comment_value" class="number-value">&nbsp;</span>

					<div id="comment_percent" class="percent-value"></div>
					<div style="clear:both"></div>
				</div>
				<span class="chart-information">Comments Over Time</span>
			</div>
			<div class="widget-chart text-center">
				<div id="comment-chart" style="height:200px;width:100%;"></div>
			</div>
			<div class="chart-content text-center">
				<div class="chart-label m-l-15">
					<div class="chart-mini-box" style="background-color: #c3cdd4"></div>
					<span class="chart-mini-label chart-label-second"></span>
				</div>
				<div class="chart-label">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label chart-label-first"></span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Reviews</span>
				<div class="chart-number-box">
					<span id="review_value" class="number-value">&nbsp;</span>

					<div id="review_percent" class="percent-value"></div>
					<div style="clear:both"></div>
				</div>
				<span class="chart-information">Reviews Over Time</span>
			</div>
			<div class="widget-chart text-center">
				<div id="review-chart" style="height:200px;width:100%;"></div>
			</div>
			<div class="chart-content text-center">
				<div class="chart-label m-l-15">
					<div class="chart-mini-box" style="background-color: #c3cdd4"></div>
					<span class="chart-mini-label chart-label-second"></span>
				</div>
				<div class="chart-label">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label chart-label-first"></span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Watched Blocks</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Block</th>
					<th style="width: 30%">Story</th>
					<th style="width: 100px">Watched</th>
				</tr>
				</thead>
				<tbody id="watched-blocks-body"></tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Reviewed Stories</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Story</th>
					<th style="width: 100px">Reviews</th>
				</tr>
				</thead>
				<tbody id="reviewed-stories-body"></tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Liked Blocks</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Block</th>
					<th style="width: 30%">Story</th>
					<th style="width: 100px">Likes</th>
				</tr>
				</thead>
				<tbody id="liked-blocks-body"></tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Shared Stories</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Story</th>
					<th style="width: 100px">Shares</th>
				</tr>
				</thead>
				<tbody id="shared-stories-body"></tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Commented Blocks</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Block</th>
					<th style="width: 30%">Story</th>
					<th style="width: 100px">Comments</th>
				</tr>
				</thead>
				<tbody id="commented-blocks-body"></tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Commented Stories</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Story</th>
					<th style="width: 100px">Comments</th>
				</tr>
				</thead>
				<tbody id="commented-stories-body"></tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Top Searched Terms</span>
			</div>
			<canvas id="chart-searched" style="max-width: 450px; margin: 25px auto 10px"></canvas>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Age Demographic</span>
			</div>
			<canvas id="chart-age" style="max-width: 450px; margin: 25px auto 10px"></canvas>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Followed User</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Username</th>
					<th>Total Followers</th>
					<th>New Followers</th>
				</tr>
				</thead>
				<tbody id="most-followed-body"></tbody>
			</table>
		</div>
	</div>
</div>