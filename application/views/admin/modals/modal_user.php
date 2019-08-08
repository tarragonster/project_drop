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
                <span>Type "DELETE" in the box below to proceed. You
                will not be able to undo this.</span>
            </div>
            <div class="outer-input">
                <input type="text" id="input-confirm-delete">
            </div>
            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #E62C30;" onclick="ConfirmDeletePick()">Delete</button>
            </div>
        </div>
    </div>
</div>