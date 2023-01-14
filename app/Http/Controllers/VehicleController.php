<?php

namespace App\Http\Controllers;

use App\Models\Vec3;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehicleController extends Controller
{
	// create/destroy
	public function createOne(Request $request):View
	{
		$validator = Validator::make($request->all(), [
			'modelId' => [
				'required',
				'int',
				'between:90,100',
				Rule::notIn([98])
			],
			'posX' => 'required|numeric|between:-20000.0,20000.0',
			'posY' => 'required|numeric|between:-20000.0,20000.0',
			'posZ' => 'required|numeric|between:-20000.0,20000.0',
			'heading' => 'required|numeric|between:-'.pi().','.pi()
		], [
			'modelId.required' => 'Model ID is required.',
			'posX.required' => 'X position is required.',
			'posY.required' => 'Y position is required.',
			'posZ.required' => 'Z position is required.',
			'heading.required' => 'Heading is required.'
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
		$validated = self::validateVehicleId($request->input('vehicleId'));
		if(!$validated)
			return view('vehicle.destroy.invalid');
		
		$vehicleId = $validated['vehicleId'];
		if(!Vehicle::isVehicleId($vehicleId))
			return view('vehicle.destroy.invalid');
		
		if(!Vehicle::destroyVehicle($vehicleId))
			return view('vehicle.destroy.failed');
		
		return view('vehicle.destroy.success');
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

	public function showOne(int $vehicleId):View
	{
		$validated = self::validateVehicleId($vehicleId);
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

	// csv
	public function showIds():View
	{
		$vehicleIdsStr = Vehicle::getIdsString();

		return view('vehicle.show.ids', [
			'vehicleIdsStr' => $vehicleIdsStr
		]);
	}

	// json
	public function showAllJson():JsonResponse
	{
		$vehicles = Vehicle::get();

		return response()
			->json($vehicles);
	}

	public function showOneJson(int $vehicleId):JsonResponse|View
	{
		$validated = self::validateVehicleId($vehicleId);
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

	// validation
	private function validateVehicleId(int $vehicleId):array|false
	{
		$validator = Validator::make([
			'vehicleId' => $vehicleId
		], [
			'vehicleId' => 'required|exists:vehicle'
		], [
			'vehicleId.required' => 'Vehicle ID is either missing or invalid.'
		]);
		
		if($validator->fails())
			return false;
		
		return $validator->validated();
	}
};

