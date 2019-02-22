<?php
    if ($comments != null && count($comments) > 0) {
        foreach ($comments as $row) {
            echo '<tr>';
            echo '<td align="center">' . $row['comment_id'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['content'] . '</td>';
            echo '<td>'. timeFormat($row['timestamp']) . '</td>';
            echo "<td><div class='button-list'>";
            if($row['num_rep'] == 0){
                echo "<button class='btn btn-inverse btn-custom btn-xs'>".$row['num_rep']." Replies</button>";
            }else{
                echo "<button id='".$row['comment_id']."' class='btn btn-comment btn-inverse btn-custom btn-xs'>".$row['num_rep']." Replies</button>";
            }
            echo '<span class="sa-warning sa-comment" data-href="'.base_url("/comment/delete/" . $row['comment_id']).'"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>';
            echo "</div></td>";
            echo '</tr>';
        }
    }else{
        echo '<tr><td colspan="5">No result</td></tr>';
    }
?>