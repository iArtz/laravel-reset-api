<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function setTitleAttribute(string $title): void
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug']  = Str::slug($title);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getFiltered(array $filters): Collection
    {
        return $this->filter($filters, 'tag', 'tags', 'name')
            ->where(array_key_exists('offset', $filters), function ($q) use ($filters)
        {
                $q->offset($filters['offset'])->limit($filters['limit']);
            })
            ->with('user', 'users', 'tags')
            ->get();
    }

    public function scopeFilter($query, array $filters, string $key, string $relation, string $column)
    {
        return $query->when(array_key_exists($key, $filters), function ($q) use ($filters, $relation, $column, $key)
        {
            $q->whereRelation($relation, $column, $filters[$key]);
        });
    }
}
