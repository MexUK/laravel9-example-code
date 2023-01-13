<?php

use App\Models\Vehicle;

echo 'Vehicle IDs: '.implode(', ', array_map(function(Vehicle $vehicle):int
{
    return $vehicle->vehicleId;
}, $vehicles->all())).'.';
echo '<br><br>';

echo 'Vehicle Model IDs and Counts:<br>';
foreach($vehicleModelCounts as &$row)
{
    echo ($row->vehicleModel <= 100 ? $row->model->id->value : $row->vehicleModel).' ('.$row->vehicleModelCount.')<br>';
}