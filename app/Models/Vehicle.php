<?php

namespace App\Models;
use App\Models\Vec3;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

enum EVehicleLockType:int
{
	case Unlocked	= 0;
	case Locked		= 1;
};

class Vehicle extends Model
{
	protected $table = 'vehicle';
	protected $primaryKey = 'vehicleId';
	public $timestamps = false;
	
	// create/destroy
	public static function createVehicle(int $modelId, Vec3 &$position, Vec3 &$rotation):Vehicle|null
	{
		$vehicle = new Vehicle();
		$vehicle->vehicleModel = $modelId;
		$vehicle->vehiclePosX = $position->x;
		$vehicle->vehiclePosY = $position->y;
		$vehicle->vehiclePosZ = $position->z;
		$vehicle->vehicleHeading = $rotation->z;
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
		return Vehicle::where('vehicleId', '=', $vehicleId)
			->first();
	}

	public static function isVehicleId(int $vehicleId):bool
	{
		return Vehicle::where('vehicleId', '=', $vehicleId)
			->exists();
	}

	public static function getModelCounts():Collection
	{
		return Vehicle::select('vehicleModel', DB::raw('count(*) as vehicleModelCount'))
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

	// vehicle attributes
	public function getLockType():EVehicleLockType
	{
		return EVehicleLockType::from($this->vehicleLocked);
	}
};