<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="card-box table-responsive" id="comment-box">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title">Comments</h3>
                </div>
                <div class="form-group" id='block_product'>
                    <label>Episode</label>
                    <input id='select_episode' class='form-control' type="text" placeholder='Episode Name' data-href='<?php echo base_url('comment/ajaxEpisode?q=')?>'/>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Content</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="content-comment">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-box table-responsive" style="display: none;" id="replies-box">
                <div class="box-header">
                    <h3 class="m-t-0 m-b-20 header-title"> <button class="btn btn-xs btn-back-comment">< Back</button>  Replies</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Content</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="content-replies">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id='episode_id' class='form-control'/>
<input type="hidden" id='comment_id' class='form-control'/>