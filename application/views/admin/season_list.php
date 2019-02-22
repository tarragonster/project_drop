<div class="row">
    <?php if($this->session->flashdata('msg')){
        echo '<div class="col-xs-12"><div class="alert alert-success">';
        echo $this->session->flashdata('msg');
        echo '</div></div>';
    }
    if($this->session->flashdata('error')){
        echo '<div class="col-xs-12"><div class="alert alert-danger">';
        echo $this->session->flashdata('error');
        echo '</div></div>';

    } ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="card-box table-responsive">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title">Seasons</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Film</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <?php
                        if ($seasons != null && count($seasons) > 0) {
                            foreach ($seasons as $row) {
                                echo '<tr>';
                                echo '<td align="center">' . $row['season_id'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td>' . $row['product_name'] . '</td>';
                                echo "<td><div class='button-list'>";
                                echo "<a href='" . base_url('season/edit/' . $row['season_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Edit</button></a>";
                                echo "<a href='" . base_url('season/episode/' . $row['season_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>".$row['num_episode']. " Episode</button></a>";
                                echo '<span class="sa-warning" data-href="' . base_url('season/delete/' . $row['season_id']) . '"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>';
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
