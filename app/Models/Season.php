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
 * Class Season
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|Match[] $matches
 *
 * @package App\Models
 */
class Season extends Model
{
	use SoftDeletes;
	protected $table = 'seasons';

	protected $casts = [
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'start_at',
		'end_at',
		'is_active'
	];

	public function matches()
	{
		return $this->hasMany(MatchEntity::class);
	}
}
