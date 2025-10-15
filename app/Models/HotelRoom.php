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
 * Class HotelRoom
 * 
 * @property int $id
 * @property int|null $hotel_id
 * @property array|null $image_url
 * @property string $title_ru
 * @property string|null $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property int $bed_quantity
 * @property float $room_size
 * @property bool $air_conditioning
 * @property bool $private_bathroom
 * @property bool $tv
 * @property bool $wifi
 * @property bool $smoking_allowed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Hotel|null $hotel
 * @property Collection|RoomFacility[] $room_facilities
 *
 * @package App\Models
 */
class HotelRoom extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;
	protected $table = 'hotel_rooms';

	protected $casts = [
		'hotel_id' => 'int',
		'image_url' => 'json',
		'bed_quantity' => 'int',
		'room_size' => 'float',
		'air_conditioning' => 'bool',
		'private_bathroom' => 'bool',
		'tv' => 'bool',
		'wifi' => 'bool',
		'smoking_allowed' => 'bool'
	];

	protected $fillable = [
		'hotel_id',
		'image_url',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'bed_quantity',
		'room_size',
		'air_conditioning',
		'private_bathroom',
		'tv',
		'wifi',
		'smoking_allowed'
	];

	public function hotel()
	{
		return $this->belongsTo(Hotel::class);
	}

	public function room_facilities()
	{
		return $this->hasMany(RoomFacility::class, 'room_id');
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
