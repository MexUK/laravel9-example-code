<?php

namespace App\Http\Controllers;

use App\Models\Vec3;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class VehicleController extends Controller
{
	// create/destroy
	public function createOne(Request $request):View
	{
		$validator = Validator::make($request->all(), [
			'modelId' => 'required|int:100,150',
			'posX' => 'required|numeric|between:-20000.0,20000.0',
			'posY' => 'required|numeric|between:-20000.0,20000.0',
			'posZ' => 'required|numeric|between:-20000.0,20000.0',
			'heading' => 'required|numeric|between:-'.pi().','.pi()
		], [
			'name.required' => 'Model ID is required.'
		]);
		if($validator->fails())
			return view('vehicle.create.invalid');
		
		$validated = $validator->validated();
		$modelId = $validated['modelId'];
		$position = new Vec3(
			$validated['posX'],
			$validated['posY'],
			$validated['posZ']
		);
		$rotation = new Vec3(
			0.0,
			0.0,
			$validated['heading']
		);

		$vehicle = Vehicle::createVehicle($modelId, $position, $rotation);
		if(!$vehicle)
			return view('vehicle.create.failed');
		
		return view('vehicle.create.success', [
			'vehicle' => $vehicle
		]);
	}

	public function destroyOne(Request $request):View
	{
		$validator = Validator::make($request->all(), [
			'vehicleId' => 'required|exists:vehicle'
		], [
			'vehicleId.required' => 'Vehicle ID is required.'
		]);
		if($validator->fails())
			return view('vehicle.destroy.invalid');
		
		$validated = $validator->validated();
		$vehicleId = $validated['vehicleId'];

		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.destroy.invalid');
		
		if(Vehicle::destroyVehicle($vehicleId))
			return view('vehicle.destroy.success');
		else
			return view('vehicle.destroy.failed');
	}

	// fetch
	public function showAll():View
	{
		$vehicles = Vehicle::get();
		$vehicleModelCounts = Vehicle::getModelCounts();

		return view('vehicle.show.all', [
			'vehicles' => $vehicles,
			'vehicleModelCounts' => $vehicleModelCounts
		]);
	}

	public function showOne(string $vehicleId):View
	{
		$validator = Validator::make([
			'vehicleId' => $vehicleId
		], [
			'vehicleId' => 'required|exists:vehicle'
		], [
			'vehicleId.required' => 'Vehicle ID is required.'
		]);
		if($validator->fails())
			return view('vehicle.show.one-invalid');
		
		$validated = $validator->validated();
		$vehicleId = $validated['vehicleId'];

		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.show.one-invalid');
		
		$vehicle = Vehicle::getVehicle($vehicleId);
		if(!$vehicle)
			return view('vehicle.show.one-invalid');

		return view('vehicle.show.one', [
			'vehicle' => $vehicle
		]);
	}
};

