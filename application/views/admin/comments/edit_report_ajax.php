<div class="modal-header" style="padding: 30px 25px 35px 28px; background-color: #EFEFEF; border-bottom-width: 0px">
    <div class="modal-title">
        <span>Reported Comment</span>
        <button data-user_id="<?php echo $report_id; ?>" onclick="SaveNoteReportComment()" class="edit-btn"
                style="display: block">Save
        </button>
    </div>
</div>
<div class="outer-content note-content">

    <form action="" method='POST' id="form-note-update">
        <div class="row" style="margin: 0">
            <div class="modal-content group-popup outer-table-modal">
                <span class="lead" style="font-weight: 600">Reported Comment Notes</span>
                <div contenteditable class="form-control style-edit-input note-input"
                     style="min-height: 125px!important;margin-top: 25px!important;"
                     onkeyup="FillNoteComment(this)"><?php echo $report[0]['content'] ?></div>
                <input type="text" name="note" value="<?php echo $report[0]['content'] ?>" style="display: none">
            </div>
        </div>
    </form>

</div>