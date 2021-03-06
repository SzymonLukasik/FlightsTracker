<!------- Service-like flight statistics -------->
<section class="statistics">
    <div class="row">
        <div class="service-col">
            <h1><?php echo $this->data['n_flights']?></h1>
            <h2> 
                registered flights
            </h2>
        </div>
        <div class="service-col">
            <h1><?php echo $this->data['n_airports']?></h1>
            <h2> 
                airports gathered 
            </h2>
        </div>
        <div class="service-col">
            <h1><?php echo $this->data['n_countries']?></h1>
            <h2> 
                countries covered
            </h2>
        </div>
    </div>
</section>

<?php
    if (isset($_SESSION['flight_form_post_data']))
        $_POST = $_SESSION['flight_form_post_data'];
?>


<form class="flight-data" method="post" id="flight_form" action="">
    <label for="dep_country">Dep Country: </label>
    <input type="text" id="dep_country" name="dep_country" value="<?php echo $_POST['dep_country'] ?? ''; ?>">

    <label for="dep_city">Dep City: </label>
    <input type="text" id="dep_city" name="dep_city" value="<?php echo $_POST['dep_city'] ?? ''; ?>">

    <label for="des_country">Des Country: </label>
    <input type="text" id="des_country" name="des_country" value="<?php echo $_POST['des_country'] ?? ''; ?>">

    <label for="des_city">Des City: </label>
    <input type="text" id="des_city" name="des_city" value="<?php echo $_POST['des_city'] ?? ''; ?>">
    
    <label for="departure_after"> Dep After </label>
    <input type="datetime-local" id="departure_after" name="departure_after" value="<?php echo $_POST['departure_after'] ?? ''; ?>">

    <button type="submit">Filter</button>
</form>

<?php 
    if ($this->data['n_filtered_flights'] > 0)
        require TEMPLATES_PATH . 'flight/data.php';
    else if (isset($this->data['n_filtered_flights']))
        require TEMPLATES_PATH . 'flight/no_data.php';
?>

<!-- 
<form action="/action_page.php" method="get" id="form1">
<label for="fname">First name:</label>
<input type="text" id="fname" name="fname"><br><br>
<label for="lname">Last name:</label>
<input type="text" id="lname" name="lname">
</form>

<button type="submit" form="form1" value="Submit">Submit</button> -->