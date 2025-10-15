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
 * Class Hotel
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
 * @property int $star
 * @property string|null $email
 * @property string|null $address_ru
 * @property string|null $address_kk
 * @property string|null $address_en
 * @property string|null $website_ru
 * @property string|null $website_kk
 * @property string|null $website_en
 * @property string|null $lat
 * @property string|null $lon
 * @property bool $is_active
 * @property bool $is_partner
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property City|null $city
 * @property Collection|HotelRoom[] $hotel_rooms
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class Hotel extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;
	protected $table = 'hotels';

	protected $casts = [
		'city_id' => 'int',
		'star' => 'int',
		'is_active' => 'bool',
		'is_partner' => 'bool'
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
		'star',
		'email',
		'address_ru',
		'address_kk',
		'address_en',
		'website_ru',
		'website_kk',
		'website_en',
		'lat',
		'lon',
		'is_active',
		'is_partner'
	];

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function hotel_rooms()
	{
		return $this->hasMany(HotelRoom::class);
	}

	public function trips()
	{
		return $this->belongsToMany(Trip::class, 'trip_hotels', 'room_id')
					->withPivot('id', 'hotel_id', 'from_date', 'to_date', 'info', 'deleted_at')
					->withTimestamps();
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
