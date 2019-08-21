<div class="section-container fixed-height-container">
    <div class="section-menu">
        <h4>Explore</h4>
        <ul>
            <li <?= ($page_index == 'featured_user') || ($page_index == 'empty_featured_users') ? 'class="active"' : ''; ?>><a href="<?= base_url('explore'); ?>">Featured users</a></li>
            <li <?= ($page_index == 'preview_list') || ($page_index == 'empty_preview') ? 'class="active"' : ''; ?>><a href="<?= base_url('explore/managePreviews'); ?>">Preview list</a></li>
        </ul>
    </div>
    <div class="section-content">
        <div class="tab-content">
        <?php
            $this->load->view('admin/explore/sub_page/' . $page_index);
        ?>
        </div>
    </div>
    <div class="clear-both"></div>
</div>
