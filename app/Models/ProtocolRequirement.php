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
 * Class ProtocolRequirement
 *
 * @property int $id
 * @property int $league_id
 * @property int|null $match_id
 * @property int $judge_type_id
 * @property array|null $example_file_url
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string $info_ru
 * @property string|null $info_kk
 * @property string|null $info_en
 * @property bool $is_required
 * @property array $extensions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property JudgeType $judge_type
 * @property League $league
 * @property Match|null $match
 * @property Collection|Protocol[] $protocols
 *
 * @package App\Models
 */
class ProtocolRequirement extends Model
{
	use SoftDeletes;
	protected $table = 'protocol_requirements';

	protected $casts = [
		'league_id' => 'int',
		'match_id' => 'int',
		'judge_type_id' => 'int',
		'example_file_url' => 'json',
		'is_required' => 'bool',
		'extensions' => 'json'
	];

	protected $fillable = [
		'league_id',
		'match_id',
		'judge_type_id',
		'example_file_url',
		'title_ru',
		'title_kk',
		'title_en',
		'info_ru',
		'info_kk',
		'info_en',
		'is_required',
		'extensions'
	];

	public function judge_type()
	{
		return $this->belongsTo(JudgeType::class);
	}

	public function league()
	{
		return $this->belongsTo(League::class);
	}

	public function match()
	{
		return $this->belongsTo(MatchEntity::class);
	}

	public function protocols()
	{
		return $this->hasMany(Protocol::class, 'requirement_id');
	}
}
