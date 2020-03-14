<?php

namespace App\Repository\Eloquent;

use App\Repository\BaseRepositoryInterface;
use App\Search\Search;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllWithPaging(Request $request, Search $searchClass = null)
    {
        $page = $request->get('page');
        $pageSize = $request->get('pageSize');
        $ret = [];
        $tot = $this->model::count();
        if (null != $searchClass) {
            $ret = $searchClass::apply($request);
        } else {
            $ret = $this->model->all();
        }
        $ret = $ret->slice(($page - 1) * $pageSize, $pageSize)->values();

        return new LengthAwarePaginator(
            $ret,
            $total = $tot,
            $pageSize,
            $page
        );
    }

    public function getById($id)
    {
        return $this->model->where($this->getIdStr(), $id)->first();
    }

    public function delete($id)
    {
        return $this->model->delete($this->getById($id));
    }

    abstract protected function getIdStr();
}
