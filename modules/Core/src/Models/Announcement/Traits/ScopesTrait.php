<?php

namespace Modules\Core\Models\Announcement\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopesTrait
{

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->whereEnabled(true);
    }

    /**
     * @param Builder $query
     * @param string $area
     *
     * @return Builder
     */
    public function scopeForArea(Builder $query, string $area): Builder
    {
        return $query->where(function ($query) use ($area) {
            $query->whereArea($area)
                ->orWhereNull('area');
        });
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInTimeFrame(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->where(function ($query) {
                $query->whereNull('starts_at')
                    ->whereNull('ends_at');
            })->orWhere(function ($query) {
                $query->whereNotNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now());
            })->orWhere(function ($query) {
                $query->whereNotNull('starts_at')
                    ->whereNull('ends_at')
                    ->where('starts_at', '<=', now());
            })->orWhere(function ($query) {
                $query->whereNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '>=', now());
            });
        });
    }

}
