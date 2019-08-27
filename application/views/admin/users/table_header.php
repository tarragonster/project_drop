<thead style="background: #ebeff2" class="head-deal">
<tr>
    <?php
    $sort_by = isset($conditions['sort_by']) ? $conditions['sort_by'] : '';
    $inverse = isset($conditions['inverse']) && $conditions['inverse'] > 0 ? 1 : 0;

    foreach ($headers as $key => $row) {
        if (is_array($row) && isset($row['sorting']) && $row['sorting']) {
            $class = '';
            $url = '';
            $conditions['sort_by'] = $key;
            if ($key == $sort_by) {
                if ($inverse == 1) {
                    unset($conditions['inverse']);
                    $class = 'sorting_desc';
                } else {
                    $conditions['inverse'] = 1;
                    $class = 'sorting_asc';
                }
            } else {
                $conditions['inverse'] = 1;
                $class = 'sorting';
            }
            $url = base_url('user') . '?' . http_build_query($conditions);
            echo '<th th-header-url="' . $url . '" class="'. ShowHeaderFAQ($row['label']). ' th-header ' . $class.'"><span style="margin-left: 14px;">'. $row['label'] . '</span></th>';
        } else
            echo '<th class="noSortHeader'.$row['label'] .' "><span style="margin-left: 18px;">'. (is_array($row) ? $row['label'] : $row) . '</span></th>';
    }
    ?>
</tr>
</thead>