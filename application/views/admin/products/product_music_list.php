<div class="row">
    <?php if($this->session->flashdata('msg')){
        echo '<div class="col-xs-12"><div class="alert alert-success">';
        echo $this->session->flashdata('msg');
        echo '</div></div>';
    } ?>
    <div class="col-xs-12">
        <h3 class="m-t-0 m-b-20 header-title">Film <?php echo $name ?></h3>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#list">List Music</a></li>
    <li><a data-toggle="tab" href="#add">Add music</a></li>
    <li><a data-toggle="tab" href="#create">Create music</a></li>
</ul>
<div class="row card-box"> 
    <div class="tab-content">
        <div id="list" class="tab-pane fade in active">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Singer</th>
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
                                echo "<td><div class='button-list'>";
                                echo "<a href='" . base_url('product/removedMusic/' . $row['music_id'].'/'.$product_id) . "' /><button class='btn btn-danger btn-custom btn-xs'>Remove</button></a>";
                                echo "</div></td>";
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="add" class="tab-pane fade">
            <div class="row">
                <div class="col-xs-12">               
                    <div class="box-body table-responsive">
                        <table id="example3" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Singer</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <?php
                            if ($others != null && count($others) > 0) {
                                foreach ($others as $row) {
                                    echo '<tr>';
                                    echo '<td align="center">' . $row['music_id'] . '</td>';
                                    echo '<td>' . $row['name'] . '</td>';
                                    echo '<td>' . $row['singer'] . '</td>';
                                    echo "<td><div class='button-list'>";
                                    echo "<a href='" . base_url('product/putInMusic/' . $row['music_id'].'/'.$product_id) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Add</button></a>";
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
        <div id="create" class="tab-pane fade">
        <form action='' method='POST' enctype="multipart/form-data">
            <div class="row">
                <div class="col-xs-6">               
                    <div class="table-responsive">
                        <input type="hidden" name='product_id' class="form-control" value="<?php echo $product_id;?>" />
                        <div class="box-body">
                            <div class="form-group">                       
                                <label>Name</label>                        
                                <input type="text" name='name' required class="form-control" placeholder="" />
                            </div>
                            <div class="form-group">                       
                                <label>Singer</label>                        
                                <input type="text" name='singer' required class="form-control" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Mp3</label>
                                <input type="file" class="form-control" name="music_url"/>
                            </div>
                            <div class="form-group m-b-0">
                                <button type="submit" class="btn btn-inverse btn-custom" name='cmd' value='Save'>Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
