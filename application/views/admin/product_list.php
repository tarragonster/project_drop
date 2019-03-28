<?php
if ($this->session->flashdata('alert')) {
    echo '<div class="row"><div class="col-xs-12 alert alert-success">';
    echo $this->session->flashdata('alert');
    echo '</div></div>';
}
if($this->session->flashdata('error')){
    echo '<div class="col-xs-12"><div class="alert alert-danger">';
    echo $this->session->flashdata('error');
    echo '</div></div>';
}
?>
<div class="search-container">
  	<i class="fa fa-search"></i>
    <input type="search" id="search_text" class="form-control" placeholder="Search Films" onkeyup="searchFilm();">
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="card-box table-responsive">
				<div class="filter-container">
					<label>Status:</label>
					<select class="form-control status-film">
						<option value="2" selected>All</option>
						<option value="1">Enable Films</option>
						<option value="0">Disable Films</option>
					</select>
				</div>
				<div id="product_table"></div>
			</div>
		</div>
	</div>
</div>