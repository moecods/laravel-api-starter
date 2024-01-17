<?php

namespace App\Models;

use App\Filters\PostFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Filterable, HasFactory;

    protected string $default_filters = PostFilters::class;

    /**
     * Mass-assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
    ];
}
