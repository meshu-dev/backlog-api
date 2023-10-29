<?php

namespace App\Repositories;

use App\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;

/**
 * Used to perform CRUD operations.
 */
abstract class ModelRepository implements Repository
{
    private const ROW_LIMIT = 20;

    protected $model;

    /**
     * Constructor for class.
     *
     * @param Model $model Used to get and set data.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create data entry from array to JSON.
     *
     * @param array $params The parameteres for data entry.
     *
     * @return array|null
     */
    public function add(array $params)
    {
        $row = $this->model->create($params);

        if (empty($row) === false) {
            return $row;
        }
        return null;
    }

    /**
     * Read data entry from given ID.
     *
     * @param mixed $id The ID for specific data entry.
     *
     * @return array|null
     */
    public function get($id)
    {
        $row = $this->model->find($id);

        if (empty($row) === false) {
            return $row;
        }
        return null;
    }

    /**
     * Read all data entries to an array.
     *
     * @param array $params Used to filter data entries.
     *
     * @return array
     */
    public function getAll(array $params = [])
    {
        return $this->model->all();
        
        /*
        $params = $this->model->verifySearchable($params);

        if (isset($params['offset']) === true) {
            $offset = (int) $params['offset'];
            unset($params['offset']);
        } else {
            $offset = 0;
        }

        if (isset($params['limit']) === true) {
            $limit = (int) $params['limit'];
            unset($params['limit']);
        } else {
            $limit = self::ROW_LIMIT;
        }

        if (isset($params['order']) === true) {
            $orderField = key($params['order']);
            $sort = current($params['order']);
            unset($params['order']);
        } else {
            $orderField = 'id';
            $sort = 'desc';
        }

        $rows = $this->model
            ->where($params)
            ->skip($offset)
            ->take($limit)
            ->orderBy($orderField, $sort)
            ->get();

        if (empty($rows) === false) {
            return $rows;
        } */
        return null;
    }

    /**
     * Update existing data entry from given ID.
     *
     * @param mixed $id The ID for specific data entry.
     * @param array $params Parameters used to update data entry.
     *
     * @return array|null
     */
    public function edit($id, array $params)
    {
        $row = $this->model->where('id', '=', $id)->update($params);

        if (empty($row) === false) {
            return $row;
        }
        return null;
    }

    /**
     * Delete existing data entry from given ID.
     *
     * @param mixed $id The ID for specific data entry.
     *
     * @return void
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function getTotal(array $params = [])
    {
        $params = $this->model->verifySearchable($params);

        if (isset($params['offset']) === true) {
            unset($params['offset']);
        }

        if (isset($params['limit']) === true) {
            unset($params['limit']);
        }

        if (isset($params['order']) === true) {
            unset($params['order']);
        }

        return $this->model->where($params)->count();
    }
}
