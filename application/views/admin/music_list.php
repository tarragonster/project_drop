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
                    <h3 class="m-t-0 m-b-20 header-title">Musics</h3>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url('admin/music/add') ?>">
                        <button class="btn btn-primary m-t-0 m-b-10">Create music</button>
                    </a>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Singer</th>
                            <th>Film</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        if ($musics != null && count($musics) > 0) {
                            foreach ($musics as $row) {
                                echo '<tr>';
                                echo '<td align="center">' . $row['music_id'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td>' . $row['singer'] . '</td>';
                                echo '<td>' . $row['product_name'] . '</td>';
                                echo "<td><div class='button-list'>";
                                echo "<a href='" . base_url('admin/music/edit/' . $row['music_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Edit</button></a>";
                                echo '<span class="sa-warning" data-href="' . base_url('admin/music/delete/' . $row['music_id']) . '?redirect=' . uri_string(). '"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>';
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
