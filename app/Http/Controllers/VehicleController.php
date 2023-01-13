<?php

namespace App\Http\Controllers;

use App\Models\Vec3;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
	// create/destroy
	public function createOne(Request $request)
	{
		$modelId = (int) $request->input('modelId');
		$position = new Vec3(
			(float) $request->input('posX'),
			(float) $request->input('posY'),
			(float) $request->input('posZ')
		);
		$rotation = new Vec3(
			0.0,
			0.0,
			(float) $request->input('heading')
		);

		$vehicle = Vehicle::createVehicle($modelId, $position, $rotation);
		if(!$vehicle)
			return view('vehicle.create.invalid');
		
		return view('vehicle.create.success', [
			'vehicle' => $vehicle
		]);
	}

	public function destroyOne(Request $request)
	{
		$vehicleId = (int) $request->input('vehicleId');
		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.destroy.invalid');
		
		if(Vehicle::destroyVehicle($vehicleId))
			return view('vehicle.destroy.success');
		else
			return view('vehicle.destroy.failed');
	}

	public function showAll():View
	{
		$vehicle = new Vehicle();
		$vehicles = $vehicle->get();
		$vehicleModelCounts = $vehicle->getModelCounts();

		return view('vehicle.show.all', [
			'vehicles' => $vehicles,
			'vehicleModelCounts' => $vehicleModelCounts
		]);
	}

	public function showOne(string $vehicleId):View
	{
		$vehicleId = (int) $vehicleId;

		$vehicle = Vehicle::getVehicle($vehicleId);
		if(!$vehicle)
			return view('vehicle.show.one-invalid');

		return view('vehicle.show.one', [
			'vehicle' => $vehicle
		]);
	}
};

