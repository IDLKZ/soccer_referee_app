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
 * Class CategoryOperation
 * 
 * @property int $id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string $value
 * @property int $level
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|MatchFlow[] $match_flows
 * @property Collection|MatchFlowsStage[] $match_flows_stages
 * @property Collection|Operation[] $operations
 *
 * @package App\Models
 */
class CategoryOperation extends Model
{
	use SoftDeletes;
	protected $table = 'category_operations';

	protected $casts = [
		'level' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'level',
		'is_active'
	];

	public function match_flows()
	{
		return $this->hasMany(MatchFlow::class, 'category_id');
	}

	public function match_flows_stages()
	{
		return $this->hasMany(MatchFlowsStage::class, 'category_id');
	}

	public function operations()
	{
		return $this->hasMany(Operation::class, 'category_id');
	}
}
