<div class="flight_data_page">
    <h1> Found <?= $this->data['n_filtered_flights']?> flights <h1>
    
    <?php 
        if (!$this->data['pages']['one'])
            require TEMPLATES_PATH . 'flight/pages.php';
    ?>
</div>

<table id="flight_data">
<tr>
    <?php foreach ($this->data['flights'][0] as $k => $v): ?>
        <th><?= $k ?></th>
    <?php endforeach ?>

    <?php
        if (isset($_SESSION['logged_user']))
            echo "<th> TRACK </th>";
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
        if (isset($_SESSION['logged_user']))
            require TEMPLATES_PATH . 'flight/add_cell.php';
    ?>

    </tr>
<?php endforeach ?>
</table>