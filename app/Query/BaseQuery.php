<?php

namespace App\Query;

use Illuminate\Support\Facades\App;
use App\Models\ArabicGrammar;

class BaseQuery
{
    /**
     * @var model
     */
    protected $model;

    /**
     * Get list
     *
     * @return dataObject
     */
    public function getData()
    {
        return $this->model->get();
    }

    public function getActiveData()
    {
        return $this->model->where('status', 1)->get();
    }

    /**
     * @param $request
     * @return insert_id
     */
    public function create($request)
    {
        return $this->model->create($request);
    }

    /**
     * Get single data object
     *
     * @param int $id
     * @return dataObject
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Update data
     *
     * @param $request
     * @param int $id
     * @return boolean
     */
    public function update($request, $id)
    {
        return $this->model->find($id)->update($request);
    }

    /**
     * Delete data
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * get list
     *
     * @return array
     */
    public function lists()
    {
        return $this->model->orderBy('title', 'asc')->pluck('title', 'id');
    }

    public function listByStatus()
    {
        return $this->model->where('status', 1)->orderBy('name', 'asc')->get(['name', 'id']);
    }


    public function count(){
        return $this->model->count();
    }

}
