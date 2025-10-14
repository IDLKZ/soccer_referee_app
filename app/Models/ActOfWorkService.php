<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ActOfWorkService
 * 
 * @property int $id
 * @property int $act_of_work_id
 * @property int $service_id
 * @property string $price_per
 * @property float $qty
 * @property float $price
 * @property float $total_price
 * @property Carbon $executed_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property ActOfWork $act_of_work
 * @property CommonService $common_service
 *
 * @package App\Models
 */
class ActOfWorkService extends Model
{
	use SoftDeletes;
	protected $table = 'act_of_work_services';

	protected $casts = [
		'act_of_work_id' => 'int',
		'service_id' => 'int',
		'qty' => 'float',
		'price' => 'float',
		'total_price' => 'float',
		'executed_date' => 'datetime'
	];

	protected $fillable = [
		'act_of_work_id',
		'service_id',
		'price_per',
		'qty',
		'price',
		'total_price',
		'executed_date'
	];

	public function act_of_work()
	{
		return $this->belongsTo(ActOfWork::class);
	}

	public function common_service()
	{
		return $this->belongsTo(CommonService::class, 'service_id');
	}
}
