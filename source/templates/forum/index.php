<form method="post" id="flight_form" action="">
    <label for="airline">Enter Airline</label>
    <input type="text" id="airline" name="airline" value="<?php echo $_POST['airline'] ?? ''; ?>">

    <div class="button">
        <button type="submit">Submit</button>
    </div>
</form>

<?php 
    if ($this->data['airline_found'] > 0)
        require TEMPLATES_PATH . 'forum/data.php';
    else 
        require TEMPLATES_PATH . 'forum/no_data.php';
?>