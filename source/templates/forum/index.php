<form class="flight-data" method="post" id="flight_form" action="">
    <label for="airline">Enter Airline</label>
    <input type="text" id="airline" name="airline" value="<?php echo $_POST['airline'] ?? ''; ?>">

    <button type="submit">Submit</button>
</form>

<?php
    if ($this->data['airline_found'] > 0)
        require TEMPLATES_PATH . 'forum/data.php';
    else if (isset($this->data['airline_found']) && ($this->data['airline_found'] == 0))
        require TEMPLATES_PATH . 'forum/no_data.php';
?>