<?php

namespace Modules\Core\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryContract.
 */
interface RepositoryContract
{
    public function all(): Collection;

    public function count(): int;

    public function deleteById(int $id): ?bool;

    public function first(): Model;

    public function get(): Collection;

    public function getById(int $id): Model;

    public function getByColumn(mixed $item, string $column, array $columns = ['*']): Model|Builder|null;

    public function limit(int $limit): static;

    public function orderBy(string $column, string $direction): static;

    public function paginate(int $limit = null, array $columns = ['*'], string $pageName = 'page', int $page = null): LengthAwarePaginator;

    public function where(string $column, mixed $value, string $operator = '='): static;

    public function whereIn(string $column, mixed $values): static;

    public function with(string|array $relations): static;
}
