<?php

function calculateFare($distance, $baseFare = 3.00, $perKmRate = 1.50) {
    $fare = $baseFare + ($distance * $perKmRate);
    return round($fare, 2);
}

?>