<div class="modal-header" style="padding: 30px 25px 35px 28px; background-color: #EFEFEF; border-bottom-width: 0px">

    <div class="modal-title">
        <div>
            <span>Comment Replies</span>
            <span class="film_title"><?php echo $title[0]['film_name'] ?> - <?php echo $title[0]['episode_name'] ?></span>
        </div>
        <button class="btn-back-comments" data-comment_id='<?php echo $mainComment[0]['comment_id'] ?>'
                onclick="BackComment()">Back to Comments &nbsp;&nbsp;>
        </button>
    </div>
</div>

<div class="outer-content">
    <div class="outer-comment-tab">
        <div class="head-replies">
            <span class="text-uppercase replies-tag">Main Comment</span>
            <span class="text-uppercase replies-tag">Replies</span>
        </div>
        <div class="mainComment-section">
            <span class="comment-username">@<?php echo $mainComment[0]['user_name'] ?></span>
            <div class="comment-box">
                <div class="top-comment-box">
                    <span><?php echo date('m/d/Y h:iA', $mainComment[0]['timestamp']) ?></span>&nbsp;
                    <span>- #</span>
                    <span><?php echo $mainComment[0]['comment_id'] ?></span>
                </div>
                <div class="content-comment-box">
                    <span><?php echo $mainComment[0]['content'] ?></span>
                </div>
                <div class="footer-comment-box">
                    <?php if ($mainComment[0]['status'] == 1) { ?>
                        <div class="rightFooter-comment-box">
                            <img src="<?php echo base_url('assets/imgs/like.svg') ?>" alt="">
                            <span><?php echo $total_commentLike ?></span>
                        </div>
                    <?php } else { ?>
                        <div class="header-item-content item-style status-tb" style="font-weight: 900!important;">
                            <img src="<?= base_url('assets/imgs/red.svg') ?>" alt="red">&nbsp;
                            <span class="text-uppercase">Disabled</span>
                        </div>
                    <?php } ?>
                    <div class="dropdown comment-drp">
                        <span class="btnAction dropdown-toggle" style="margin: 0;display: inline-block; height: 100%"
                              data-toggle="dropdown">
                            <i class="fa fa-ellipsis-h ellipsis-comment"
                               style="color: #d8d8d8;display: inline-block;height: 100%; position: absolute"></i>
                        </span>
                        <ul class="dropdown-menu menu-comment-custom" id="customDropdown">
                            <?php if ($mainComment[0]['status'] == 1) { ?>
                                <li class="text-uppercase"
                                    data-comment_id="<?= $mainComment[0]['comment_id'] ?>"
                                    onclick="ShowDisableCommentBox(this)"><a
                                            class="drp-items"><span>Disable Comment</span></a>
                                </li>
                            <?php } else { ?>
                                <li class="text-uppercase"
                                    data-comment_id="<?= $mainComment[0]['comment_id'] ?>"
                                    onclick="ShowEnableCommentBox(this)"><a
                                            class="drp-items"><span>Enable Comment</span></a>
                                </li>
                            <?php } ?>
                            <li class="text-uppercase"
                                data-comment_id="<?= $mainComment[0]['comment_id'] ?>"
                                onclick="ShowFirstDeleteModal(this)"><a
                                        class="drp-items"><span>Delete Comment</span></a>
                            </li>
                            <li class="text-uppercase"
                                data-user_id="<?= $mainComment[0]['user_id'] ?>"
                                onclick="ShowCommentUser(this)"><a class="drp-items"><span>View User</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="replies-section">
            <?php if (isset($replies) && is_array($replies)) { ?>
                <?php foreach ($replies as $key => $row) { ?>
                    <span class="comment-username reply-username">@<?php echo $row['user_name'] ?></span>
                    <div class="outer-replies-box">
                        <div class="comment-box replies-box" style="background: #9B9B9B!important;">
                            <div class="top-comment-box">
                                <span><?php echo date('m/d/Y h:iA', $row['timestamp']) ?></span>&nbsp;
                                <span>- #</span>
                                <span><?php echo $row['replies_id'] ?></span>
                            </div>
                            <div class="content-comment-box">
                                <span><?php echo $row['content'] ?></span>
                            </div>
                            <div class="footer-comment-box">
                                <?php if ($row['status'] == 1) { ?>
                                    <div class="rightFooter-comment-box">
                                        <img src="<?php echo base_url('assets/imgs/like.svg') ?>" alt="">
                                        <span><?php echo $row['total_replyLike'] ?></span>
                                    </div>
                                <?php } else { ?>
                                    <div class="header-item-content item-style status-tb"
                                         style="font-weight: 900!important;">
                                        <img src="<?= base_url('assets/imgs/red.svg') ?>" alt="red">&nbsp;
                                        <span class="text-uppercase">Disabled</span>
                                    </div>
                                <?php } ?>
                                <div class="dropdown comment-drp">
                                    <span class="btnAction dropdown-toggle"
                                          style="margin: 0;display: inline-block; height: 100%" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h ellipsis-comment"
                                           style="color: #d8d8d8;display: inline-block;height: 100%; position: absolute"></i>
                                    </span>
                                    <ul class="dropdown-menu menu-comment-custom" id="customDropdown">
                                        <?php if ($row['status'] == 1) { ?>
                                            <li class="text-uppercase"
                                                data-replies_id="<?= $row['replies_id'] ?>"
                                                onclick="ShowDisableReplyBox(this)"><a class="drp-items"><span>Disable Comment</span></a>
                                            </li>
                                        <?php } else { ?>
                                            <li class="text-uppercase"
                                                data-replies_id="<?= $row['replies_id'] ?>"
                                                onclick="ShowEnableReplyBox(this)"><a class="drp-items"><span>Enable Comment</span></a>
                                            </li>
                                        <?php } ?>
                                        <li class="text-uppercase"
                                            data-replies_id="<?= $row['replies_id'] ?>"
                                            onclick="ShowFirstDeleteReply(this)"><a class="drp-items"><span>Delete Comment</span></a>
                                        </li>
                                        <li class="text-uppercase"
                                            data-user_id="<?= $row['user_id'] ?>"
                                            onclick="ShowCommentUser(this)"><a class="drp-items"><span>View User</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>

        </div>
    </div>
</div>
