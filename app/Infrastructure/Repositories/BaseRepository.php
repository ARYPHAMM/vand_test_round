<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class BaseRepository implements RepositoryInterface
{
    //model muốn tương tác
    protected $model;
    //khởi tạo
    public function __construct()
    {
        $this->setModel();
    }
    //lấy model tương ứng
    abstract public function getModel();
    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }
    public function getAll($where = false)
    {
        return !$where ? $this->model->all() :  $this->model->where($where)->get();
    }
    public function find($id)
    {
        $result = $this->model->find($id);
        return $result;
    }
    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }
    public function update($id, $attributes = [])
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }
    public function delete($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }
    public function listBy(string $col, $value)
    {
        return $this->model->where($col, $value);
    }
    public function query()
    {
        return $this->model->query();
    }
    public function listOrderBy($sortBy = null, $sort = 'DESC')
    {
        return $this->model->query()->orderBy($sortBy, $sort);
    }
    public function findAllPagi($request = null, $sortBy = null, $sort = 'DESC', $limit = 15)
    {
        $query = $this->query();
        if ($request) $query->search($request);
        if (!empty($sortBy)) {
            $query->orderBy($sortBy, $sort);
        }
        return $query->paginate($limit);
    }
    public function whereMultipleAnd($data)
    {
        $result = $this->query()->where(function ($query) use ($data) {
            foreach ($data as $key => $value) {
                $query->where($key, $value);
            }
        });
        return $result;
    }
    public function getValidationSEO(Request $request)
    {
        return $request->only(
            'title',
            'desc',
            'keyword',
            'h1',
            'h2',
            'h3'
        );
    }
    public function findBy(string $col, $value)
    {
        return $this->model->where($col, $value)->first();
    }
    public function canValidate($rules = [], $messages = [])
    {
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails())
            return $validator;
        return false;
    }
    public function redirectWhenError($validator = false, $message = [], $input = true, $path = false)
    {
        $message_status = '';
        $message_content = '';
        $value = redirect();
        if (count($message) == 2) {
            $message_status = $message['status'];
            $message_content = $message['content'];
        }
        if ($input && $path) {
            $value = redirect($path)->withInput()->with($message_status, $message_content);
        }
        if (!$input && $path) {
            $value =   redirect($path)->with($message_status, $message_content);
        }
        if ($input && !$path) {
            $value =       redirect()->back()->withInput()->with($message_status, $message_content);
        }
        if (!$input && !$path) {
            $value =   redirect()->back()->with($message_status, $message_content);
        }
        if ($validator)
            $value = $value->withErrors($validator->errors());
        return $value;
    }
    public function getItem($id = null)
    {
        $cls =  get_class($this->model);
        return $this->find($id) ?? new $cls;
    }
    public function returnSource($source, $return_data)
    {
        if ($source == 'web')
            return redirect()->back()->with($return_data['type'], $return_data['msg']);
        if ($source == 'api') {
            if ($return_data['type'] == 'success') {
                if (isset($return_data['msg']) && !is_null($return_data['msg']))
                    return apiOk($return_data['msg']);
            }
            if ($return_data['type'] == 'error') {
                if (isset($return_data['msg']) && !is_null($return_data['msg']))
                    return apiError($return_data['msg']);
            }
            if (isset($return_data['msg_data']))
                return $return_data['msg_data'];
        }
    }
}
