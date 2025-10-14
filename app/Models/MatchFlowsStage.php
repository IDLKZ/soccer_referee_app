<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchFlowsStage
 * 
 * @property int $id
 * @property int $category_id
 * @property int $operation_id
 * @property int $match_id
 * @property int $flow_id
 * @property int $responsible_id
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property bool $is_passed
 * @property string $comment
 * @property int $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CategoryOperation $category_operation
 * @property MatchFlow $match_flow
 * @property Match $match
 * @property Operation $operation
 * @property User $user
 *
 * @package App\Models
 */
class MatchFlowsStage extends Model
{
	protected $table = 'match_flows_stages';

	protected $casts = [
		'category_id' => 'int',
		'operation_id' => 'int',
		'match_id' => 'int',
		'flow_id' => 'int',
		'responsible_id' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'is_passed' => 'bool',
		'result' => 'int'
	];

	protected $fillable = [
		'category_id',
		'operation_id',
		'match_id',
		'flow_id',
		'responsible_id',
		'start_at',
		'end_at',
		'is_passed',
		'comment',
		'result'
	];

	public function category_operation()
	{
		return $this->belongsTo(CategoryOperation::class, 'category_id');
	}

	public function match_flow()
	{
		return $this->belongsTo(MatchFlow::class, 'flow_id');
	}

	public function match()
	{
		return $this->belongsTo(Match::class);
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'responsible_id');
	}
}
