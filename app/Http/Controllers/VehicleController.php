<?php

namespace App\Http\Controllers;

use App\Models\Vec3;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehicleController extends Controller
{
	// create/destroy
	public function createOne(Request $request):View
	{
		$validated = Vehicle::validateVehicleDataRequest($request);
		if(!$validated)
			return view('vehicle.create.invalid');

		$modelId = $validated['vehicleModel'];
		$position = new Vec3(
			$validated['vehiclePosX'],
			$validated['vehiclePosY'],
			$validated['vehiclePosZ']
		);
		$rotation = new Vec3(
			0.0,
			0.0,
			$validated['vehicleHeading']
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
		$validated = Vehicle::validateVehicleIdRequest($request);
		if(!$validated)
			return view('vehicle.destroy.invalid');
		
		$vehicleId = $validated['vehicleId'];
		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.destroy.invalid');
		
		if(!Vehicle::destroyVehicle($vehicleId))
			return view('vehicle.destroy.failed');
		
		return view('vehicle.destroy.success');
	}

	// fetch - txt
	public function showAll():View
	{
		$vehicles = Vehicle::get();
		$vehicleModelCounts = Vehicle::getModelCounts();

		return view('vehicle.show.all', [
			'vehicles' => $vehicles,
			'vehicleModelCounts' => $vehicleModelCounts
		]);
	}

	public function showOne(int $vehicleId):View
	{
		$validated = Vehicle::validateVehicleId($vehicleId);
		if(!$validated)
			return view('vehicle.show.one-invalid');
		
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

	// fetch - csv
	public function showIds():View
	{
		$vehicleIdsStr = Vehicle::getIdsString();

		return view('vehicle.show.ids', [
			'vehicleIdsStr' => $vehicleIdsStr
		]);
	}

	// fetch - json
	public function showAllJson():JsonResponse
	{
		$vehicles = Vehicle::get();

		return response()
			->json($vehicles);
	}

	public function showOneJson(int $vehicleId):JsonResponse|View
	{
		$validated = Vehicle::validateVehicleId($vehicleId);
		if(!$validated)
			return view('vehicle.show.one-invalid');
		
		$vehicleId = $validated['vehicleId'];
		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.show.one-invalid');
		
		$vehicle = Vehicle::getVehicle($vehicleId);
		if(!$vehicle)
			return view('vehicle.show.one-invalid');

		return response()
			->json($vehicle);
	}

	// update
	public function updateOne(Request $request):View
	{
		$validated1 = Vehicle::validateVehicleIdRequest($request);
		if(!$validated1)
			return view('vehicle.update.invalid-id');
		
		$validated2 = Vehicle::validateVehicleDataRequest($request);
		if(!$validated2)
			return view('vehicle.update.invalid-data');
		
		$validated = [...$validated1, ...$validated2];

		$vehicleId = $validated['vehicleId'];
		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.update.invalid');
		
		if(!Vehicle::updateVehicle($vehicleId, $validated))
			return view('vehicle.update.failed');
		
		return view('vehicle.update.success');
	}
};

