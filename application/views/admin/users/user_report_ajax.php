<div class="modal-header" style="padding: 30px 25px 35px 28px; background-color: #EFEFEF; border-bottom-width: 0px">
    <div class="modal-title">
        <span >Reported User Notes</span>
        <button data-user_id="<?php echo $report_id; ?>" onclick="SaveReportNote()" class="edit-btn"
                style="display: block">Save
        </button>
    </div>
</div>
<div class="outer-content note-content">
    <form action="" method='POST' id="form-user-update">
        <div class="row" style="margin: 0">
            <div class="modal-content group-popup outer-table-modal">
                <span class="lead" style="font-weight: 600">Report Note</span>
                <div contenteditable class="form-control style-edit-input note-input"
                     style="min-height: 125px!important;margin-top: 25px!important;" onkeyup="FillNote(this)"></div>
                <input type="text" name="note" style="display: none">
            </div>
        </div>
    </form>
</div>