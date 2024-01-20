<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class RoleFilters extends QueryFilters
{
    protected array $allowedIncludes = ['permissions'];

    protected array $allowedFilters = ['name'];

    protected array $relationSearch = [
        'permissions' => ['name'],
    ];

    protected array $columnSearch = ['name'];
}
