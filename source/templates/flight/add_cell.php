<td>
    <?php
        if (in_array($row['ID'], $this->data['tracked_flights']))
            echo '<a href=' . $this->generateURL('flight/delete', ['id'=>$row['ID']]) . '> UNTRACK </a>';
        else
            echo '<a href=' . $this->generateURL('flight/add', ['id'=>$row['ID']]) . '> TRACK </a>';
    ?>
</td>