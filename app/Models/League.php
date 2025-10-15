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
 * Class League
 *
 * @property int $id
 * @property string|null $image_url
 * @property int|null $country_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property int $level
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Country|null $country
 * @property Collection|Match[] $matches
 * @property Collection|ProtocolRequirement[] $protocol_requirements
 *
 * @package App\Models
 */
class League extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;
	protected $table = 'leagues';

	protected $casts = [
		'country_id' => 'int',
		'level' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'image_url',
		'country_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'level',
		'is_active'
	];

	public function country()
	{
		return $this->belongsTo(Country::class);
	}

	public function matches()
	{
		return $this->hasMany(MatchEntity::class);
	}

	public function protocol_requirements()
	{
		return $this->hasMany(ProtocolRequirement::class);
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
