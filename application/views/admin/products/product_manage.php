<div class="section-container fixed-height-container">
    <div class="section-menu">
        <h4>Story</h4>
        <ul>
            <li <?= $page_index == 'product_edit' ? 'class="active"' : ''; ?>>
                <a href="<?= base_url('product/edit/' . $product['product_id']); ?>">Manage Story</a>
            </li>
            <li <?= $page_index == 'genre_settings' ? 'class="active"' : ''; ?>>
                <a href="<?= base_url('genre'); ?>">Genres</a>
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
