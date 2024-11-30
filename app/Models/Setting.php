<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Disable the default timestamps if not needed
    public $timestamps = false;

    // Specify the table name (optional if the table follows the Laravel naming convention)
    protected $table = 'settings';

    // Allow mass assignment on 'key' and 'value' fields
    protected $fillable = ['key', 'value'];

    // Get the value as a string
    public function getValueAttribute($value)
    {
        return $value;  // You can also return a specific format if needed
    }

    // Set the value as a string (for custom formatting before saving, if needed)
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $value;
    }

    // Custom method to retrieve setting by key
    public static function getByKey($key)
    {
        return self::where('key', $key)->first()->value ?? null;  // Return null if key not found
    }
}
