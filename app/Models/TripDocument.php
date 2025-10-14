<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TripDocument
 * 
 * @property int $id
 * @property int $trip_id
 * @property string|null $file_url
 * @property string $title
 * @property string|null $info
 * @property bool $is_active
 * @property float $price
 * @property float $qty
 * @property float $total_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Trip $trip
 *
 * @package App\Models
 */
class TripDocument extends Model
{
	use SoftDeletes;
	protected $table = 'trip_documents';

	protected $casts = [
		'trip_id' => 'int',
		'is_active' => 'bool',
		'price' => 'float',
		'qty' => 'float',
		'total_price' => 'float'
	];

	protected $fillable = [
		'trip_id',
		'file_url',
		'title',
		'info',
		'is_active',
		'price',
		'qty',
		'total_price'
	];

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}
