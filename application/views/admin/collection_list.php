<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="card-box table-responsive">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title">Collections</h3>
                </div>
                <div class="box-header m-t-0 m-b-10">
                    <a href="<?php echo base_url('collection/add') ?>">
                        <button class="btn btn-primary">Create Collection</button>
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        if ($collections != null && count($collections) > 0) {
                            foreach ($collections as $row) {
                                echo '<tr>';
                                echo '<td align="center">' . $row['collection_id'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo "<td><div class='button-list'>";
                                echo "<a href='" . base_url('collection/edit/' . $row['collection_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Edit</button></a>";
                                if ($row['collection_type'] != 3) {
	                                echo "<a href='" . base_url('collection/films/' . $row['collection_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>View films</button></a>";
                                }
                                if ($row['collection_type'] == 1 || $row['collection_type'] == 5 || $row['collection_type'] == 2) {
		                            echo "<a href='" . base_url('collection/films/' . $row['collection_id']) . "?active=add' /><button class='btn btn-inverse btn-custom btn-xs'>Add Film</button></a>";
	                            }
                                echo "</div></td>";
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
