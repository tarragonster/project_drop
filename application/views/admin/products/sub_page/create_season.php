<div class="tab-pane <?= $page_index == 'create_season' ? 'active' : ''; ?>" id="setting-profile" style="padding-top: 30px;	margin-left: 85px;">
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
                        <input type="text" required name="season_name" class="form-control form-dispatch" autocomplete="off"/>
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