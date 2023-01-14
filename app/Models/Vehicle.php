<?php

namespace App\Models;
use App\Models\Vec3;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

enum EVehicleLockType:int
{
	case Unlocked	= 0;
	case Locked		= 1;
};

enum EVehicleModel:int
{
	case Unknown = 0;
	
	case a = 90;
	case b = 91;
	case c = 92;
	case d = 93;
	case e = 94;
	case f = 95;
	case g = 96;
	case h = 97;
	
	case i = 99;
	case j = 100;
};

abstract class EntityModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
};

class VehicleModel extends EntityModel
{
	public function __construct(
		public EVehicleModel $id
	)
	{
		parent::__construct();
	}
};

abstract class Entity extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
};

class Vehicle extends Entity
{
	protected $table = 'vehicle';
	protected $primaryKey = 'vehicleId';
	public $timestamps = false;
	protected $appends = ['model', 'lock'];

	// create/destroy
	public static function createVehicle(int $modelId, Vec3 &$position, Vec3 &$rotation):Vehicle|null
	{
		$vehicle = new self();
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
		$vehicle = self::find($vehicleId);
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
		return self::where('vehicleId', '=', $vehicleId)
			->first();
	}

	public static function isVehicleId(int $vehicleId):bool
	{
		return self::where('vehicleId', '=', $vehicleId)
			->exists();
	}

	public static function getModelCounts():Collection
	{
		return self::select('vehicleModel', DB::raw('count(*) as vehicleModelCount'))
			->orderBy('vehicleModelCount', 'desc')
			->orderBy('vehicleModel', 'asc')
			->groupBy('vehicleModel')
			->get();
	}

	public static function getIdsString():string
	{
		return self::select(DB::raw("GROUP_CONCAT(vehicleId SEPARATOR ',') as vehicleIdsStr"))
			->first()
			->vehicleIdsStr;
	}

	// update
	public static function updateVehicle(int $vehicleId, array $newData):bool
	{
		return self::where('vehicleId', '=', $vehicleId)
			->update($newData);
	}

	// vehicle attributes
	public function getModelAttribute():VehicleModel
	{
		$modelId = $this->vehicleModel <= EVehicleModel::j->value ? EVehicleModel::from($this->vehicleModel) : EVehicleModel::Unknown;
		return new VehicleModel($modelId);
	}

	public function getLockAttribute():EVehicleLockType
	{
		return EVehicleLockType::from($this->vehicleLocked);
	}

	// validation
	public static function validateVehicleId(int|null $vehicleId):array|false
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

	public static function validateVehicleData(array $newData = null):array|false
	{
		$validator = Validator::make($newData, [
			'vehicleModel' => [
				'required',
				'int',
				'between:90,100',
				Rule::notIn([98])
			],
			'vehiclePosX' => 'required|numeric|between:-20000.0,20000.0',
			'vehiclePosY' => 'required|numeric|between:-20000.0,20000.0',
			'vehiclePosZ' => 'required|numeric|between:-20000.0,20000.0',
			'vehicleHeading' => 'required|numeric|between:-'.pi().','.pi()
		], [
			'vehicleModel.required' => 'Model ID is required.',
			'vehiclePosX.required' => 'X position is required.',
			'vehiclePosY.required' => 'Y position is required.',
			'vehiclePosZ.required' => 'Z position is required.',
			'vehicleHeading.required' => 'Heading is required.'
		]);
		if($validator->fails())
			return false;
		
		return $validator->validated();
	}

	public static function validateVehicleIdRequest(Request $request):array|false
	{
		if($request->has('vehicleId'))
			$vehicleId = $request->input('vehicleId');
		else
			$vehicleId = null;

		return self::validateVehicleId($vehicleId);
	}

	public static function validateVehicleDataRequest(Request $request, array $newData = null):array|false
	{
		if($newData === null)
			$newData = $request->all();

		return self::validateVehicleData($newData);
	}
};