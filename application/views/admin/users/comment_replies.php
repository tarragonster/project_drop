<div class="modal-header" style="padding: 30px 25px 35px 28px; background-color: #EFEFEF; border-bottom-width: 0px">

    <div class="modal-title">
        <div>
            <span>Comment Replies</span><br>
            <span class="film_title"><?php echo $comment_replies[0]['film_name'] ?> - <?php echo $comment_replies[0]['name'] ?></span>
        </div>
        <button class="btn-back-comments" data-comment_id = '<?php echo $comment_replies[0]['comment_id'] ?>'  onclick="BackComments(this)">Back to Comments &nbsp;&nbsp;></button>
    </div>
</div>
<div class="outer-content">
    <div class="tab-content">

    </div>
</div>