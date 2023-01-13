<?php

use App\Models\EVehicleLockType;

echo 'Showing vehicle with ID '.$vehicle->vehicleId.'.<br><br>';

echo 'Lock Type: '.($vehicle->getLockType() == EVehicleLockType::Locked ? 'Locked' : 'Unlocked').'.<br>';

