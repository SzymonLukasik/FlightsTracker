<script>
    $(document).ready(function() {
        $("#<?php echo $item ?>").autocomplete({
            source: function(request, response) {
                $.getJSON(
                    "/assets/flight_autocomplete.php",
                    {
                        field: "<?php echo $field ?>",
                        airport: "<?php echo $airport ?>",
                        term: request.term
                    }, 
                    response
                );
            },
            minLength: 1,
        });
    });
</script>
