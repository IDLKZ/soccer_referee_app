<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MatchLogist
 *
 * @property int $id
 * @property int $match_id
 * @property int $logist_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 * @property Match $match
 *
 * @package App\Models
 */
class MatchLogist extends Model
{
	protected $table = 'match_logists';

	protected $casts = [
		'match_id' => 'int',
		'logist_id' => 'int'
	];

	protected $fillable = [
		'match_id',
		'logist_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'logist_id');
	}

	public function match()
	{
		return $this->belongsTo(MatchEntity::class);
	}
}
