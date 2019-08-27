declare var $: any;
declare var ajax: any;

class User{

    paramreq: any;
    url: string;
    typereq: string = 'get';
    paramheader:any;
    static object = new User();
    isEdit:boolean = false;
    isProfile:boolean = true;
    isCreate:boolean = false;
    user_id:any;
    active:string = 'profile';

    userName:any;
    fullName:string;
    email:string;
    bio:string;
    file:string;
    switch:boolean = false;

    contentType:boolean = false;
    processData:boolean = true;

    pick_id:number;
    quote:string;
    confirmDelete: string;
    watch_id:number;
    comment_id:number;
    report_id:number;
    episodeLike_id:number;
    productLike_id:number;
    commentLike_id:number;
    note:string;

    sendAjaxRequest(_callback) {

        $.ajax({
            type: this.typereq,
            dataType: 'json',
            url: this.url,
            data: this.paramreq,
            headers: this.paramheader,

            success: function (data) {

                _callback(data);

            },
        });
    }

    sendAjaxFormData(_callback) {

        $.ajax({
            type: this.typereq,
            dataType: 'json',
            url: this.url,
            data: this.paramreq,
            enctype: 'multipart/form-data',
            headers: this.paramheader,

            success: function (data) {

                _callback(data);

            },
            contentType: this.contentType,
            processData: this.processData,
        });
    }

    showUserProfile(){
        // $('#view-user-content').html("");
        this.paramreq = {
            user_id: this.user_id,
            isEdit: this.isEdit,
            isProfile: this.isProfile,
            isCreate:this.isCreate,
            active: this.active,
        };
        this.url = '/user/ajaxProfile/' + this.user_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            model.isProfile = true;
            model.isEdit = false;
            model.isCreate = false;
            $('#view-user-content').html(data.content);
        })
    }

    showCommentUser(){
        this.paramreq = {
            user_id: this.user_id,
            isEdit: this.isEdit,
            isProfile: this.isProfile,
            isCreate:this.isCreate,
            active: this.active,
        };
        this.url = '/user/ajaxProfile/' + this.user_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            model.isProfile = true;
            model.isEdit = false;
            model.isCreate = false;
            $('#view-replies-content').html(data.content);
        })
    }

    saveUpdateProfile(myFormData){
        this.paramreq = myFormData;
        this.url = '/user/ajaxEdit/' + this.user_id;
        this.typereq = 'POST';

        this.sendAjaxFormData(function (data) {

            if(data.user_message != null || data.email_message != null){
                $('.email-smg').text(data.email_message);
                $('.user-smg').text(data.user_message);

            }else{
                model.showUserProfile()
            }
        })
    }

    showEditPick(){

        this.url = '/user/editPick/' + this.pick_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#edit-pick-content').html(data.content)

        })
    }

    saveEditPick(){
        this.url = '/user/editPick/' + this.pick_id;
        this.typereq = 'POST';
        this.paramreq = {
            quote: this.quote
        };

        this.sendAjaxRequest(function (data) {

            model.active = 'your-picks';
            model.showUserProfile()
            model.active = 'profile';
        })

    }

    deletePick(){
        this.url = '/user/removePick/' + this.pick_id;
        this.typereq = 'POST';
        this.paramreq = {
            confirmDelete: this.confirmDelete
        };
        this.sendAjaxRequest(function (data) {
            $('.msg-toDelete').text(data.message);
            model.active = 'your-picks';
            model.showUserProfile()
            model.active = 'profile';
            ToClosePickModal()
        })
    }
    confirmDeleteWatch(){
        this.url = '/user/removeWatch/' + this.watch_id;
        this.typereq = 'POST';
        this.paramreq = {
            confirmDelete: this.confirmDelete
        };
        this.sendAjaxRequest(function (data) {
            $('.msg-toDelete-watch').text(data.message);
            model.active = 'watch-list';
            model.showUserProfile()
            model.active = 'profile';
            ToCloseWatchModal()
        })
    }

    saveBlockUser(){
        this.url = '/user/block/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload();
        })

    }

    saveUnblockUser(){
        this.url = '/user/unblock/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload();
        })
    }
    showFirstDeleteModal(){
        this.url = '/user/firstModalDelete';
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#delete-user-content').html(data.content)
        })
    }
    showSecondDeleteModal(){
        this.url = '/user/secondModalDelete';
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#delete-user-content').html(data.content)
        })
    }

    confirmDeleteUser(){
        this.url = '/user/delete/' + this.user_id;
        this.typereq = 'POST';
        this.paramreq = {
            confirmDelete: this.confirmDelete
        };
        this.sendAjaxRequest(function (data) {
            $('.msg-toDelete-user').text(data.message);
            ToCloseUserModal()
        })
    }

    showReportNote(){
        this.url = '/user/showNote/' + this.report_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#view-note-content').html(data.content);

        })
    }
    saveReportNote(){
        this.url = '/user/saveNote/' + this.report_id;
        this.typereq = 'POST';
        this.paramreq = {
            note: this.note
        };
        this.sendAjaxRequest(function (data) {
            $('#view-note-content').html(data.confirmContent);
            model.switch =true
        })
    }
    editReportNote(){
        this.url = '/user/editNote/' + this.report_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#view-note-content').html(data.content);

        })
    }

    showConfirmNote(){
        this.url = '/user/confirmNote/' + this.report_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#view-note-content').html(data.content);

        })
    }
    removeComment(){
        this.url = '/user/deleteComment/' + this.comment_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            model.active = 'comments';
            model.showUserProfile();
            model.active = 'profile';
            $('#delete-comment').modal('hide')
        })
    }
    showCommentReplies(){
        this.url = '/user/ShowCommentReplies/' + this.comment_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            if(location.pathname.split('/')[1] == 'user'){
                $('#view-user-content').html('');
                $('#view-user-content').html(data.content)
            }
            if(location.pathname.split('/')[1] == 'comment'){
                $('#view-replies-content').html('');
                $('#view-replies-content').html(data.content)
            }
        })
    }

    saveDisableUserReported(){
        this.url = '/user/disableUserReported/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload()
        })
    }
    saveEnableUserReported(){
        this.url = '/user/enableUserReported/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload()
        })
    }

    saveRemoveReport(){
        this.url = '/user/saveRemoveReport/' + this.report_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload()
        })
    }
    showFirstDeleteReported(){
        this.url = '/user/firstModalDeleteReported';
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#delete-user-content').html(data.content)
        })
    }

    showSecondDeleteReported(){
        this.url = '/user/showSecondDeleteReported';
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#delete-user-content').html(data.content)
        })
    }

    confirmDeleteReported(){
        this.url = '/user/deleteReported/' + this.user_id;
        this.typereq = 'POST';
        this.paramreq = {
            confirmDelete: this.confirmDelete
        };
        this.sendAjaxRequest(function (data) {
            $('.msg-toDelete-user').text(data.message);
            ToCloseUserModal()
        })
    }

    addVerify(){
        this.url = '/user/addVerify/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload()
        })
    }
    addCurator(){
        this.url = '/user/addCurator/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload()
        })
    }

    removeTag(){
        this.url = '/user/removeTag/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            location.reload()
        })
    }

    confirmDeleteEpisodeLike(){
        this.url = '/user/deleteEpisodeLike/' + this.episodeLike_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            model.switch = true;
            model.active = 'thumb-up';
            model.showUserProfile();
            model.active = 'profile';
            $('#delete-episode-like').modal('hide')
        })
    }

    confirmDeleteProductLike(){
        this.url = '/user/deleteProductLike/' + this.productLike_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            model.switch = true;
            model.active = 'thumb-up';
            model.showUserProfile();
            model.active = 'profile';
            $('#delete-product-like').modal('hide')
        })
    }
    confirmDeleteCommentLike(){
        this.url = '/user/deleteCommentLike/' + this.commentLike_id;
        this.typereq = 'POST';
        this.sendAjaxRequest(function (data) {
            model.switch = true;
            model.active = 'thumb-up';
            model.showUserProfile();
            model.active = 'profile';
            $('#delete-comment-like').modal('hide')
        })
    }

    ShowReportedCommentUser(){

    }

}

let model = User.object;

function ShowUserProfile(event){

    model.isProfile =true
    model.isEdit= false
    model.user_id = $(event).data('user_id') * 1;
    model.showUserProfile()
    $('#view-user-popup').modal('show');
}

function EditUserProfile(event){
    model.isProfile =false
    model.isEdit= true
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile()
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser()
    }
    model.isEdit= false
    model.isProfile =true

}

function readURL(input){

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        $('.the-avatar').attr('src', "");
        reader.onload = function (e) {
            $('.the-avatar')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }

}

function SaveUpdateProfile(){
    //form-update
    console.log('SaveUpdateProfile');

    $("#form-update" ).validate({
        rules: {
            full_name: {
                required: true,
            },
            email:{
                required:true,
                email:true
            },
            user_name:{
                required:true,
            },
            bio:{
                required:true,
            },
            avatar:{
                filesize: 1000000,
            }
        },
        messages: {
            email: {
                required: "Email is invalid",
                email: "Your email address must be in the format of name@domain.com"
            }
        }
    });
    let validatedata = $("#form-update").valid();
    if(validatedata ==true){
        model.contentType = false;
        model.processData = false;

        var myformData = new FormData();
        myformData.append('full_name', $('input[name=full_name]').val());
        myformData.append('user_name', $('input[name=user_name]').val());
        myformData.append('email', $('input[name=email]').val());
        myformData.append('bio', $('.bio-input').text());
        myformData.append('avatar', $('input[name=avatar]')[0].files[0]);

        if($('.check-feature').is(":checked")){
            myformData.append('feature', '1');
        }else{
            myformData.append('feature', '0');

        }

        if($('.check-curator').is(":checked")){
            myformData.append('curator', '2');
        }else{
            myformData.append('curator', '0');
        }

        model.saveUpdateProfile(myformData);
        model.switch = true
    }

}

$('#view-user-popup').on('hidden.bs.modal', function () {

    if(model.switch == true){
        location.reload();
        model.switch = false
    }
});

$('#view-note-popup').on('hidden.bs.modal', function () {

    if(model.switch == true){
        location.reload();
        model.switch = false
    }
});

function ShowEditPick(event){
    model.pick_id = $(event).data('pick_id');

    model.showEditPick()
    $('#edit-pick-view').modal('show')

}

function SaveEditPick(){

    model.quote = $('.quote-input').text();
    model.saveEditPick()
    $('#edit-pick-view').modal('hide')
}

function DeleteShow(event){
    model.pick_id = $(event).data('pick_id')
    $('.msg-toDelete').text(' ')
    $('.text-diff').text('delete')
    $('#delete-view').modal('show');
    $('#input-confirm-delete').val('')
}

function ConfirmDeletePick(){
    model.confirmDelete = $('#input-confirm-delete').val();
    model.deletePick();

}
function ToClosePickModal(){
    if($('.msg-toDelete').text().trim() ==''){
        $('#delete-view').modal('hide');
        model.switch = true
    }
}

function DeleteShowWatch(event){
    model.watch_id = $(event).data('watch_id')
    $('.msg-toDelete-watch').text(' ')
    $('.text-diff').text('remove')
    $('#delete-view-watch').modal('show');
    $('#watch-confirm-delete').val('')


}

function ConfirmDeleteWatch(){
    model.confirmDelete = $('#watch-confirm-delete').val();
    console.log(model.confirmDelete)
    model.confirmDeleteWatch();
}

function ToCloseWatchModal(){
    if($('.msg-toDelete-watch').text().trim() ==''){
        $('#delete-view-watch').modal('hide');
        model.switch = true
    }
}

function ShowBlockUser(event){
    model.user_id = $(event).data('user_id');
    $('#disable-view').modal('show')
}

function SaveBlockUser(){
    model.saveBlockUser();
}

function ShowUnblockUser(event){
    model.user_id = $(event).data('user_id');
    $('#enable-view').modal('show')
}

function SaveUnblockUser(){
    model.saveUnblockUser();
}

function ShowFirstDeleteModal(event){
    model.user_id = $(event).data('user_id');
    model.showFirstDeleteModal();
    $('#delete-user').modal('show')
}

function ShowSecondDeleteModal(){
    model.showSecondDeleteModal()
}

function ConfirmDeleteUser(){
    model.confirmDelete = $('#user-confirm-delete').val()
    model.confirmDeleteUser()
}

function ToCloseUserModal(){
    if($('.msg-toDelete-user').text().trim() ==''){
        location.reload()
    }
}

// report modal

function ShowReportNote(event){
    model.report_id = $(event).data('report_id')
    model.showReportNote()
    $('#view-note-popup').modal('show')
}

function SaveReportNote(){
    $("#form-user-update" ).validate({
        rules: {
            note: {
                required: true,
            },
        },
        messages: {

        }
    });
    let validatedata = $("#form-user-update" ).valid()
    if(validatedata ==true){
        model.note = $('.note-input').text()
        model.saveReportNote()
    }
}

function EditReportNote(){
    model.editReportNote()
}

$(document).ready(function () {

    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });


});

function ShowRemoveComment(event){
    model.comment_id = $(event).data('comment_id');
    $('#delete-comment').modal('show')
}

function RemoveComment(){
    model.removeComment()
    model.switch = true
}

function ShowCommentReplies(event){
    model.comment_id = $(event).data('comment_id');
    model.showCommentReplies()
}

function BackComments(event){
    model.comment_id = $(event).data('comment_id')
    model.active = 'comments';
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile();
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser();
    }
    model.active = 'profile';
}

function ShowTabProfile(){
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile();
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser()
    }

}

function ShowTabComment(){
    model.isProfile = false;
    model.isEdit = false;
    model.isCreate = false;
    model.active = 'comments';
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile();
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser()
    }
    model.active = 'profile';

}

function ShowTabPick(){
    model.isProfile = false;
    model.isEdit = false;
    model.isCreate = false;
    model.active = 'your-picks';
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile();
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser()
    }
    model.active = 'profile';
}

function ShowTabWatch(){
    model.isProfile = false;
    model.isEdit = false;
    model.isCreate = false;
    model.active = 'watch-list';
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile();
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser()
    }
    model.active = 'profile';
}

function ShowTabThumbsup(){
    model.isProfile = false;
    model.isEdit = false;
    model.isCreate = false;
    model.active = 'thumb-up';
    if(location.pathname.split('/')[1] == 'user'){
        model.showUserProfile();
    }
    if(location.pathname.split('/')[1] == 'comment'){
        model.showCommentUser()
    }
    model.active = 'profile';
}

function ShowDisableUserReported(event){
    model.user_id = $(event).data('user_id');
    $('#disable-user-reported').modal('show');
}

function SaveDisableUserReported(){
    model.saveDisableUserReported()
}

function ShowEnableUserReported(event){
    model.user_id = $(event).data('user_id');
    $('#enable-user-reported').modal('show');

}

function SaveEnableUserReported(){
    model.saveEnableUserReported()
}

function ShowRemoveReport(event){
    model.report_id = $(event).data('report_id');
    $('#remove-reported').modal('show');

}

function SaveRemoveReport(){
    model.saveRemoveReport()
}

function ShowFirstDeleteReported(event){
    model.user_id = $(event).data('user_id');
    model.showFirstDeleteReported()
    $('#delete-user').modal('show')

}

function ShowSecondDeleteReported(event){
    model.showSecondDeleteReported()
}

function ConfirmDeleteReported(){
    model.confirmDelete = $('#user-confirm-delete').val()
    model.confirmDeleteReported()

}

function FillInput(event){
    var divfield = $(event).text();
    $("[name=bio]").val(divfield)
}

function FillNote(event){
    var divfield = $(event).text();
    $("[name=note]").val(divfield)

}

$(document).ready(function(){
    $.validator.setDefaults({
        ignore: []
    });

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 1MB');

    $.validator.addMethod("confirmInput", function(value, element) {
        if(this.optional(element) || value == "REMOVE") {
            return true;
        }else{
            return false;
        }
    },'Sorry, it must be confirmed by typing "REMOVE" into input box above');
});

function ShowEditNote(event){
    model.report_id = $(event).data('report_id')
    $('#view-note-popup').modal('show')
    model.editReportNote()
}

function ShowConfirmNote(event){
    model.report_id = $(event).data('report_id')
    $('#view-note-popup').modal('show')
    model.showConfirmNote()

}

function AddVerify(event){
    model.user_id = $(event).data('user_id');
    model.addVerify()
}

function AddCurator(event){
    model.user_id = $(event).data('user_id');
    model.addCurator()
}

function RemoveTag(event){
    model.user_id = $(event).data('user_id');
    model.removeTag()
}

function showDeleteEpisodeLike(event){
    model.episodeLike_id = $(event).data('el_id');
    $('#delete-episode-like').modal('show');
}

function ConfirmDeleteEpisodeLike(){
    $('#form-episodeLike-delete').validate({
        rules: {
            confirmDelete: {
                required: true,
                confirmInput:true
            },

        },
    });
    let validatedata = $("#form-episodeLike-delete").valid();
    if(validatedata ==true){
        model.confirmDeleteEpisodeLike();
    }
}

function ShowDeleteProductLike(event){
    model.productLike_id = $(event).data('pl_id');
    $('#delete-product-like').modal('show');
}

function ConfirmDeleteProductLike(){
    $('#form-productLike-delete').validate({
        rules: {
            confirmDeletePL: {
                required: true,
                confirmInput:true
            },
        },
    });
    let validatedata = $("#form-productLike-delete").valid();
    if(validatedata ==true){
        model.confirmDeleteProductLike();
    }
}

function ShowDeleteCommentLike(event){
    model.commentLike_id = $(event).data('cl_id');
    $('#delete-comment-like').modal('show');
}

function ConfirmDeleteCommentLike(){
    $('#form-commentLike-delete').validate({
        rules: {
            confirmDeleteCL: {
                required: true,
                confirmInput:true
            },
        },
    });
    let validatedata = $("#form-commentLike-delete").valid();
    if(validatedata ==true){
        model.confirmDeleteCommentLike();
    }
}

function ShowCommentUser(event){
    model.isProfile =true
    model.isEdit= false
    model.user_id = $(event).data('user_id');
    model.showCommentUser()
    $('#view-replies-popup').modal('show');
}

function ShowReportedCommentUser(event){
    model.isProfile =true
    model.isEdit= false
    model.user_id = $(event).data('user_id');
    model.showCommentUser()
    $('#view-replies-popup').modal('show');
}