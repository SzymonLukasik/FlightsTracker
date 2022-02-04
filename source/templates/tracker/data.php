<table id="flight_data">
<tr>
    <?php foreach ($this->data['flights'][0] as $k => $v): ?>
        <th><?= $k ?></th>
    <?php endforeach ?>

    <?php
        echo "<th> UNTRACK </th>";
    ?> 

</tr>    
<?php foreach ($this->data['flights'] as $row): ?>
    <tr>
    <?php foreach ($row as $k => $v): ?>
        <td>
            <?php
                if ($k == 'ID') 
                    echo '<a href=' . $this->generateURL('flight/show', ['id'=>$v]) . '>' . $v . '</a>';
                else 
                    echo $v;
            ?>
        </td>
    <?php endforeach ?>

    <?php
        require TEMPLATES_PATH . 'tracker/add_cell.php';
    ?>

    </tr>
<?php endforeach ?>
</table>