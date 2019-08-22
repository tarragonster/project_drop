<div class="section-container fixed-height-container">
    <div class="section-menu">
        <h4>Story</h4>
        <ul>
            <li <?= $page_index == 'product_edit' ? 'class="active"' : ''; ?>>
                <a href="<?php echo base_url('product/edit/' . $this->session->userdata('product_id'))?>">Manage Story</a>
            </li>
            <li <?= ($page_index == 'manage_season') || ($page_index == 'create_season') || ($page_index == 'empty_episode') ? 'class="active"' : ''; ?>>
                <a href="<?php echo base_url('product/manageSeason/' . $this->session->userdata('product_id'))?>">Manage Season</a>
            </li>
            <li <?= ($page_index == 'manage_review') || ($page_index == 'empty_review') ? 'class="active"' : ''; ?>>
                <a href="<?php echo base_url('product/manageReview/' . $this->session->userdata('product_id'))?>">Reviews</a>
            </li>
        </ul>
    </div>
    <div class="section-content">
        <div class="tab-content">
        <?php
            $this->load->view('admin/products/sub_page/' . $page_index);
        ?>
        </div>
    </div>
    <div class="clear-both"></div>
</div>
