<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @package App\Models
 * 
 * @property int $id
 * @property string $url
 * @property string $mime
 * @property int $mediable_id
 * @property string $mediable_type
 */
class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'mime',
        'mediable_id',
        'mediable_type',
    ];

    protected $casts = [
        'mediable_id' => 'integer',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
