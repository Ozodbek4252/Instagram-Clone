<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Overtrue\LaravelLike\Traits\Likeable;

/**
 * @package App\Models
 * 
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property string $location
 * @property bool $hide_like_view
 * @property bool $allow_commenting
 * @property string $type allowed: ['post', 'reel']
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @property User $user
 * @property Media[]|Collection $media
 * @property Comment[]|Collection $comments
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;
    use Likeable, Favoriteable;

    protected $fillable = [
        'user_id',
        'description',
        'location',
        'hide_like_view',
        'allow_commenting',
        'type',
    ];

    protected $casts = [
        'hide_like_view' => 'boolean',
        'allow_commenting' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->with('replies');
    }
}
