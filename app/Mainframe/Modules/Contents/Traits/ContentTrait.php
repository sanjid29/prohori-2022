<?php

namespace App\Mainframe\Modules\Contents\Traits;

use App\Content;

/** @mixin Content $this */
trait ContentTrait
{
    /*
    |--------------------------------------------------------------------------
    | Section: Query scopes + Dynamic scopes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Section: Accessors
    |--------------------------------------------------------------------------
    */
    // public function getFirstNameAttribute($value) { return ucfirst($value); }

    /*
    |--------------------------------------------------------------------------
    | Section: Mutators
    |--------------------------------------------------------------------------
    */
    // public function setFirstNameAttribute($value) { $this->attributes['first_name'] = strtolower($value); }

    /*
    |--------------------------------------------------------------------------
    | Section: Attributes
    |--------------------------------------------------------------------------
    */
    public function getPartsArrayAttribute()
    {
        $parts = $this->parts ?? [];

        $array = [];
        foreach ($parts as $part) {
            if (isset($part->key)) {
                $array[$part->key] = $part->value;
            }
        }

        return $array;

    }

    public function getPartsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        return json_decode($value ?? '[]');
    }

    /*
    |--------------------------------------------------------------------------
    | Section: Relations
    |--------------------------------------------------------------------------
    */
    // public function updater() { return $this->belongsTo(\App\User::class, 'updated_by'); }

    /*
    |--------------------------------------------------------------------------
    | Section: helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get content by part
     *
     * @param  null  $key
     * @return mixed|null
     */
    public function part($key = null)
    {
        if (!$key || $key == 'body') {
            return $this->body;
        }

        if ($key == 'title') {
            return $this->title;
        }

        if (isset($this->parts_array[$key])) {
            return $this->parts_array[$key];
        }

        return null;
    }

    /**
     * Check if content has a part
     *
     * @param $key
     * @return bool
     */
    public function hasPart($key)
    {
        return (bool) $this->part($key);
    }

    /**
     * Get content by key
     *
     * @param $key
     * @return Content|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function byKey($key)
    {
        return Content::where('key', $key)->where('is_active', 1)->first();
    }

}