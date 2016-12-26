<?php
    if ($others != null && count($others) > 0) {
        foreach ($others as $row) {
            echo '<tr>';
            echo '<td align="center">' . $row['product_id'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td><img style="max-width: 70px; max-height: 70px" src="'.base_url($row['image']).'"/></td>';
            echo '<td>' . $row['publish_year'] . '</td>';
            echo "<td><div class='button-list'>";
            echo "<a href='" . base_url('admin/feed/add/' . $row['product_id']) . "' ><button class='btn btn-inverse btn-custom btn-xs'>Add</button></a>";
            echo "</div></td>";
            echo '</tr>';
        }
    }
    ?>