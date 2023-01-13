<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vec3
{
	public function __construct(
		public float $x,
		public float $y,
		public float $z
	)
	{
	}

	public function blank():Vec3
	{
		return new Vec3(0.0, 0.0, 0.0);
	}
};