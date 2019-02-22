<?php
    if ($replies != null && count($replies) > 0) {
        foreach ($replies as $row) {
            echo '<tr>';
            echo '<td align="center">' . $row['replies_id'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['content'] . '</td>';
            echo '<td>'. timeFormat($row['timestamp']) . '</td>';
            echo "<td><div class='button-list'>";
            echo '<span class="sa-warning sa-replies" data-href="'.base_url("/comment/deleteReplies/" . $row['replies_id']).'"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>';
            echo "</div></td>";
            echo '</tr>';
        }
    }else{
        echo '<tr><td colspan="5">No result</td></tr>';
    }
?>