<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchJudge
 *
 * @property int $id
 * @property int $match_id
 * @property int $type_id
 * @property int $judge_id
 * @property int $judge_response
 * @property string|null $cancel_reason
 * @property int $final_status
 * @property int|null $created_by_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 * @property Match $match
 * @property JudgeType $judge_type
 *
 * @package App\Models
 */
class MatchJudge extends Model
{
	protected $table = 'match_judges';

	protected $casts = [
		'match_id' => 'int',
		'type_id' => 'int',
		'judge_id' => 'int',
		'judge_response' => 'int',
		'final_status' => 'int',
		'created_by_id' => 'int'
	];

	protected $fillable = [
		'match_id',
		'type_id',
		'judge_id',
		'judge_response',
		'cancel_reason',
		'final_status',
		'created_by_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchEntity::class);
	}

	public function judge_type()
	{
		return $this->belongsTo(JudgeType::class, 'type_id');
	}
}
