<div class="outer-comment-live">
    <div class="sub-menu">
        <div>
            <span class="submenu-title">Comments</span>
        </div>
        <div class="submenu-content">
            <div class="cover-submenu-item">
                <a href="<?php echo base_url('comment/stories'); ?>"
                   class="submenu-item text-uppercase <?php echo isset($sub_id) && $sub_id == 91 ? ' active-mnu' : ''; ?>">Comments</a>
            </div>
            <div class="cover-submenu-item">
                <a href="<?php echo base_url('comment/reports'); ?>"
                   class="submenu-item text-uppercase <?php echo isset($sub_id) && $sub_id == 92 ? ' active-mnu' : ''; ?>">Reported</a>
            </div>
        </div>
    </div>
    <div class="not-menu">
        <div class="to-back">
            <form action="" method="get">
                <div class="col-xs-12 list-content style-content-block">
                    <div class="box">
                        <div class="card-box table-responsive">
                            <div id="comment_table">
                                <table id="example3 " class="table dataTable table-hover table-block">
                                    <?php $this->load->view('admin/comments/table_header_report.php') ?>
                                    <tbody>
                                    <?php
                                    if (isset($reports) && is_array($reports)) {
                                        foreach ($reports as $key => $row):
                                            ?>
                                            <tr>
                                                <td class="header-item-content item-style"> <span style="margin-left: 14px"><?php echo $row['report_id'] ?></span></td>
                                                <td class="header-item-content item-style">
                                                    <span style="font-weight: 600"><?php echo $row['reported_name'] ?></span><br>
                                                    <span>@<?php echo $row['reported_short'] ?></span>
                                                </td>

                                                <td class="header-item-content item-style"><?php echo $row['comment_content'] ?></td>
                                                <td class="header-item-content item-style">
                                                    <span style="font-weight: 600"><?php echo $row['reporter_name'] ?></span><br>
                                                    <span>@<?php echo $row['reporter_short'] ?></span>
                                                </td>
                                                <td class="header-item-content item-style"><?php echo date('m/d/Y h:iA', $row['created_at'])  ?></td>
                                                <?php if ($row['status'] == 'pending') { ?>
                                                    <td class="header-item-content item-style status-tb">
                                                        <img src="<?= base_url('assets/imgs/warning.svg') ?>" alt="orange">&nbsp;
                                                        <span class="text-uppercase">Pending</span>
                                                    </td>
                                                <?php } elseif ($row['status'] == 'disable') { ?>
                                                    <td class="header-item-content item-style status-tb">
                                                        <img src="<?= base_url('assets/imgs/red.svg') ?>" alt="red">&nbsp;
                                                        <span class="text-uppercase">Comment Disabled</span>
                                                    </td>
                                                <?php }elseif ($row['status'] == 'rejected'){ ?>
                                                    <td class="header-item-content item-style status-tb">
                                                        <img src="<?= base_url('assets/imgs/green.svg') ?>" alt="green">&nbsp;
                                                        <span class="text-uppercase">Report Removed</span>
                                                    </td>
                                                <?php }elseif ($row['status'] == 'deleted'){ ?>
                                                    <td class="header-item-content item-style status-tb">
                                                        <img src="<?= base_url('assets/imgs/dark.svg') ?>" alt="dark">&nbsp;
                                                        <span class="text-uppercase">Comment Deleted</span>
                                                    </td>
                                                <?php } ?>
                                                <td class="header-item-content item-style">
                                                    <div class="dropdown">
                                                    <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i>
                                                    </span>
                                                        <ul class="dropdown-menu" id="customDropdown">
                                                            <?php if(empty($row['content'])){ ?>
                                                                <li class="text-uppercase view-user-click"
                                                                    data-report_id="<?= $row['report_id'] ?>" onclick="ShowNoteReportComment(this)"><a
                                                                            class="drp-items"><span>
                                                                Add Notes
                                                            </span></a>
                                                                </li>
                                                            <?php }else{ ?>
                                                                <li class="text-uppercase view-user-click"
                                                                    data-report_id="<?= $row['report_id'] ?>" onclick="ShowConfirmReportComments(this)"><a
                                                                            class="drp-items"><span>
                                                                View Notes
                                                            </span></a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if($row['status'] == 'pending' || $row['status'] == 'rejected' || $row['status'] == 'deleted'){ ?>
                                                                <li class="text-uppercase <?php echo $row['status'] == 'deleted' ? "dis-btn" : ""; ?>"
                                                                    data-report_id="<?= $row['report_id'] ?>" onclick="ShowDisableCommentReported(this)">
                                                                    <a class="drp-items"><span>
                                                                Disable Comment
                                                            </span></a>
                                                                </li>
                                                            <?php }else{ ?>
                                                                <li class="text-uppercase"
                                                                    data-report_id="<?= $row['report_id'] ?>" onclick="ShowEnableCommentReported(this)">
                                                                    <a class="drp-items"><span>
                                                                Enable Comment
                                                            </span></a>
                                                                </li>
                                                            <?php } ?>
                                                            <li class="text-uppercase
                                                                <?php echo $row['status'] == 'rejected' ? "dis-btn" : ""; ?>
                                                                <?php echo $row['status'] == 'deleted' ? "dis-btn" : ""; ?>"
                                                                data-report_id="<?= $row['report_id'] ?>" onclick="ShowRemoveReportedComment(this)">
                                                                <a class="drp-items"><span>
                                                                Remove Report
                                                            </span></a>
                                                            </li>
                                                            <li class="text-uppercase <?php echo $row['status'] == 'deleted' ? "dis-btn" : ""; ?>"
                                                                data-report_id="<?= $row['report_id'] ?>" onclick="ShowFirstDeleteReportedComment(this)"><a
                                                                        class="drp-items"><span>
                                                                Delete Comment</span></a>
                                                            </li>
                                                            <li class="text-uppercase"
                                                                data-user_id="<?= $row['user_id'] ?>" onclick="ShowReportedCommentUser(this)">
                                                                <a class="drp-items"><span>
                                                                View User
                                                            </span></a>
                                                            </li>
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
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('admin/modals/modal_comment') ?>
<?php $this->load->view('admin/modals/modal_user') ?>
