<!------- Service-like airline statistics -------->
<section class="statistics">
    <div class="row">
        <div class="service-col">
            <h1><?php echo $this->data['num_flights']?></h1>
            <h2> 
                registered flights
            </h2>
        </div>
        <div class="service-col">
            <h1><?php echo $this->data['num_late']?></h1>
            <h2> 
                late flights
            </h2>
        </div>
        <div class="service-col">
            <h1><?php echo $this->data['num_comments']?></h1>
            <h2> 
                comments
            </h2>
        </div>
        <div class="service-col">
            <h1><?php 
                if (isset($this->data['avg_stars'])) 
                    echo $this->data['avg_stars'];
                else 
                    echo 'No rating';?></h1>
            <h2> 
                average rating
            </h2>
        </div>
    </div>
</section>
