<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldValue extends Model
{
    protected $fillable = ['subscriber_id', 'field_id', 'value'];

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}
