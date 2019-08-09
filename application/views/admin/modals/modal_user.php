<div class="modal fade right" id="view-user-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog style-modal" role="document">
        <div class="modal-content group-popup" style="padding: 0px" id="view-user-content">

        </div>
    </div>
</div>

<div class="modal fade" id="edit-pick-view" tabindex="-1" role="dialog">
    <div class="modal-dialog pick-edit" role="document">
        <div class="modal-content" style="padding: 0px" id="edit-pick-content">

        </div>
    </div>
</div>

<div class="modal fade" id="delete-view" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="delete-content">
            <div class="upper-delete-img">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/Delete-modal.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text">
                <span>Type "<span class="text-diff text-uppercase" style="display: inline;"></span>" in the box below to proceed. You
                will not be able to undo this.</span>
            </div>
            <div class="outer-input">
                <input type="text" id="input-confirm-delete">
                <span class="msg-toDelete"></span>
            </div>
            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #E62C30;" onclick="ConfirmDeletePick()">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-view-watch" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="delete-content">
            <div class="upper-delete-img">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/Delete-modal.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text">
                <span>Type "<span class="text-diff text-uppercase" style="display: inline;"></span>" in the box below to proceed. You
                will not be able to undo this.</span>
            </div>
            <div class="outer-input">
                <input type="text" id="watch-confirm-delete">
                <span class="msg-toDelete-watch"></span>
            </div>
            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #E62C30;" onclick="ConfirmDeleteWatch()">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disable-view" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to disable this? The
                        user will no longer have access to the app. You
                        will be able to undo this in the actions section
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveBlockUser()">Disable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="enable-view" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="enable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to enable this? The
                    user will have access to the app. You
                    will be able to undo this in the actions section
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveUnblockUser()">Enable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-user" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="delete-user-content">

        </div>
    </div>
</div>

<!--report note modal-->

<div class="modal fade right" id="view-note-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog style-modal" role="document">
        <div class="modal-content group-popup" style="padding: 0px" id="view-note-content">

        </div>
    </div>
</div>