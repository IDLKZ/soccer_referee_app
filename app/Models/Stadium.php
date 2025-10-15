<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Stadium
 *
 * @property int $id
 * @property string|null $image_url
 * @property int|null $city_id
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string|null $address_ru
 * @property string|null $address_kk
 * @property string|null $address_en
 * @property string|null $lat
 * @property string|null $lon
 * @property Carbon|null $built_date
 * @property string|null $phone
 * @property string|null $website
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property City|null $city
 * @property Collection|Club[] $clubs
 * @property Collection|MatchEntity[] $matches
 *
 * @package App\Models
 */
class Stadium extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;
	protected $table = 'stadiums';

	protected $casts = [
		'city_id' => 'int',
		'built_date' => 'datetime',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'image_url',
		'city_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'address_ru',
		'address_kk',
		'address_en',
		'capacity',
		'lat',
		'lon',
		'built_date',
		'phone',
		'website',
		'is_active'
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function clubs()
	{
		return $this->belongsToMany(Club::class, 'club_stadiums')
					->withPivot('id')
					->withTimestamps();
	}

	public function matches()
	{
		return $this->hasMany(MatchEntity::class);
	}

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile()
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100)
                    ->sharpen(10)
                    ->optimize()
                    ->format('webp');

                $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10)
                    ->optimize()
                    ->format('webp');
            });
    }
}
