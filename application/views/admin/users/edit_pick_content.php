<form action="" method='POST'>
    <div class="word-section">
        <span class="edit-pick-title">Edit Review</span><br>
        <span class="intro-tag">Film Name:</span>&nbsp;<span class="detail-tag"><?php echo $name ?></span><br>
        <span class="intro-tag">Username:</span>&nbsp;<span class="detail-tag"><?php echo $full_name ?>&nbsp;-&nbsp;@<?php echo $user_name ?></span>
    </div>
    <div class="box-section" style="padding-top: 30px">
        <div contenteditable class="form-control style-edit-input quote-input"><?php echo $quote; ?></div>
    </div>
    <div class="foot-section">
        <button type="button" data-dismiss="modal" class="btn-pickEdit">Cancel</button>
        <button type="button" class="btn-pickEdit" style="background:#C7AE6E;!important;" onclick="SaveEditPick()">Save</button>
    </div>
</form>
