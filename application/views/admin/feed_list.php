<div class="row">
    <?php if($this->session->flashdata('msg')){
        echo '<div class="col-xs-12"><div class="alert alert-danger">';
        echo $this->session->flashdata('msg');
        echo '</div></div>';
    } ?>
    <div class="col-xs-12">
        <h3 class="m-t-0 m-b-20 header-title">Main Feeds</h3>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#list">Feeds</a></li>
    <li ><a data-toggle="tab" href="#add">Add Film</a></li>
</ul>
<div class="row card-box"> 
    <div class="tab-content">
        <div id="list" class="tab-pane fade in active">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table id="example3" class="table table-striped table-bordered" data-alert="Are you want to delete this product?">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>TimeStamp</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($feeds != null && count($feeds) > 0) {
                                foreach ($feeds as $row): ?>
                            <tr>
                                <td align="center"><?php echo $row['product_id'];?></td>
                                <td><img style="max-width: 70px; max-height: 70px" src="<?php echo media_url($row['feed_image']); ?>"/></td>
                                <td><?php echo $row['product_name']?></td>
                                <td><?php echo timeFormat($row['timestamp']); ?></td>
                                <?php
                                    echo '<td><div class="button-list">';
                                    if ($row['position'] > 1) {
                                        echo "<a href='" . base_url('admin/feed/up/' . $row['feed_id']) . "'>
                                                <button class='btn btn-sm btn-purple'><i class='fa fa-long-arrow-up'></i> Up</button>
                                            </a>";
                                    }
                                    if ($row['position'] < $max) {
                                        echo "<a href='" . base_url('admin/feed/down/' . $row['feed_id']) . "'>
                                                <button class='btn btn-sm btn-inverse'><i class='fa fa-long-arrow-down'></i> Down</button>
                                            </a>";
                                    }
                                    echo '</div></td>';
                                ?>
                                <td><div class='button-list'>
                                    <a href='<?php echo base_url("admin/feed/edit/" . $row["feed_id"]) ?>'>
                                        <button class='btn btn-inverse btn-custom btn-xs'>Edit</button>
                                    </a>
                                    <a href='<?php echo base_url('admin/feed/remove/' . $row['feed_id']) ?>'>
                                        <button class='btn btn-danger btn-custom btn-xs'>Remove</button>
                                    </a>
                                    <?php echo "<a href='" . base_url('admin/feed/active/' . $row['feed_id']) . "'><button class='btn btn-custom btn-xs " 
                                    . ($row['status'] != 1 ? "btn-success'>Active" : "btn-inverse'>Deactive"). "</button></a>"; ?>
                                </div>
                                </td>
                            </tr>
                            <?php endforeach ;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="add" class="tab-pane fade">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box table-responsive" style="border: none; margin-bottom: 0px;">
                            <form method='POST' enctype="multipart/form-data">
                                <div class="form-group" id='block_product'>
                                    <label>Film</label>
                                    <input id='select_product' class='form-control' type="text" placeholder='Film Name' data-href='<?php echo base_url('admin/feed/ajaxProduct?q=')?>' data-linked-id='product_id'/>
                                    <input type="hidden" id='product_id' name='product_id' class='form-control'/>
                                </div>
                                <div class="form-group m-b-30">
                                    <label>Image</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img id='image' width='120' height='120'  style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                                        </div>
                                        <div class="col-md-8">
                                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                                            <div class="uploader" onclick="$('#imagePhoto').click()">
                                                <input type="file" accept="img/*" name="image" id="imagePhoto"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name='cmd' value='Add'>Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <!-- <div class="row">
                <div class="col-xs-6 form-group">
                    <label>Name Film</label>
                    <input type="text" id="nameFilm" class="form-control">
                </div> 
                <div class="col-xs-12">

                    <div class="table-responsive">
                        <table id="example3" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Year</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="bodyFilm">
                            <?php
                            if ($others != null && count($others) > 0) {
                                foreach ($others as $row) {
                                    echo '<tr>';
                                    echo '<td align="center">' . $row['product_id'] . '</td>';
                                    echo '<td>' . $row['name'] . '</td>';
                                    echo '<td><img style="max-width: 70px; max-height: 70px" src="'.media_url($row['image']).'"/></td>';
                                    echo '<td>' . $row['publish_year'] . '</td>';
                                    echo "<td><div class='button-list'>";
                                    echo "<a href='" . base_url('admin/feed/add/'. $row['product_id']) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Add</button></a>";
                                    echo "</div></td>";
                                    echo '</tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
