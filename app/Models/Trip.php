<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Trip
 *
 * @property int $id
 * @property int $operation_id
 * @property int $match_id
 * @property int|null $departure_city_id
 * @property int|null $arrival_city_id
 * @property string|null $name
 * @property Carbon|null $departure_date
 * @property Carbon|null $return_date
 * @property int $transport_type_id
 * @property int|null $judge_id
 * @property int|null $logist_id
 * @property int $judge_status
 * @property int $first_status
 * @property int $final_status
 * @property string|null $info
 * @property string|null $judge_comment
 * @property string|null $first_comment
 * @property string|null $final_comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property City|null $city
 * @property User|null $user
 * @property MatchEntity $match
 * @property Operation $operation
 * @property TransportType $transport_type
 * @property Collection|TripDocument[] $trip_documents
 * @property Collection|Hotel[] $hotels
 *
 * @package App\Models
 */
class Trip extends Model
{
	protected $table = 'trips';

	protected $casts = [
		'operation_id' => 'int',
		'match_id' => 'int',
		'departure_city_id' => 'int',
		'arrival_city_id' => 'int',
		'departure_date' => 'datetime',
		'return_date' => 'datetime',
		'transport_type_id' => 'int',
		'judge_id' => 'int',
		'logist_id' => 'int',
		'judge_status' => 'int',
		'first_status' => 'int',
		'final_status' => 'int'
	];

	protected $fillable = [
		'operation_id',
		'match_id',
		'departure_city_id',
		'arrival_city_id',
		'name',
		'departure_date',
		'return_date',
		'transport_type_id',
		'judge_id',
		'logist_id',
		'judge_status',
		'first_status',
		'final_status',
		'info',
		'judge_comment',
		'first_comment',
		'final_comment'
	];

	public function city()
	{
		return $this->belongsTo(City::class, 'departure_city_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function judge()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchEntity::class);
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}

	public function transport_type()
	{
		return $this->belongsTo(TransportType::class);
	}

	public function trip_documents()
	{
		return $this->hasMany(TripDocument::class);
	}

	public function trip_hotels()
	{
		return $this->hasMany(TripHotel::class);
	}

	public function trip_migrations()
	{
		return $this->hasMany(TripMigration::class);
	}

	public function hotels()
	{
		return $this->belongsToMany(Hotel::class, 'trip_hotels', 'trip_id', 'room_id')
					->withPivot('id', 'hotel_id', 'from_date', 'to_date', 'info', 'deleted_at')
					->withTimestamps();
	}
}
