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
 * Class TransportType
 *
 * @property int $id
 * @property string|null $image_url
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|TripMigration[] $trip_migrations
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class TransportType extends Model implements HasMedia
{
    use InteractsWithMedia;

	protected $table = 'transport_types';

	protected $casts = [
		'image_url' => 'json'
	];

	protected $fillable = [
		'image_url',
		'title_ru',
		'title_kk',
	'title_en',
		'description_ru',
		'description_kk',
		'description_en'
	];

	public function trip_migrations()
	{
		return $this->hasMany(TripMigration::class);
	}

	public function trips()
	{
		return $this->hasMany(Trip::class);
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
