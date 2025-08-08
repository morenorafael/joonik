<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
    ];

    #[Scope]
    protected function code(Builder $query, string $value): void
    {
        $query->whereLike('code', "%{$value}%");
    }

    #[Scope]
    protected function name(Builder $query, string $value): void
    {
        $query->whereLike('name', "%{$value}%");
    }
}
