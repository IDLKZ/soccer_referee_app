<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property int|null $role_id
 * @property string|null $image_url
 * @property string $last_name
 * @property string $first_name
 * @property string|null $patronomic
 * @property string $phone
 * @property string $email
 * @property string $username
 * @property int $sex
 * @property string|null $iin
 * @property Carbon|null $birth_date
 * @property string|null $password_hash
 * @property bool $is_active
 * @property bool $is_verified
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Role|null $role
 * @property Collection|ActOfPayment[] $act_of_payments
 * @property Collection|ActOfWork[] $act_of_works
 * @property Collection|JudgeCity[] $judge_cities
 * @property Collection|MatchFlowsStage[] $match_flows_stages
 * @property Collection|MatchJudge[] $match_judges
 * @property Collection|MatchLogist[] $match_logists
 * @property Collection|Protocol[] $protocols
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use SoftDeletes, Notifiable, HasApiTokens;
	protected $table = 'users';

	protected $casts = [
		'role_id' => 'int',
		'sex' => 'int',
		'birth_date' => 'datetime',
		'is_active' => 'bool',
		'is_verified' => 'bool'
	];

	protected $hidden = [
		'password_hash',
		'remember_token'
	];

	/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_hash' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'sex' => 'integer',
            'birth_date' => 'datetime',
        ];
    }

	/**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

	/**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->last_name} {$this->first_name}" .
               ($this->patronomic ? " {$this->patronomic}" : '');
    }

	/**
     * Check if user is administrator.
     */
    public function isAdministrator(): bool
    {
        return $this->role && $this->role->value === \App\Constants\RoleConstants::ADMINISTRATOR;
    }

	/**
     * Check if user has administrative role.
     */
    public function isAdministrative(): bool
    {
        return $this->role && in_array($this->role->value, \App\Constants\RoleConstants::administrative());
    }

	/**
     * Check if user can manage referees.
     */
    public function canManageReferees(): bool
    {
        return $this->role && in_array($this->role->value, [
            \App\Constants\RoleConstants::ADMINISTRATOR,
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

	/**
     * Check if user can manage finance.
     */
    public function canManageFinance(): bool
    {
        return $this->role && in_array($this->role->value, [
            \App\Constants\RoleConstants::ADMINISTRATOR,
            \App\Constants\RoleConstants::FINANCE_DEPARTMENT_HEAD,
            \App\Constants\RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ]);
    }

	/**
     * Check if user can manage logistics.
     */
    public function canManageLogistics(): bool
    {
        return $this->role && in_array($this->role->value, [
            \App\Constants\RoleConstants::ADMINISTRATOR,
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

	protected $fillable = [
		'role_id',
		'image_url',
		'last_name',
		'first_name',
		'patronomic',
		'phone',
		'email',
		'username',
		'sex',
		'iin',
		'birth_date',
		'password_hash',
		'is_active',
		'is_verified',
		'remember_token'
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function act_of_payments()
	{
		return $this->hasMany(ActOfPayment::class, 'checked_by');
	}

	public function act_of_works()
	{
		return $this->hasMany(ActOfWork::class, 'judge_id');
	}

	public function judge_cities()
	{
		return $this->hasMany(JudgeCity::class);
	}

	public function match_flows_stages()
	{
		return $this->hasMany(MatchFlowsStage::class, 'responsible_id');
	}

	public function match_judges()
	{
		return $this->hasMany(MatchJudge::class, 'judge_id');
	}

	public function match_logists()
	{
		return $this->hasMany(MatchLogist::class, 'logist_id');
	}

	public function protocols()
	{
		return $this->hasMany(Protocol::class, 'judge_id');
	}

	public function trips()
	{
		return $this->hasMany(Trip::class, 'logist_id');
	}

	/**
	 * Generate a unique username from email.
	 */
	public static function generateUniqueUsername(string $email): string
	{
		$username = explode('@', $email)[0];
		$baseUsername = $username;
		$counter = 1;

		while (self::where('username', $username)->exists()) {
			$username = $baseUsername . $counter;
			$counter++;
		}

		return $username;
	}
}
