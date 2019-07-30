        <div class="section-container fixed-height-container">
            <div class="section-menu">
                <h4>Settings</h4>
                <ul>
                    <li <?= $page_index == 'general_settings' ? 'class="active"' : ''; ?>><a href="<?= base_url('setting'); ?>">General</a></li>
                    <li <?= ($page_index == 'genre_settings') || ($page_index == 'empty_genre') ? 'class="active"' : ''; ?>><a href="<?= base_url('genre'); ?>">Genres</a></li>
                </ul>
            </div>
            <div class="section-content">
                <div class="tab-content">
                <?php
                    $this->load->view('admin/setting/sub_page/'.$page_index);
                ?>
                </div>
            </div>
            <div class="clear-both"></div>
        </div>
