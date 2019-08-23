<table id="example3 " class="table dataTable table-hover">
    <?php $this->load->view('admin/users/table_header') ?>
    <tbody>
    <?php
    if (isset($users) && is_array($users)) {
        foreach ($users as $key => $row):
            ?>
            <tr>
                <td class="header-item-content item-style">
                    <div style="width: 50px;height: 50px;">
                        <img style="width: 100%; height: 100%;border-radius: 29.5px;"
                             src="<?= media_thumbnail($row['avatar'], 70) ?>"/>
                    </div>
                    <?php if($row['user_type'] == 1){ ?>
                        <span class="outer-tag"><img src="<?= base_url('assets/imgs/Verify.svg') ?>" alt=""></span>
                    <?php } ?>
                    <?php if($row['user_type'] == 2){ ?>
                        <span class="outer-tag"><img src="<?= base_url('assets/imgs/Btag-orange.svg') ?>" alt=""></span>
                    <?php } ?>
                </td>
                <td class="header-item-content item-style"><?php echo $row['user_id'] ?></td>
                <td class="header-item-content item-style" style="font-weight: 600;">
                    <?php echo $row['full_name'] ?><br>
                    <span style="font-weight: 500!important;">@<?php echo $row['user_name'] ?></span>
                </td>
                <td class="header-item-content item-style"><?php echo $row['email'] ?></td>
                <td class="header-item-content item-style">Comments:&nbsp;<?php echo $row['total_comment'] ?> <br>
                    Thumbs&nbsp;up:&nbsp;<?php echo $row['sum_like'] ?> <br>
                    Picks:&nbsp;<?php echo $row['total_pick'] ?>
                </td>

                <td class="header-item-content item-style">
                    <?php if (!empty($row['device_os'])): ?>
	                    <span><?= $row['device_os'] == 'android' ? 'Android' : 'iOS' ?> - <?= $row['app_version'] ?></span>
                    <?php endif; ?>
                </td>


                <td class="header-item-content item-style"><?php echo date('m/d/Y h:iA', $row['joined']) ?></td>
                <?php if($row['user_status'] == 1){ ?>
                    <td class="header-item-content item-style status-tb"><img src="<?= base_url('assets/imgs/green.svg') ?>" alt="green">&nbsp;<span class="text-uppercase">Enabled</span></td>
                <?php }elseif($row['user_status'] == 0){ ?>
                    <td class="header-item-content item-style status-tb"><img src="<?= base_url('assets/imgs/red.svg') ?>" alt="red">&nbsp;<span class="text-uppercase">Disabled</span></td>
                <?php } ?>
                <td class="header-item-content item-style">
                    <div class="dropdown">
                        <span class="btnAction dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-ellipsis-h"
                                                                                           style="color: #d8d8d8"></i></span>
                        <ul class="dropdown-menu" id="customDropdown">
                            <li class="text-uppercase view-user-click" data-user_id="<?= $row['user_id'] ?>" onclick="ShowUserProfile(this)"><a href="#" class="drp-items"><span>View</span><img
                                            src="<?= base_url('assets/images/view.svg') ?>" alt=""></a>
                            </li>
                            <?php if($row['user_type'] == 1){ ?>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="RemoveTag(this)"><a class="drp-items"><span>Remove</span><img
                                                src="<?= base_url('assets/images/addTrue.svg') ?>" alt=""></a></li>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="AddCurator(this)"><a class="drp-items"><span>Add</span><img
                                                src="<?= base_url('assets/images/addB.svg') ?>" alt=""></a></li>
                            <?php }elseif($row['user_type'] == 2){ ?>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="AddVerify(this)"><a class="drp-items"><span>Add</span><img
                                                src="<?= base_url('assets/images/addTrue.svg') ?>" alt=""></a></li>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="RemoveTag(this)"><a class="drp-items"><span>Remove</span><img
                                                src="<?= base_url('assets/images/addB.svg') ?>" alt=""></a></li>
                            <?php }else{ ?>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="AddVerify(this)"><a class="drp-items"><span>Add</span><img
                                                src="<?= base_url('assets/images/addTrue.svg') ?>" alt=""></a></li>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="AddCurator(this)"><a class="drp-items"><span>Add</span><img
                                                src="<?= base_url('assets/images/addB.svg') ?>" alt=""></a></li>
                            <?php } ?>

                            <?php if ($row['status'] == 1): ?>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="ShowBlockUser(this)"><a class="drp-items"><span>Disable</span><img
                                                src="<?= base_url('assets/images/block.svg') ?>" alt=""></a></li>
                            <?php else: ?>
                                <li class="text-uppercase" data-user_id="<?= $row['user_id'] ?>" onclick="ShowUnblockUser(this)"><a class="drp-items"><span>Enable</span><img
                                                src="<?= base_url('assets/images/block.svg') ?>" alt=""></a></li>
                            <?php endif; ?>
                            <li class="text-uppercase <?php echo $row['user_status'] == '2' ? "dis-btn" : ""; ?>" data-user_id="<?= $row['user_id'] ?>" onclick="ShowFirstDeleteModal(this)"><a href="#" class="drp-items"><span>Delete</span><img
                                            src="<?= base_url('assets/images/delete.svg') ?>" alt=""></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php
        endforeach;
    }
    ?>
</table>
<!--pagination-->

<?php
$has_items = isset($paging) && $paging['total'] > 0;
$dropdown_size = $has_items && isset($paging['dropdown-size']) ? $paging['dropdown-size'] - 25 : '40';
?>
<div class="row" style="padding: 0 20px;padding-top: 10px; margin: 0; background: white; border-bottom-left-radius: 6px;border-bottom-right-radius: 6px;height: 70px;">
    <?php if (isset($paging)) : ?>
        <div class="col-xs-4">
            <?php
            $per_page = isset($conditions['per_page']) ? $conditions['per_page'] * 1 : 0;
            ?>
            <div class="dataTables_info" id="table-driver_info" role="status" aria-live="polite">
                <?php if ($paging['total'] > 0) : ?>
                    Showing <?= $paging['from'] ?> to <?= $paging['to'] ?> of <?= $paging['total'] ?> items
                <?php else: ?>
                    No Results
                <?php endif; ?>
            </div>
        </div>
        <div class="col-xs-8">
            <?php if ($has_items): ?>
                <div class="dataTables_paginate paging_bootstrap" style="float: right">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            <?php endif; ?>
            <div class="per_page m-r-15" style="float: right; margin-top: 2px; margin-left: 30px">
                <label>
                    <select name="per_page" class="form-control input-sm">
                        <option value="25"<?php echo $per_page == 25 ? ' selected' : '' ?>>25</option>
                        <option value="50"<?php echo $per_page == 50 ? ' selected' : '' ?>>50</option>
                    </select> &nbsp;
                    Items per page
                </label>
            </div>
        </div>
    <?php endif; ?>
</div>