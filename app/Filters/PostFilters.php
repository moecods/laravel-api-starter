<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class PostFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ['title'];

    protected array $allowedSorts = ['created_at'];
}
