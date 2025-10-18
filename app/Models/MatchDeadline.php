<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchDeadline
 *
 * @property int $id
 * @property int $match_id
 * @property int $operation_id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property MatchEntity $match
 * @property Operation $operation
 *
 * @package App\Models
 */
class MatchDeadline extends Model
{
	protected $table = 'match_deadlines';

	protected $casts = [
		'match_id' => 'int',
		'operation_id' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime'
	];

	protected $fillable = [
		'match_id',
		'operation_id',
		'start_at',
		'end_at'
	];

	public function match()
	{
		return $this->belongsTo(MatchEntity::class, 'match_id');
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}
}
