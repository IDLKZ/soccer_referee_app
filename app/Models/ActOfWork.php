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
 * Class ActOfWork
 * 
 * @property int $id
 * @property int $match_id
 * @property int|null $protocol_id
 * @property int $operation_id
 * @property int $judge_id
 * @property string $customer_info
 * @property bool|null $judge_status
 * @property bool|null $first_status
 * @property bool|null $control_status
 * @property bool|null $first_financial_status
 * @property bool|null $last_financial_status
 * @property bool|null $final_status
 * @property string $dogovor_number
 * @property string $dogovor_date
 * @property string $act_number
 * @property Carbon $act_date
 * @property bool $is_ready
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Match $match
 * @property Operation $operation
 * @property Protocol|null $protocol
 * @property Collection|ActOfPayment[] $act_of_payments
 * @property Collection|ActOfWorkService[] $act_of_work_services
 *
 * @package App\Models
 */
class ActOfWork extends Model
{
	use SoftDeletes;
	protected $table = 'act_of_works';

	protected $casts = [
		'match_id' => 'int',
		'protocol_id' => 'int',
		'operation_id' => 'int',
		'judge_id' => 'int',
		'judge_status' => 'bool',
		'first_status' => 'bool',
		'control_status' => 'bool',
		'first_financial_status' => 'bool',
		'last_financial_status' => 'bool',
		'final_status' => 'bool',
		'act_date' => 'datetime',
		'is_ready' => 'bool'
	];

	protected $fillable = [
		'match_id',
		'protocol_id',
		'operation_id',
		'judge_id',
		'customer_info',
		'judge_status',
		'first_status',
		'control_status',
		'first_financial_status',
		'last_financial_status',
		'final_status',
		'dogovor_number',
		'dogovor_date',
		'act_number',
		'act_date',
		'is_ready'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}

	public function match()
	{
		return $this->belongsTo(Match::class);
	}

	public function operation()
	{
		return $this->belongsTo(Operation::class);
	}

	public function protocol()
	{
		return $this->belongsTo(Protocol::class);
	}

	public function act_of_payments()
	{
		return $this->hasMany(ActOfPayment::class, 'act_id');
	}

	public function act_of_work_services()
	{
		return $this->hasMany(ActOfWorkService::class);
	}
}
