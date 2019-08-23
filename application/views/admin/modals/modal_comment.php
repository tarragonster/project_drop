<div class="modal fade right" id="view-replies-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog style-modal" role="document">
        <div class="modal-content group-popup" style="padding: 0px" id="view-replies-content">

        </div>
    </div>
</div>

<div class="modal fade" id="disable-comment" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to disable this comment from
                    being viewed on the app? You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveDisableComment()">Disable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="enable-comment" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to enable this comment from
                    being viewed on the app? You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveEnableComment()">Enable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-comment-box" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="delete-content">

        </div>
    </div>
</div>

<div class="modal fade" id="disable-comment-box" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to disable this comment from
                    being viewed on the app? You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveDisableCommentBox()">Disable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="enable-comment-box" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to enable this comment from
                    being viewed on the app? You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveEnableCommentBox()">Enable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disable-reply-box" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to disable this comment from
                    being viewed on the app? You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveDisableReplyBox()">Disable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="enable-reply-box" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to enable this comment from
                    being viewed on the app? You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveEnableReplyBox()">Enable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-reply" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="delete-reply-content">

        </div>
    </div>
</div>

<div class="modal fade" id="disable-commentReported" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to disable this comment?
                    You will be able to undo this in the actions section.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveDisableCommentReported()">Disable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="enable-commentReported" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding-left: 56px!important;padding-right: 56px!important;" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to enable this comment
                    The comment will now be visible on the app and the report comment
                    status will update to report removed.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveEnableCommentReported()">Enable</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-commentReported" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding: 0px" id="delete-reportedComment-content">

        </div>
    </div>
</div>

<div class="modal fade" id="reject-commentReported" tabindex="-1" role="dialog">
    <div class="modal-dialog outer-delete" role="document">
        <div class="modal-content" style="padding-left: 56px!important;padding-right: 56px!important;" id="disable-content">
            <div class="upper-delete-img" style="margin-bottom: 0!important;">
                <div class="outer-img">
                    <img src= "<?php echo base_url('assets/images/AttentionIcon.svg') ?>" alt="">
                </div>
            </div>
            <div class="outer-text-dis">
                <span>Are you sure?</span>
                <div class="detail-dis">Are you sure you want to remove this report
                    The comment will now be visible on the app and the report comment
                    status will update to report removed.
                </div>
            </div>

            <div class="outer-btn">
                <button class="btn-confirm" data-dismiss="modal">Cancel</button>
                <button class="btn-confirm" style="background: #FFC17C;" onclick="SaveEnableCommentReported()">Enable</button>
            </div>
        </div>
    </div>
</div>