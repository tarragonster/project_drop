<div class="upper-delete-img">
    <div class="outer-img">
        <img src="<?php echo base_url('assets/images/Delete-modal.svg') ?>" alt="">
    </div>
</div>
<div class="outer-text">
    <span>Type "DELETE-USER" in the box below to proceed.
    You will not be able to undo this.
    </span>
</div>
<div class="outer-input">
    <input type="text" id="user-confirm-delete">
    <span class="msg-toDelete-user"></span>
</div>
<div class="outer-btn">
    <button class="btn-confirm" data-dismiss="modal">Cancel</button>
    <button class="btn-confirm" style="background: #E62C30;" onclick="ConfirmDeleteUser()">Delete</button>
</div>
