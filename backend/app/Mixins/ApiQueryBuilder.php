<?php

namespace App\Mixins;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ApiQueryBuilder
{
    public function allowedFilters(): Closure
    {
        return function (array $allowedFilters) {
            /** @var Builder $this */
            foreach (request('filter', []) as $filter => $value) {
                abort_unless(in_array($filter, $allowedFilters), 400);

                if ($this->hasNamedScope($filter)) {
                    $this->{$filter}($value);
                } else {
                    $this->whereLike($filter, "%{$value}%");
                }
            }

            return $this;
        };
    }
}
