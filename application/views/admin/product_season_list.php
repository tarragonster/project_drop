<div class="row">
    <div class="col-xs-6">
        <?php if($this->session->flashdata('msg')){
            echo '<div class="alert alert-success">';
            echo $this->session->flashdata('msg');
            echo '</div>';
        } ?>
        <div class="m-t-0 m-b-30 header-title">
            <form action='' method='POST'>
                <div class="box-header">
                    <h3 class="m-t-0 m-b-30 header-title">Create Season for Film <?php echo $name ?></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">                       
                        <label>Name</label>                        
                        <input type="text" name='name' required class="form-control" placeholder="" />
                    </div>
                    <div class="form-group m-b-0">
                        <button type="submit" class="btn btn-primary" name='cmd' value='Save'>Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6">
        <div class="box">
            <div class="card-box table-responsive">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title">List Seasons</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <?php
                        if ($seasons != null && count($seasons) > 0) {
                            foreach ($seasons as $row) {
                                echo '<tr>';
                                echo '<td align="center">' . $row['season_id'] . '</td>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td><div class="button-list">';
                                echo "<a href='" . base_url('season/edit/' . $row['season_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Edit</button></a>";
                                echo "<a href='" . base_url('season/episode/' . $row['season_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>".$row['num_episode']. " Episode</button></a>";
                                echo "</div></td>";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
