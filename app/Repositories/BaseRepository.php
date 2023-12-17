<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve all data of repository.
     *
     * @param  array  $columns
     */
    public function all($columns = ['*']): mixed
    {
        return $this->model->all($columns);
    }

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param  null  $limit
     * @param  array  $columns
     */
    public function paginate($limit = null, $columns = ['*']): mixed
    {
        return $this->model->query()->select($columns)->latest()->paginate($limit);
    }

    /**
     * Save a new entity in repository.
     */
    public function create(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    /**
     * Return an entity.
     */
    public function findOrNull(int $id): mixed
    {
        return $this->model->query()->find($id);
    }

    /**
     * Update an entity.
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->findOrNull($id);

        if (is_null($model)) {
            return false;
        }

        return $model->update($data);
    }

    /**
     * Delete an entity.
     */
    public function delete(int $id): ?bool
    {
        $entity = $this->findOrNull($id);

        if (is_null($entity)) {
            return null;
        }

        return $entity->delete();
    }

    /**
     * Update or create an entity.
     */
    public function updateOrCreate(array $attributes, array $values): mixed
    {
        return $this->model->query()->updateOrCreate($attributes, $values);
    }

    /**
     * Get entity.
     */
    public function get(array $condition = [], bool $takeOne = true): mixed
    {
        $result = $this->model->query()->where($condition);

        if ($takeOne) {
            return $result->first();
        }

        return $result->get();
    }
}
