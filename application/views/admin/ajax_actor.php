<?php
    if ($others != null && count($others) > 0) {
        foreach ($others as $row) {
            echo '<tr>';
            echo '<td align="center">' . $row['cast_id'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td><img style="max-width: 70px; max-height: 70px" src="'.media_thumbnail($row['image'], 70).'"/></td>';
            echo '<td>' . $row['country'] . '</td>';
            echo "<td><div class='button-list'>";
            echo "<a href='" . base_url('product/addActor/' . $row['cast_id'].'/'.$product_id) . "'><button class='btn btn-inverse btn-custom btn-xs'>Add</button></a>";
            echo "</div></td>";
            echo '</tr>';
        }
    }
?>