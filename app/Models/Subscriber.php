<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriber extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'name', 'state'];

    public function fieldValues(): HasMany
    {
        return $this->hasMany(FieldValue::class);
    }
}
