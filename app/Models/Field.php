<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'type'];

    public function fieldValues(): HasMany
    {
        return $this->hasMany(FieldValue::class);
    }

    /**
     * Get all subscribers that have values for this field.
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(
            Subscriber::class,
            'field_values',
            'field_id',
            'subscriber_id'
        );
    }
}
