<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package App\Models
 * 
 * @property int $id
 * @property int $user_id
 * @property int $parent_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @property User $user
 * @property Comment $parent
 * @property Comment[]|HasMany $replies
 */
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'commentable_id',
        'commentable_type',
        'body',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')->with('replies');
    }
}
