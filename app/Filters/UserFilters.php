<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class UserFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ['name', 'email'];

    protected array $allowedSorts = ['created_at'];
}
