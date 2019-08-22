var User = /** @class */ (function () {
    function User() {
        this.typereq = 'get';
        this.isEdit = false;
        this.isProfile = true;
        this.active = 'profile';
        this.switch = false;
        this.contentType = false;
        this.processData = true;
    }
    User.prototype.sendAjaxRequest = function (_callback) {
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
    };
    User.prototype.sendAjaxFormData = function (_callback) {
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
    };
    User.prototype.showUserProfile = function () {
        // $('#view-user-content').html("");
        this.paramreq = {
            user_id: this.user_id,
            isEdit: this.isEdit,
            isProfile: this.isProfile,
            active: this.active,
        };
        this.url = 'user/ajaxProfile/' + this.user_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#view-user-content').html(data.content);
        });
    };
    User.prototype.saveUpdateProfile = function (myFormData) {
        this.paramreq = myFormData;
        this.url = 'user/ajaxEdit/' + this.user_id;
        this.typereq = 'POST';
        this.sendAjaxFormData(function (data) {
            model.showUserProfile();
        });
    };
    User.prototype.showEditPick = function () {
        this.url = 'user/editPick/' + this.pick_id;
        this.typereq = 'GET';
        this.sendAjaxRequest(function (data) {
            $('#edit-pick-content').html(data.content);
        });
    };
    User.prototype.saveEditPick = function () {
        this.url = 'user/editPick/' + this.pick_id;
        this.typereq = 'POST';
        this.paramreq = {
            quote: this.quote
        };
        this.sendAjaxRequest(function (data) {
            model.active = 'your-picks';
            model.showUserProfile();
            model.active = 'profile';
        });
    };
    User.prototype.deletePick = function () {
        this.url = 'user/removePick/' + this.pick_id;
        this.typereq = 'POST';
        this.paramreq = {
            confirmDelete: this.confirmDelete
        };
        this.sendAjaxRequest(function (data) {
            model.active = 'your-picks';
            model.showUserProfile();
            model.active = 'profile';
        });
    };
    User.object = new User();
    return User;
}());
var model = User.object;
function ShowUserProfile(event) {
    model.isProfile = true;
    model.user_id = $(event).data('user_id') * 1;
    model.showUserProfile();
    $('#view-user-popup').modal('show');
}
function EditUserProfile(event) {
    model.isProfile = false;
    model.isEdit = true;
    model.showUserProfile();
    model.isEdit = false;
    model.isProfile = true;
}
function readURL(input) {
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
function SaveUpdateProfile() {
    model.contentType = false;
    model.processData = false;
    var myformData = new FormData();
    myformData.append('full_name', $('input[name=full_name]').val());
    myformData.append('user_name', $('input[name=user_name]').val());
    myformData.append('email', $('input[name=email]').val());
    myformData.append('bio', $('.bio-input').text());
    myformData.append('avatar', $('input[name=avatar]')[0].files[0]);
    model.saveUpdateProfile(myformData);
    model.switch = true;
}
$('#view-user-popup').on('hidden.bs.modal', function () {
    if (model.switch == true) {
        location.reload();
        model.switch = false;
    }
});
function ShowEditPick(event) {
    model.pick_id = $(event).data('pick_id');
    model.showEditPick();
    $('#edit-pick-view').modal('show');
}
function SaveEditPick() {
    model.quote = $('.quote-input').text();
    model.saveEditPick();
    $('#edit-pick-view').modal('hide');
}
function DeleteShow(event) {
    model.pick_id = $(event).data('pick_id');
    $('#delete-view').modal('show');
}
function ConfirmDeletePick() {
    model.confirmDelete = $('#input-confirm-delete').val();
    model.deletePick();
    $('#delete-view').modal('hide');
}
