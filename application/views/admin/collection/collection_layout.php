<div class="section-container fixed-height-container">
    <div class="section-menu">
        <h4>Collections</h4>
        <ul>
            <li <?= ($page_index == 'collection_carousel') || ($page_index == 'empty_carousel') ? 'class="active"' : ''; ?>>
	            <a href="<?= base_url('collection/carousel'); ?>">Carousel</a>
            </li>
            <li <?= ($page_index == 'collection_trending') || ($page_index == 'empty_trending') ? 'class="active"' : ''; ?>>
	            <a href="<?= base_url('collection/trending'); ?>">Trending</a>
            </li>
        </ul>
    </div>
    <div class="section-content">
        <div class="tab-content">
        <?php
            $this->load->view('admin/collection/sub_page/' . $page_index);
        ?>
        </div>
    </div>
    <div class="clear-both"></div>
</div>
