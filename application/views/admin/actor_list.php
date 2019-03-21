<div class="row">
    <?php if($this->session->flashdata('msg')){
        echo '<div class="col-xs-12"><div class="alert alert-success">';
        echo $this->session->flashdata('msg');
        echo '</div></div>';
    } ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="card-box table-responsive">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title">Actors</h3>
                </div>
                <div class="box-header">
                    <a href="<?php echo base_url('actor/add') ?>">
                        <button class="btn btn-primary m-t-0 m-b-10">Create Actor</button>
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>From</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        if ($actors != null && count($actors) > 0) {
                            foreach ($actors as $row) {
                                echo '<tr>';
                                echo '<td align="center">' . $row['cast_id'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td><img style="max-width: 70px; max-height: 70px" src="'.media_thumbnail($row['image'], 70).'"/></td>';
                                echo '<td>' . $row['country'] . '</td>';
                                echo "<td><div class='button-list'>";
                                echo "<a href='" . base_url('actor/edit/' . $row['cast_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Edit</button></a>";
                                echo '<span class="sa-warning" data-href="' . base_url('actor/delete/' . $row['cast_id']) . '"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>';
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
