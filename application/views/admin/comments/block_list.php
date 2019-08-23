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
                                <div class="backProduct-title">
                                    <div class="backProduct-outer">
                                        <a class="backProduct-btn" href="<?php echo base_url('comment/stories'); ?>">< Back to Stories</a>
                                    </div>
                                    <span class="tag-product"><?php echo $title[0]['name'] ?></span>
                                </div>
                                <table id="example3 " class="table dataTable table-hover table-block">
                                    <?php $this->load->view('admin/comments/table_header_block') ?>
                                    <tbody>
                                    <?php
                                    if (isset($blocks) && is_array($blocks)) {
                                        foreach ($blocks as $key => $row):
                                            ?>
                                            <tr>
                                                <td class="header-item-content item-style"> <span style="margin-left: 14px"><?php echo $row['episode_id'] ?></span></td>
                                                <td class="header-item-content item-style"><?php echo $row['position'] ?></td>

                                                <td class="header-item-content item-style"><?php echo $row['ep_name'] ?></td>
                                                <td class="header-item-content item-style"><?php echo $row['total_comments']  ?></td>

                                                <?php if ($row['e_status'] == 1) { ?>
                                                    <td class="header-item-content item-style status-tb" style="font-weight: 600!important;"><img
                                                                src="<?= base_url('assets/imgs/green.svg') ?>" alt="green">&nbsp;<span
                                                                class="text-uppercase">Enabled</span></td>
                                                <?php } elseif ($row['e_status'] == 0) { ?>
                                                    <td class="header-item-content item-style status-tb" style="font-weight: 600!important;"><img
                                                                src="<?= base_url('assets/imgs/red.svg') ?>" alt="red">&nbsp;<span
                                                                class="text-uppercase">Disabled</span></td>
                                                <?php } ?>
                                                <td class="header-item-content item-style">
                                                    <div class="dropdown">
                                                    <span class="btnAction dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i>
                                                    </span>
                                                        <ul class="dropdown-menu" id="customDropdown">
                                                            <li class="text-uppercase view-comment-click"
                                                                data-user_id="<?= $row['product_id']  ?>"
                                                                onclick=""><a href="<?php echo base_url('comment/comments/'. $row['episode_id']); ?>" class="drp-items"><span>View Comments</span></a>
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