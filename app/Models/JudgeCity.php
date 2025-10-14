<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JudgeCity
 * 
 * @property int $id
 * @property int $user_id
 * @property int $city_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property City $city
 * @property User $user
 *
 * @package App\Models
 */
class JudgeCity extends Model
{
	protected $table = 'judge_cities';

	protected $casts = [
		'user_id' => 'int',
		'city_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'city_id'
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
