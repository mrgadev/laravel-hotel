<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'photos', 'room_facilities_id', 'price', 'total_rooms', 'available_rooms'];

    protected $casts = [
            'photos' => 'array'
        ];

        protected static function boot() {
            parent::boot();

            static::deleting(function($room) {
                if(!empty($room->photos)) {
                    foreach($room->photos as $photo) {
                        if(file_exists($photo)) {
                            unlink($photo);
                        }
                    }
                }
                
                if(!empty($room->cover) && file_exists($room->cover)) {
                    unlink($room->cover);
                }
            });
        }

    public function room_facility() {
        return $this->belongsToMany(RoomFacility::class);
    }

    public function promos(){
        return $this->belongsToMany(Promo::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function room_reviews() {
        return $this->hasMany(RoomReview::class);
    }

    public function decrementAvailableRooms()
    {
        $this->decrement('available_rooms');
    }

    public function incrementAvailableRooms()
    {
        $this->increment('available_rooms');
    }

    public function room_rules() {
        return $this->hasMany(RoomRule::class);
    }

     /**
     * Get the first photo as thumbnail
     */
    public function getThumbnailAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos)) {
            return $this->photos[0];
        }
        return null;
    }

    /**
     * Get thumbnail URL with fallback
     */
    public function getThumbnailUrlAttribute()
    {
        $thumbnail = $this->thumbnail;
        
        if ($thumbnail) {
            // If thumbnail already starts with http/https, return as is
            if (str_starts_with($thumbnail, 'http')) {
                return $thumbnail;
            }
            
            // If it starts with 'storage/', remove it and prepend with asset()
            if (str_starts_with($thumbnail, 'storage/')) {
                return asset($thumbnail);
            }
            
            // Otherwise, assume it's a relative path and prepend with asset()
            return asset($thumbnail);
        }
        
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    /**
     * Get all photos with proper URLs
     */
    public function getPhotosUrlAttribute()
    {
        if (empty($this->photos) || !is_array($this->photos)) {
            return [];
        }

        return array_map(function($photo) {
            if (str_starts_with($photo, 'http')) {
                return $photo;
            }
            
            if (str_starts_with($photo, 'storage/')) {
                return asset($photo);
            }
            
            return asset($photo);
        }, $this->photos);
    }
}
