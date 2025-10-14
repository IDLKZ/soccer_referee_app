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
 * Class CommonService
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|ActOfWorkService[] $act_of_work_services
 *
 * @package App\Models
 */
class CommonService extends Model
{
	use SoftDeletes;
	protected $table = 'common_services';

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en'
	];

	public function act_of_work_services()
	{
		return $this->hasMany(ActOfWorkService::class, 'service_id');
	}
}
