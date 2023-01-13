<?php

use App\Models\EVehicleLockType;

echo 'Showing vehicle with ID '.$vehicle->vehicleId.'.<br><br>';

echo 'Lock Type: '.($vehicle->lock == EVehicleLockType::Locked ? 'Locked' : 'Unlocked').'.<br>';

