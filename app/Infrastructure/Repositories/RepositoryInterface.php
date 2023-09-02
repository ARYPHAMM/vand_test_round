<?php

namespace App\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll();
    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);
    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);
    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, $attributes = []);
    public function getValidationSEO(Request $request);
    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);
    public function listBy(string $col, $value);
    public function query();
    public function findAllPagi($request = null, $sortBy = null, $sort = 'DESC', $limit = 15);
    public function whereMultipleAnd($data);
    public function findBy(string $col, $value);
    public function listOrderBy();
    public function canValidate($rules = [], $model = []);
    public function redirectWhenError($error = false, $message = [], $input = true, $path = false);
    public function getItem($id = null);
    public function returnSource($source, $return_data);
}
