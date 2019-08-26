<div class="modal-header" style="padding: 30px 25px 35px 28px; background-color: #EFEFEF; border-bottom-width: 0px">
    <div class="modal-title">
        <span>Reported Comment</span>
        <button  onclick="EditCommentReportNote()" class="edit-btn" style="display: block">Edit</button>
    </div>
</div>
<div class="outer-content note-content">
    <div class="tab-content">
        <form action="" method='POST' id="form-note-update">
            <div class="row" style="margin: 0">
                <div class="modal-content group-popup outer-table-modal">
                    <span class="lead" style="font-weight: 600">Report Note</span>
                    <div class="text-note-confirm"><span><?php echo $report[0]['content'] ?></span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>