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
 * Class Country
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string $value
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|City[] $cities
 * @property Collection|League[] $leagues
 *
 * @package App\Models
 */
class Country extends Model
{
	use SoftDeletes;
	protected $table = 'countries';

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'is_active'
	];

	public function cities()
	{
		return $this->hasMany(City::class);
	}

	public function leagues()
	{
		return $this->hasMany(League::class);
	}
}
