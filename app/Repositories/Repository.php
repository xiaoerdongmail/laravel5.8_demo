<?php


namespace App\Repositories;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 所有repository的基类
 * Class Repository
 *
 * @package App\Repositories
 */
class Repository
{
    protected $model;



    /**
     * 删除数据,只能删除未审核数据
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $model = $this->findOrFail($id);
        return $model->delete();
    }

    /**
     * 更新数据
     *
     * @param       $model
     * @param array $data
     *
     * @return mixed
     */
    public function update($model, array $data)
    {
        $model->update($data);
        return $model;
    }

    /**
     * @param $whereOrId
     * @param array $with 关联信息
     * @return mixed
     */
    public function show($whereOrId,array $with=[])
    {
        return $this->model->when($with, function ($query) use ($with) {
            return $query->with($with);
        //是数组的执行方法1其他条件查询，否则执行方法2id查询
        })->when(is_array($whereOrId),
            function ($query) use ($whereOrId) {
                return $query->filter($whereOrId);
            },
            function ($query) use ($whereOrId) {
                return $query->where('id', $whereOrId);
            }
            )->firstOrFail();
    }

    /**
     * 创建数据
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        if( count($data) != count($data,1) )
            return $this->model->addAll($data);//二维数组 批量插入
        else
            return $this->model->create($data);//一维数组
    }
    /**
     * 如果有一个设置为默认，其他设置为非默认
     *
     * @return mixed
     */
    public function unDefault()
    {
        $this->model->where(['is_default'=>BaseModel::DEFAULT])->update(['is_default' => BaseModel::NOT_DEFAULT,'updater_id' => Auth::id()]);
    }

    public function firstOrFail(array $data = [])
    {
        return $this->model->filter($data)->firstOrFail();
    }

    public function first(array $data = [])
    {
        return $this->model->filter($data)->first();
    }

    public function exists(array $data = [])
    {
        return $this->model->filter($data)->exists();
    }

    public function doesntExist(array $data = [])
    {
        return $this->model->filter($data)->doesntExist();
    }


    /**
     * 按ID查询数据
     *
     * @param $id
     *
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }


    /**
     * 分页数据
     *
     * @param array $data
     * @param null $with
     *
     * @return mixed
     */
    public function paginate(array $data = [], $with = null)
    {
        return $this->model->filter($data)->when($with, function ($query) use ($with) {
            return $query->with($with);
        })->orderByDesc('id')->when(Request::get('page'), function ($query) {
            return $query->paginate();
        }, function ($query) {
            return $query->get();
        });
    }


    /**
     * 获取所有审核后的数据,主要做下拉使用
     *
     * @param array $data
     * @param null $select
     * @return mixed
     */
    public function all(array $data = [], $select = null)
    {
        return $this->model->filter($data)->when($select, function ($query) use ($select) {
            return $query->select($select);
        })->orderByDesc('id')->get();
    }

    /**
     * @todo 编辑
     * @param array $where
     * @param array $data
     * @return mixed
     */
    public function edit( $where = [],$data = [] ){
        return $this->model->edit( $where,$data );
    }
    /**
     * 统计
     * @param array $where
     * @return mixed
     */
    public function counts(array $where = [])
    {
        return $this->model->counts($where);
    }

    /**
     * @todo 获取一条数据
     * @param array $where
     * @param string $filed
     * @param array $order
     * @return mixed
     */
    public function getOne( $where = [],$filed = "*",$order = [] ){
        return $this->model->getOne($where,$filed,$order);
    }

    /**
     * @todo 获取列表数据
     * @param array $where
     * @param string $field
     * @param string $page
     * @param int $per_page
     * @param array $order
     * @param array $group
     * @return mixed
     */
    public function getList( $where =  [],$field = "*",$page = "all",$per_page = 10,$order = [],$group = [] ){
        return $this->model->getList($where,$field,$page,$per_page,$order,$group);
    }
}