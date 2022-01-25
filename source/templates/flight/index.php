<!------- Service-like flights statistics -------->
<section class="service">
    <div class="row">
        <div onclick="location.href='';" style="cursor: pointer;" class="service-col">
            <h2><?php echo $this->data['n_flights']?></h2>
            <h3> 
                registered flights
            </h3>
        </div>
        <div onclick="location.href='';" style="cursor: pointer;" class="service-col">
            <h2><?php echo $this->data['n_airports']?></h2>
            <h3> 
                airports gathered 
            </h3>
        </div>
        <div onclick="location.href='';" style="cursor: pointer;" class="service-col">
            <h2><?php echo $this->data['n_countries']?></h2>
            <h3> 
                countries covered
            </h3>
        </div>
    </div>
</section>
