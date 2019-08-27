<div class="tab-pane" style="padding-top: 30px;	margin-left: 85px;">
    <div class="card-box setting-card-box" style="border-radius: 16px">
        <div class="row">
            <div class="col-md-12">
                <h3 class="title-create-season">
                    Create Season
                </h3>
            </div>
        </div>
        <form action="<?= base_url('product/createSeason') ?>" method="post" autocomplete="off">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-dispatch">
                        <label style="margin-top: 27px;">Season Name</label>
                        <input type="text" required name="season_name" class="form-control form-dispatch" 
                        autocomplete="off" style="color:#403F3F;" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top: 20px">
                    <button type="submit" class="btn btn--rounded btn-orange btn-change-email">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal custom-modal below-header fade visiting right" id="add-block-popup" tabindex="-1" role="dialog">
    <form action="<?php echo base_url('product/addEpisode')?>" method="post" id="block-form-add" enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="add-block-form" style="padding: 0px">
                
            </div>
        </div>
    </form>
</div>
<div class="modal custom-modal below-header fade visiting right" id="edit-block-popup" tabindex="-1" role="dialog">
    <form action="<?php echo base_url('product/editEpisode')?>" method="post" id="block-form-edit" enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="edit-block-form" style="padding: 0px">
                
            </div>
        </div>
    </form>
</div>