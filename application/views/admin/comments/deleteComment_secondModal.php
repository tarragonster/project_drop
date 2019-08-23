<div class="upper-delete-img">
    <div class="outer-img">
        <img src="<?php echo base_url('assets/images/Delete-modal.svg') ?>" alt="">
    </div>
</div>
<div class="outer-text">
    <span>Type "DELETE-COMMENT" in the box below to proceed.
    You will not be able to undo this.
    </span>
</div>
<form action="" id="form-deleteComment">
    <div class="outer-input">
        <input type="text" name="confirmDeleteComment" id="comment-confirm-delete">
    </div>
</form>

<div class="outer-btn">
    <button class="btn-confirm" data-dismiss="modal">Cancel</button>
    <button class="btn-confirm" style="background: #E62C30;" onclick="ConfirmDeleteCommentReplies()">Delete</button>
</div>

