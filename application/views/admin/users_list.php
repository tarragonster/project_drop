<div class="outer-user-live">
    <div class="sub-menu">
        <div>
            <span class="submenu-title">Users</span>
        </div>
        <div class="submenu-content">
            <div class="cover-submenu-item">
                <a href="<?php echo base_url('user'); ?>"
                   class="submenu-item text-uppercase <?php echo isset($sub_id) && $sub_id == 21 ? ' active' : ''; ?>">View
                    Users</a>
            </div>
            <div class="cover-submenu-item">
                <a href="<?php echo base_url('user/reports'); ?>"
                   class="submenu-item text-uppercase <?php echo isset($sub_id) && $sub_id == 23 ? ' active' : ''; ?>">Reported
                    Users</a>
            </div>
        </div>
    </div>

    <div class="not-menu">
        <form action="" method="get">
        <div class="search-container">
                <div class="outer-search dataTables_filter">
                    <i class="fa fa-search"></i>
                    <input name='key' value="<?php echo isset($conditions['key']) ? $conditions['key'] : '' ?>"
                           type="search" class="form-control input-sm" placeholder="Search Users"/>
                </div>
                <button type="submit" class="btn-nothing" name="cmd" value="nothing" style="display: none;">&nbsp;
                </button>
        </div>
        <div class="col-xs-12 list-content">
            <div class="box">
                <div class="card-box table-responsive">
                    <!--                    <div class="filter-container">-->
                    <!--                        <label>Status:</label>-->
                    <!--                        <select class="form-control status-user">-->
                    <!--                            <option value="2" selected>All</option>-->
                    <!--                            <option value="1">Active Users</option>-->
                    <!--                            <option value="0">Inactive Users</option>-->
                    <!--                        </select>-->
                    <!--                    </div>-->
                    <div id="user_table">
                        <?php echo isset($userContent) ? $userContent : 'Empty page'; ?>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<?php $this->load->view('admin/modals/modal_user') ?>