<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TripMigration
 * 
 * @property int $id
 * @property int $transport_type_id
 * @property int $departure_city_id
 * @property int $arrival_city_id
 * @property Carbon $from_date
 * @property Carbon $to_date
 * @property string|null $info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property City $city
 * @property TransportType $transport_type
 *
 * @package App\Models
 */
class TripMigration extends Model
{
	use SoftDeletes;
	protected $table = 'trip_migrations';

	protected $casts = [
		'transport_type_id' => 'int',
		'departure_city_id' => 'int',
		'arrival_city_id' => 'int',
		'from_date' => 'datetime',
		'to_date' => 'datetime'
	];

	protected $fillable = [
		'transport_type_id',
		'departure_city_id',
		'arrival_city_id',
		'from_date',
		'to_date',
		'info'
	];

	public function city()
	{
		return $this->belongsTo(City::class, 'departure_city_id');
	}

	public function transport_type()
	{
		return $this->belongsTo(TransportType::class);
	}
}
