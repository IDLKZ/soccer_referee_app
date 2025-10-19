<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Protocol
 *
 * @property int $id
 * @property int $operation_id
 * @property int $match_id
 * @property int $requirement_id
 * @property int $judge_id
 * @property string|null $file_url
 * @property string|null $info
 * @property bool|null $first_status
 * @property bool|null $final_status
 * @property bool|null $is_ready
 * @property string|null $comment
 * @property string|null $final_comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User $user
 * @property MatchEntity $match
 * @property Operation $operation
 * @property ProtocolRequirement $protocol_requirement
 * @property Collection|ActOfWork[] $act_of_works
 *
 * @package App\Models
 */
class Protocol extends Model
{
	use SoftDeletes;
	protected $table = 'protocols';

	protected $casts = [
		'operation_id' => 'int',
		'match_id' => 'int',
		'requirement_id' => 'int',
		'judge_id' => 'int',
		'first_status' => 'bool',
		'final_status' => 'bool',
		'is_ready' => 'bool'
	];

	protected $fillable = [
		'operation_id',
		'match_id',
		'requirement_id',
		'judge_id',
		'file_url',
		'info',
		'first_status',
		'final_status',
		'is_ready',
		'comment',
		'primary_comment',
		'final_comment'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function judge()
	{
		return $this->user();
	}

	public function match()
	{
		return $this->belongsTo(MatchEntity::class,"match_id");
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}

	public function protocol_requirement()
	{
		return $this->belongsTo(ProtocolRequirement::class, 'requirement_id');
	}

	public function requirement()
	{
		return $this->belongsTo(ProtocolRequirement::class, 'requirement_id');
	}

	public function act_of_works()
	{
		return $this->hasMany(ActOfWork::class);
	}
}
