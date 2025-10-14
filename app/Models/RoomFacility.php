<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomFacility
 * 
 * @property int $id
 * @property int $room_id
 * @property int $facility_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Facility $facility
 * @property HotelRoom $hotel_room
 *
 * @package App\Models
 */
class RoomFacility extends Model
{
	protected $table = 'room_facilities';

	protected $casts = [
		'room_id' => 'int',
		'facility_id' => 'int'
	];

	protected $fillable = [
		'room_id',
		'facility_id'
	];

	public function facility()
	{
		return $this->belongsTo(Facility::class);
	}

	public function hotel_room()
	{
		return $this->belongsTo(HotelRoom::class, 'room_id');
	}
}
