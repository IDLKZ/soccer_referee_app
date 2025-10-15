<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Operation
 *
 * @property int $id
 * @property int $category_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property array|null $responsible_roles
 * @property bool $is_first
 * @property bool $is_last
 * @property bool $can_reject
 * @property bool $is_active
 * @property int $result
 * @property int|null $previous_id
 * @property int|null $next_id
 * @property int|null $on_reject_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CategoryOperation $category_operation
 * @property Operation|null $operation
 * @property Collection|ActOfWork[] $act_of_works
 * @property Collection|MatchDeadline[] $match_deadlines
 * @property Collection|MatchFlow[] $match_flows
 * @property Collection|MatchFlowsStage[] $match_flows_stages
 * @property Collection|MatchEntity[] $matches
 * @property Collection|Operation[] $operations
 * @property Collection|Protocol[] $protocols
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class Operation extends Model
{
	protected $table = 'operations';

	protected $casts = [
		'category_id' => 'int',
		'responsible_roles' => 'json',
		'is_first' => 'bool',
		'is_last' => 'bool',
		'can_reject' => 'bool',
		'is_active' => 'bool',
		'result' => 'int',
		'previous_id' => 'int',
		'next_id' => 'int',
		'on_reject_id' => 'int'
	];

	protected $fillable = [
		'category_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'responsible_roles',
		'is_first',
		'is_last',
		'can_reject',
		'is_active',
		'result',
		'previous_id',
		'next_id',
		'on_reject_id'
	];

	public function category_operation()
	{
		return $this->belongsTo(CategoryOperation::class, 'category_id');
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class, 'previous_id');
	}

	public function act_of_works()
	{
		return $this->hasMany(ActOfWork::class);
	}

	public function match_deadlines()
	{
		return $this->hasMany(MatchDeadline::class);
	}

	public function match_flows()
	{
		return $this->hasMany(MatchFlow::class);
	}

	public function match_flows_stages()
	{
		return $this->hasMany(MatchFlowsStage::class);
	}

	public function matches()
	{
		return $this->hasMany(MatchEntity::class, 'current_operation_id');
	}

	public function operations()
	{
		return $this->hasMany(Operation::class, 'previous_id');
	}

	public function protocols()
	{
		return $this->hasMany(Protocol::class);
	}

	public function trips()
	{
		return $this->hasMany(Trip::class);
	}
}
