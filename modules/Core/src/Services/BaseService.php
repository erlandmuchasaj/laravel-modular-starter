<?php

namespace Modules\Core\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository.
 */
abstract class BaseService
{
    /**
     * The repository model.
     */
    protected Model $model;

    /**
     * The query builder.
     */
    protected Builder $query;

    /**
     * Alias for the query limit.
     */
    protected int|null $take;

    /**
     * Array of related models to eager load.
     */
    protected array $with = [];

    /**
     * Array of one or more where clause parameters.
     */
    protected array $wheres = [];

    /**
     * Array of one or more where in clause parameters.
     */
    protected array $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     */
    protected array $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     */
    protected array $scopes = [];

    /**
     * Get all the model records in the database.
     */
    public function all(): Collection
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     */
    public function count(): int
    {
        return $this->get()->count();
    }

    /**
     * Get the first specified model record from the database.
     */
    public function first(): Model|null
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->first();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get the first specified model record from the database or throw an exception if not found.
     */
    public function firstOrFail(): Model
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->firstOrFail();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get all the specified model records in the database.
     */
    public function get(): Collection
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Get the specified model record from the database.
     */
    public function getById(int $id): Model
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }

    public function getByColumn(string $item, string $column, array $columns = ['*']): Model|Builder|null
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    /**
     * Delete the specified model record from the database.
     *
     *
     * @throws Exception
     */
    public function deleteById(int $id): ?bool
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    /**
     * Set the query limit.
     *
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    public function paginate(int $limit = 25, array $columns = ['*'], string $pageName = 'page', int $page = null): LengthAwarePaginator
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Add a simple where clause to the query.
     *
     * @return $this
     */
    public function where(string $column, string $value, string $operator = '='): static
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @return $this
     */
    public function whereIn(string $column, mixed $values): static
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @return $this
     */
    public function with(string|array $relations): static
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery(): static
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad(): static
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses(): static
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) and ! empty($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes(): static
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses(): static
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }
}
