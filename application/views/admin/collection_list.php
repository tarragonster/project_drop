<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="card-box table-responsive">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title">Collections</h3>
                </div>
                <div class="box-header m-t-0 m-b-10">
                    <a href="<?php echo base_url('admin/collection/add') ?>">
                        <button class="btn btn-primary">Create Collection</button>
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <!-- <th>Position</th> -->
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        if ($collections != null && count($collections) > 0) {
                            foreach ($collections as $row) {
                                echo '<tr>';
                                echo '<td align="center">' . $row['collection_id'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                // echo '<td><div class="button-list">';
                                // if ($row['priority'] > 1) {
                                //     echo "<a href='" . base_url('admin/collection/up/' . $row['collection_id']) . "'>
		                              //       <button class='btn btn-sm btn-purple'><i class='fa fa-long-arrow-up'></i> Up</button>
		                              //   </a>";
                                // }
                                // if ($row['priority'] < count($collections)) {
                                //     echo "<a href='" . base_url('admin/collection/down/' . $row['collection_id']) . "'>
		                              //       <button class='btn btn-sm btn-inverse'><i class='fa fa-long-arrow-down'></i> Down</button>
		                              //   </a>";
                                // }
                                // echo '</div></td>';
                                echo "<td><div class='button-list'>";
                                echo "<a href='" . base_url('admin/collection/edit/' . $row['collection_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Edit</button></a>";
                                echo "<a href='" . base_url('admin/collection/films/' . $row['collection_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>View films</button></a>";
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
