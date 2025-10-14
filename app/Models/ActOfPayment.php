<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ActOfPayment
 * 
 * @property int $id
 * @property int $act_id
 * @property array $file_urls
 * @property int $checked_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property ActOfWork $act_of_work
 * @property User $user
 *
 * @package App\Models
 */
class ActOfPayment extends Model
{
	use SoftDeletes;
	protected $table = 'act_of_payments';

	protected $casts = [
		'act_id' => 'int',
		'file_urls' => 'json',
		'checked_by' => 'int'
	];

	protected $fillable = [
		'act_id',
		'file_urls',
		'checked_by'
	];

	public function act_of_work()
	{
		return $this->belongsTo(ActOfWork::class, 'act_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'checked_by');
	}
}
