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
        <?php if($isCreate == 'true'){ ?>
            <button  data-user_id = "<?php echo $user['user_id']; ?>" class="edit-btn" style="display: block">Create</button>
        <?php } ?>
    </div>
</div>
<ul class="nav nav-tabs">
    <li <?= $active == 'profile' ? 'class="active"' : '' ?> style="width: 97px;height: 35px" onclick="ShowTabProfile()">
        <a data-toggle="tab" href="#profile">Profile</a>
    </li>
    <li <?= $active == 'comments' ? 'class="active"' : '' ?> style="width: 120px;height: 35px" onclick="ShowTabComment()">
        <a data-toggle="tab" href="#comments">Comments</a>
    </li>
    <li <?= $active == 'your-picks' ? 'class="active"' : '' ?> style="width: 123px;height: 35px" onclick ="ShowTabPick()">
        <a data-toggle="tab" href="#your-picks">Your Picks</a>
    </li>
    <li <?= $active == 'watch-list' ? 'class="active"' : '' ?> style="width: 121px;height: 35px" onclick ="ShowTabWatch()">
        <a data-toggle="tab" href="#watch-list">Watch List</a>
    </li>
    <li <?= $active == 'thumb-up' ? 'class="active"' : '' ?> style="width: 139px;height: 35px" onclick ="ShowTabThumbsup()">
        <a data-toggle="tab" href="#thumb-up">Thumbs up</a>
    </li>
</ul>

<div class="outer-content">
    <div class="tab-content">
        <div id="profile" class="tab-pane fade in<?=  $active== 'profile' ? ' active' : '' ?>">
            <form action="" method='POST' class="outer-profile" id="form-update" enctype = 'multipart/form-data'>
                <div class="row inner-profile" style="margin: 0">
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
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <label class="cnt">
                                                    <input class="check-feature" type="checkbox" <?php echo $user['feature_id'] != ''? 'checked = "checked"': null ?>>
                                                    <span class="checkmark"></span>
                                                </label>
                                            <?php }else{ ?>
                                                <?php if($user['feature_id'] != ''){ ?>
                                                    <span>Yes</span>
                                                <?php }else{ ?>
                                                    <span>No</span>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;" class="first-column">Curator:</td>
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <label class="cnt">
                                                    <input class="check-curator" type="checkbox" <?php echo $user['user_type'] == 2 ? 'checked = "checked"': null ?>>
                                                    <span class="checkmark"></span>
                                                </label>
                                            <?php }else{ ?>
                                                <?php if($user['user_type'] == 2){ ?>
                                                    <span>Yes</span>
                                                <?php }else{ ?>
                                                    <span>No</span>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0!important;vertical-align: text-top;padding-top: 5px!important;" class="first-column">Bio:</td>
                                        <td class="next-column">
                                            <?php if($isEdit == 'true'){ ?>
                                                <div contenteditable class="form-control style-edit-input bio-input" onkeyup="FillInput(this)"><?php echo $user['bio']; ?></div>
                                                <input type="text" value="<?php echo $user['bio']; ?>" name="bio" style="display: none">
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
                                        <label for="upload-photo" class="fileTitle">Upload</label>
                                    </div>
                                    <input type="file" name='avatar' id="upload-photo" onchange="readURL(this)"/>
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
            <div class="row outer-comment" style="margin: 0">
                    <div class="modal-content group-popup outer-table-modal">
                        <table class="table dataTable table-hover table-modal">
                            <thead>
                            <tr>
                                <th style="width: 20%;padding: 0!important; height: 24px!important; padding-left: 10px!important;">Comment Id</th>
                                <th style="width: 30%;padding: 0!important; height: 24px!important;">Comment</th>
                                <th style="width: 18%;padding: 0!important; height: 24px!important;">Date</th>
                                <th style="width: 22%;padding: 0!important; height: 24px!important;">Status</th>
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
                                            <span style="font-weight: 600;!important;"><?php echo $row['film_name']; ?> - <?php echo $row['name']; ?></span><br>
                                            <?php echo $row['content']; ?>
                                        </td>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"><?php echo date('m/d/Y h:iA',$row['timestamp']) ?></td>
                                        <?php if(!empty($row['comment_reportId'])){ ?>
                                            <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                                <img src="<?= base_url('assets/imgs/red.svg') ?>" alt="green">&nbsp;<span>Reported</span>
                                            </td>
                                        <?php }else{ ?>
                                            <?php if($row['comment_status'] == 1){ ?>
                                                <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                                    <img src="<?= base_url('assets/imgs/green.svg') ?>" alt="green">&nbsp;<span>Active</span>
                                                </td>
                                            <?php }elseif($row['comment_status'] == 0){ ?>
                                                <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                                    <img src="<?= base_url('assets/imgs/dark.svg') ?>" alt="dark">&nbsp;<span>Inactive</span>
                                                </td>
                                            <?php } ?>
                                        <?php } ?>

                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                            <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                                <ul class="dropdown-menu" id="customDropdown">
                                                    <li class="text-uppercase" data-comment_id="<?= $row['comment_id'] ?>" onclick="ShowCommentReplies(this)"><a class="drp-items"><span>View</span><img
                                                                    src="<?= base_url('assets/images/view.svg') ?>" alt=""></a>
                                                    </li>
                                                    <li class="text-uppercase" data-comment_id="<?= $row['comment_id'] ?>" onclick="ShowRemoveComment(this)"><a  class="drp-items"><span>Delete</span><img
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
            <div class="row outer-pick" style="margin: 0">
                <div class="modal-content group-popup outer-table-modal">
                    <table class="table dataTable table-hover table-modal">
                        <thead>
                        <tr>
                            <th style="width: 20%;padding: 0!important; height: 24px!important; padding-left: 10px!important;">Story Id</th>
                            <th style="width: 30%;padding: 0!important; height: 24px!important;">Story Name</th>
                            <th style="width: 18%;padding: 0!important; height: 24px!important;">Date</th>
                            <th style="width: 22%;padding: 0!important; height: 24px!important;">Status</th>
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
                                        <span style="font-weight: 600;!important;"><?php echo $row['name']; ?></span><br>
                                        <?php echo $row['quote']; ?>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"><?php echo date('m/d/Y h:iA',$row['created_at']) ?></td>
                                    <?php if($row['up_status'] == 1){ ?>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                            <img src="<?= base_url('assets/imgs/green.svg') ?>" alt="green">&nbsp;<span>Active</span>
                                        </td>
                                    <?php }elseif($row['up_status'] == 0){ ?>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                            <img src="<?= base_url('assets/imgs/dark.svg') ?>" alt="dark">&nbsp;<span>Inactive</span>
                                        </td>
                                    <?php } ?>
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
            <div class="row outer-watch" style="margin: 0">
                <div class="modal-content group-popup outer-table-modal">
                    <table class="table dataTable table-hover table-modal">
                        <thead>
                        <tr>
                            <th style="width: 20%;padding: 0!important; height: 24px!important; padding-left: 10px!important;">Story Id</th>
                            <th style="width: 40%;padding: 0!important; height: 24px!important;">Picks</th>
                            <th style="width: 30%;padding: 0!important; height: 24px!important;">Date</th>
                            <th style="width: 10%;padding: 0!important; height: 24px!important;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($watch_list!= null && count($watch_list) > 0) {
                            foreach ($watch_list as $row): ?>
                                <tr>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-left: 20px!important;"><?php echo $row['id']; ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-right: 10px!important;">
                                        <span style="font-weight: 600;!important;"><?php echo $row['name']; ?></span><br>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;"><?php echo date('m/d/Y h:iA',$row['update_time']) ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                            <ul class="dropdown-menu" id="customDropdown">
                                                <li class="text-uppercase" onclick="DeleteShowWatch(this)" data-watch_id="<?= $row['id'] ?>">
                                                    <a class="drp-items"><span>Remove</span>
                                                        <img src="<?= base_url('assets/images/delete.svg') ?>" alt=""></a>
                                                </li>
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

        <div id="thumb-up" class="tab-pane fade in<?= $active == 'thumb-up' ? ' active' : '' ?>">
            <div class="row outer-like" style="margin: 0">
                <div class="modal-content group-popup outer-table-modal">
                    <table class="table dataTable table-hover table-modal">
                        <thead>
                        <tr>
                            <th style="width: 20%;padding: 0!important; height: 24px!important; padding-left: 10px!important;">Story Id</th>
                            <th style="width: 30%;padding: 0!important; height: 24px!important;">Story</th>
                            <th style="width: 20%;padding: 0!important; height: 24px!important;">Type</th>
                            <th style="width: 20%;padding: 0!important; height: 24px!important;">Date</th>
                            <th style="width: 20%;padding: 0!important; height: 24px!important;">Status</th>
                            <th style="width: 10%;padding: 0!important; height: 24px!important;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($like_product!= null && count($like_product) > 0) {
                            foreach ($like_product as $row): ?>
                                <tr>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-left: 20px!important;"><?php echo $row['product_id']; ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-right: 10px!important;">
                                        <span style="font-weight: 900;!important;"><?php echo $row['name']; ?></span>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Story</td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <?php echo date('m/d/Y h:iA',$row['added_at']) ?>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Thumps Up</td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                            <ul class="dropdown-menu" id="customDropdown">
                                                <li class="text-uppercase" onclick="DeleteShow(this)" data-pick_id="<?= $row['id'] ?>">
                                                    <a class="drp-items"><span>Delete</span>
                                                        <img src="<?= base_url('assets/images/delete.svg') ?>" alt=""></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        } ?>
                        <?php
                        if ($like_episode!= null && count($like_episode) > 0) {
                            foreach ($like_episode as $row): ?>
                                <tr>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-left: 20px!important;"><?php echo $row['product_id']; ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-right: 10px!important;">
                                        <span style="font-weight: 900;!important;"><?php echo $row['film_name']; ?> - <?php echo $row['episode_name']; ?></span>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Block</td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <?php echo date('m/d/Y h:iA',$row['added_at']) ?>
                                    </td>
                                    <?php if($row['status'] == 1){ ?>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Thumps Up</td>
                                    <?php }elseif($row['status'] == 0){ ?>
                                        <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Thumps Down</td>
                                    <?php } ?>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                            <ul class="dropdown-menu" id="customDropdown">
                                                <li class="text-uppercase" onclick="DeleteShow(this)" data-pick_id="<?= $row['el_id'] ?>">
                                                    <a class="drp-items"><span>Delete</span>
                                                        <img src="<?= base_url('assets/images/delete.svg') ?>" alt=""></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        } ?>
                        <?php
                        if ($like_comment!= null && count($like_comment) > 0) {
                            foreach ($like_comment as $row): ?>
                                <tr>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-left: 20px!important;"><?php echo $row['product_id']; ?></td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;padding-right: 10px!important;">
                                        <span style="font-weight: 900;!important;"><?php echo $row['film_name']; ?> - <?php echo $row['episode_name']; ?></span>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Comment</td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <?php echo date('m/d/Y h:iA',$row['added_at']) ?>
                                    </td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">Thumps Up</td>
                                    <td class="header-item-content item-style modal-items" style="padding: 0!important;height: 50px!important;">
                                        <div class="dropdown">
                                                <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
                                            <ul class="dropdown-menu" id="customDropdown">
                                                <li class="text-uppercase" onclick="DeleteShow(this)" data-pick_id="<?= $row['cl_id'] ?>">
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
    </div>
</div>