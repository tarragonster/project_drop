<thead>
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
			$url = base_url($page_base) . '?' . http_build_query($conditions);
			echo '<th th-header-url="' . $url . '" class="'. ShowHeaderFAQ($row['label']). ' th-header ' . $class.'" >'. $row['label'] . '</th>';
		} else
			echo '<th>' . (is_array($row) ? $row['label'] : $row) . '</th>';
	}
	?>
</tr>
</thead>