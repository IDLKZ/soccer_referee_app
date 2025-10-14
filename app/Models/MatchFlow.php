<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchFlow
 *
 * @property int $id
 * @property int $match_id
 * @property int $category_id
 * @property int $operation_id
 * @property array|null $responsible_ids
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property bool $is_active
 * @property bool $is_finished
 * @property bool $is_canceled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CategoryOperation $category_operation
 * @property Match $match
 * @property Operation $operation
 * @property Collection|MatchFlowsStage[] $match_flows_stages
 *
 * @package App\Models
 */
class MatchFlow extends Model
{
	protected $table = 'match_flows';

	protected $casts = [
		'match_id' => 'int',
		'category_id' => 'int',
		'operation_id' => 'int',
		'responsible_ids' => 'json',
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'is_active' => 'bool',
		'is_finished' => 'bool',
		'is_canceled' => 'bool'
	];

	protected $fillable = [
		'match_id',
		'category_id',
		'operation_id',
		'responsible_ids',
		'start_at',
		'end_at',
		'is_active',
		'is_finished',
		'is_canceled'
	];

	public function category_operation()
	{
		return $this->belongsTo(CategoryOperation::class, 'category_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchEntity::class);
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}

	public function match_flows_stages()
	{
		return $this->hasMany(MatchFlowsStage::class, 'flow_id');
	}
}
