<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vehicle extends Model
{
    protected $table = 'vehicle';
    protected $primaryKey = 'vehicleId';

    public function getModelCounts():Collection
    {
        return $this
            ->select('vehicleModel', DB::raw('count(*) as vehicleModelCount'))
            ->orderBy('vehicleModelCount', 'desc')
            ->orderBy('vehicleModel', 'asc')
            ->groupBy('vehicleModel')
            ->get();
    }

    public static function getVehicle(int $id):Vehicle|null
    {
        return Vehicle::where('vehicleId', '=', $id)->first();
    }
};