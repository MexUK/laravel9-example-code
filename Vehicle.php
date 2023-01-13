<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vehicle extends Model
{
	protected $table = 'vehicle';
	protected $primaryKey = 'vehicleId';

	// create/destroy
	public static function createVehicle(int $modelId):Vehicle|null
	{
		$vehicle = new Vehicle();
		$vehicle->vehicleModel = $modelId;
		$vehicle->save();
		return $vehicle;
	}

	public static function destroyVehicle(int $vehicleId):bool
	{
		$vehicle = Vehicle::find($vehicleId);
		if($vehicle)
		{
			$vehicle->delete();
			return true;
		}
		return false;
	}

	// fetch
	public static function getVehicle(int $vehicleId):Vehicle|null
	{
		return Vehicle::where('vehicleId', '=', $vehicleId)->first();
	}

	public function getModelCounts():Collection
	{
		return $this
			->select('vehicleModel', DB::raw('count(*) as vehicleModelCount'))
			->orderBy('vehicleModelCount', 'desc')
			->orderBy('vehicleModel', 'asc')
			->groupBy('vehicleModel')
			->get();
	}

	// update
	public static function updateVehicle(int $vehicleId, array $newData):bool
	{
		return Vehicle::where('vehicleId', '=', $vehicleId)
			->update($newData);
	}
};