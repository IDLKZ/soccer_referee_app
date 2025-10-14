<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TripHotel
 * 
 * @property int $id
 * @property int $trip_id
 * @property int $hotel_id
 * @property int|null $room_id
 * @property Carbon $from_date
 * @property Carbon $to_date
 * @property string|null $info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Hotel|null $hotel
 * @property Trip $trip
 *
 * @package App\Models
 */
class TripHotel extends Model
{
	use SoftDeletes;
	protected $table = 'trip_hotels';

	protected $casts = [
		'trip_id' => 'int',
		'hotel_id' => 'int',
		'room_id' => 'int',
		'from_date' => 'datetime',
		'to_date' => 'datetime'
	];

	protected $fillable = [
		'trip_id',
		'hotel_id',
		'room_id',
		'from_date',
		'to_date',
		'info'
	];

	public function hotel()
	{
		return $this->belongsTo(Hotel::class, 'room_id');
	}

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}
