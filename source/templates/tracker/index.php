<!------- Service-like flight statistics -------->
<section class="statistics">
    <div class="row">
        <div class="service-col">
            <h1><?php echo $this->data['n_tracked_flights']?></h1>
            <h2> 
                tracked flights
            </h2>
        </div>
    </div>
</section>

<?php 
    if ($this->data['n_tracked_flights'] > 0)
        require TEMPLATES_PATH . 'tracker/data.php';
    else if (isset($this->data['n_tracked_flights']))
        require TEMPLATES_PATH . 'tracker/no_data.php';
?>

<!-- 
<form action="/action_page.php" method="get" id="form1">
<label for="fname">First name:</label>
<input type="text" id="fname" name="fname"><br><br>
<label for="lname">Last name:</label>
<input type="text" id="lname" name="lname">
</form>

<button type="submit" form="form1" value="Submit">Submit</button> -->