<div class="modal-header" style="padding: 30px 25px 35px 28px; background-color: #EFEFEF; border-bottom-width: 0px">
    <?php
    $active = isset($_GET['active']) ? $_GET['active'] : 'profile';
    ?>

    <div class="modal-title">
        <span>User Profile</span>
        <?php if($isEdit == 'true'){ ?>
        <label for="submit-update" data-user_id = "<?php echo $user['user_id']; ?>" onclick="SaveUpdateProfile()"  class="edit-btn" style="display: block">Save</label>
        <?php } ?>
        <?php if($isProfile == 'true'){ ?>
            <button  data-user_id = "<?php echo $user['user_id']; ?>" onclick="EditUserProfile()" class="edit-btn" style="display: block">Edit</button>
        <?php } ?>
    </div>
</div>
<ul class="nav nav-tabs">
    <li <?= $active == 'profile' ? 'class="active"' : '' ?> style="width: 97px;height: 35px">
        <a data-toggle="tab" href="#profile">Profile</a>
    </li>
    <li <?= $active == 'comments' ? 'class="active"' : '' ?> style="width: 120px;height: 35px">
        <a data-toggle="tab" href="#comments">Comments</a>
    </li>
    <li <?= $active == 'your-picks' ? 'class="active"' : '' ?> style="width: 123px;height: 35px">
        <a data-toggle="tab" href="#your-picks">Your Picks</a>
    </li>
    <li <?= $active == 'watch-list' ? 'class="active"' : '' ?> style="width: 121px;height: 35px">
        <a data-toggle="tab" href="#watch-list">Watch List</a>
    </li>
    <li <?= $active == 'thumb-up' ? 'class="active"' : '' ?> style="width: 139px;height: 35px">
        <a data-toggle="tab" href="#thumb-up">Thumbs up</a>
    </li>
</ul>

<div class="outer-content">
    <div class="tab-content">
        <div id="profile" class="tab-pane fade in<?=  $active== 'profile' ? ' active' : '' ?>">
            <form action="" method='POST' id="form-update" enctype = 'multipart/form-data'>
                <div class="row" style="margin: 0">
                    <div class="upper-info">
                        <div class="left-info">
                            <span class="lead text-uppercase">User Details</span>
                            <div class="table-responsive" style="padding-top: 20px!important;">
                                <table class="table table-user-detail">
                                    <tbody>
                                    <tr>
                                        <td style="padding-left: 0!important;" class="first-column">Full Name:</td>
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <input type="text" name='full_name' value="<?php echo $user['full_name']; ?>" class="form-control style-edit-input" placeholder=""/>
                                            <?php }else{ ?>
                                                <?php echo empty($user['full_name']) ? 'N/A' : $user['full_name']; ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;" class="first-column">User Name:</td>
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <div class="user-outer">
                                                    <span class="user-tag">@</span>
                                                    <input type="text" name='user_name' value="<?php echo $user['user_name']; ?>" class="form-control style-edit-input" placeholder="" style="padding-left: 5px!important;"/>
                                                </div>
                                            <?php }else{ ?>
                                                <?php echo empty($user['user_name']) ? 'N/A' : $user['user_name']; ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;" class="first-column">Email:</td>
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <input type="text" name='email' value="<?php echo $user['email']; ?>" class="form-control style-edit-input" placeholder=""/>
                                            <?php }else{ ?>
                                                <?php echo $user['email']; ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;" class="first-column">Feature:</td>
                                        <td class="next-column"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;" class="first-column">Curator:</td>
                                        <td class="next-column"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;vertical-align: text-top;padding-top: 5px!important;" class="first-column">Bio:</td>
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <div contenteditable class="form-control style-edit-input bio-input"><?php echo $user['bio']; ?></div>
<!--                                                <input type="text" value="--><?php //echo $user['bio']; ?><!--" name="bio" style="display: none">-->
                                            <?php }else{ ?>
                                                <?php echo $user['bio']; ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="right-info">
                            <div style="position: relative">
                                <span class="text-uppercase ava-text">Avatar Image :</span>
                                <div class="outer-img"><img width='80' class="the-avatar" src='<?= media_thumbnail($user['avatar'], 80) ?>'/></div>
                                <?php if($isEdit == 'true'){ ?>
                                    <div class="outer-upload-img">
                                        <label for="upload-photo">Upload</label>
                                        <input type="file" name='avatar' id="upload-photo" onchange="readURL(this)"/>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="under-info">
                        <span class="lead text-uppercase">User Stats</span>
                        <div class="container-stats" style="margin-top: 20px">
                            <div class="stats-element">
                                <div class="title-stats"><span># of Comments:</span></div>
                                <div class="content-stats"><span style="display: block"><?php echo count($user_comments) ?></span></div>
                            </div>
                            <div class="stats-element">
                                <div class="title-stats"><span># of Picks:</span></div>
                                <div class="content-stats"><span style="display: block"><?php echo count($your_picks) ?></span></div>
                            </div>
                            <div class="stats-element">
                                <div class="title-stats"><span># of Thumbs Up:</span></div>
                                <div class="content-stats"><span style="display: block"><?php echo count($user_likes) ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="button" id="submit-update" style="display: none">
            </form>
        </div>

        <div id="comments" class="tab-pane fade in<?= $active == 'comments' ? ' active' : '' ?>">
            <div class="row" style="margin: 0">
                    <div class="modal-content group-popup outer-table-modal">
                        <table class="table dataTable table-hover table-modal">
                            <thead>
                            <tr>
                                <th style="width: 20%;padding: 0!important; height: 24px!important; padding-left: 10px!important;">Comment Id</th>
                                <th style="width: 30%;padding: 0!important; height: 24px!important;">Comment</th>
                                <th style="width: 20%;padding: 0!important; height: 24px!important;">Date</th>
                                <th style="width: 20%;padding: 0!important; height: 24px!important;">Status</th>
                                <th style="width: 10%;padding: 0!important; height: 24px!important;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($user_comments != null && count($user_comments) > 0) {
                                foreach ($user_comments as $row): ?>
                                    <tr>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-left: 20px!important;"><?php echo $row['comment_id']; ?></td>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-right: 10px!important;">
                                            <span style="font-weight: 900;!important;"><?php echo $row['name']; ?></span><br>
                                            <?php echo $row['content']; ?>
                                        </td>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"><?php echo $row['timestamp'] ?></td>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"></td>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                            <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                                <ul class="dropdown-menu" id="customDropdown">
                                                    <li class="text-uppercase view-user-click" data-user_id="<?= $row['user_id'] ?>"><a href="#" class="drp-items"><span>View</span><img
                                                                    src="<?= base_url('assets/images/view.svg') ?>" alt=""></a>
                                                    </li>
                                                    <li class="text-uppercase"><a href="<?php echo base_url('user/delete/' . $row['user_id']) ?>" class="drp-items"><span>Delete</span><img
                                                                    src="<?= base_url('assets/images/delete.svg') ?>" alt=""></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            } ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>

        <div id="your-picks" class="tab-pane fade in<?= $active == 'your-picks' ? ' active' : '' ?>">
            <div class="row" style="margin: 0">
                <div class="modal-content group-popup outer-table-modal">
                    <table class="table dataTable table-hover table-modal">
                        <thead>
                        <tr>
                            <th style="width: 20%;padding: 0!important; height: 24px!important; padding-left: 10px!important;">Story Id</th>
                            <th style="width: 30%;padding: 0!important; height: 24px!important;">Picks</th>
                            <th style="width: 20%;padding: 0!important; height: 24px!important;">Date</th>
                            <th style="width: 20%;padding: 0!important; height: 24px!important;">Status</th>
                            <th style="width: 10%;padding: 0!important; height: 24px!important;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($your_picks!= null && count($your_picks) > 0) {
                            foreach ($your_picks as $row): ?>
                                <tr>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-left: 20px!important;"><?php echo $row['pick_id']; ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-right: 10px!important;">
                                        <span style="font-weight: 900;!important;"><?php echo $row['name']; ?></span><br>
                                        <?php echo $row['quote']; ?>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"><?php echo $row['created_at'] ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                            <ul class="dropdown-menu" id="customDropdown">
                                                <li class="text-uppercase view-user-click" data-pick_id="<?= $row['pick_id'] ?>" onclick="ShowEditPick(this)"><a href="#" class="drp-items"><span>Edit</span>
                                                        <img src="<?= base_url('assets/images/edit.svg') ?>" alt=""></a>
                                                </li>
                                                <li class="text-uppercase" onclick="DeleteShow(this)" data-pick_id="<?= $row['pick_id'] ?>">
                                                    <a class="drp-items"><span>Delete</span>
                                                        <img src="<?= base_url('assets/images/delete.svg') ?>" alt=""></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="watch-list" class="tab-pane fade in<?= $active == 'watch-list' ? ' active' : '' ?>">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped table-bordered"
                           data-delete-alert="Are you want to remove this series from you watch list?"
                           data-delete-confirm="Remove">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Series Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($watch_list != null && count($watch_list) > 0) {
                            foreach ($watch_list as $row): ?>
                                <tr>
                                    <td align="center"><?php echo $row['product_id']; ?></td>
                                    <td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo($row['status'] == 1 ? 'Enable' : ($row['status'] == 0 ? 'Disable' : 'Deleted')) ?></td>
                                    <td>
                                        <div class='button-list'>
                                            <button class="btn btn-danger btn-custom btn-xs sa-delete" type="button"
                                                    data-href="<?php echo redirect_url('user/removeWatch/' . $row['id'], ['active' => 'watch-list']) ?>">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="thumb-up" class="tab-pane fade in<?= $active == 'thumb-up' ? ' active' : '' ?>">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped table-bordered"
                           data-delete-alert="Are you want to remove this episode from your thumb up list?"
                           data-delete-confirm="Remove">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($thumbs_up != null && count($thumbs_up) > 0) {
                            foreach ($thumbs_up as $row): ?>
                                <tr>
                                    <td align="center"><?php echo $row['product_id']; ?></td>
                                    <td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo($row['status'] == 1 ? 'Enable' : ($row['status'] == 0 ? 'Disable' : 'Deleted')) ?></td>
                                    <td>
                                        <div class='button-list'>
                                            <button class="btn btn-danger btn-custom btn-xs sa-delete" type="button"
                                                    data-href="<?php echo redirect_url('user/removeProductLike/' . $row['id'], ['active' => 'thumb-up']) ?>">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>