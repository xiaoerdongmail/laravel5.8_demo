<?php
/**
 * Created by PhpStorm.
 * User: EdwardShaw
 * Date: 2020/6/5
 * Time: 15:46
 * Description:
 */

namespace App\Services;

use Auth;
/**
 * 所有service的基类
 * Class Service
 *
 * @package App\Services
 */
class Service
{
    protected $repository;

    public function index(array $data = [])
    {
        return $this->repository->paginate($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function create(array $data = [])
    {
        return $this->repository->create($data);
    }

    public function store(array $data = [])
    {
        $data['creator_id'] = Auth::id();
        $data['updater_id'] = Auth::id();
        return $this->repository->create($data);
    }

//    public function enabled($id)
//    {
//        $model = $this->repository->findOrFail($id);
//
//        $update = [
//            'enabled' => BaseModel::ENABLED_ENABLE == $model->enabled ? BaseModel::ENABLED_DISABLE : BaseModel::ENABLED_ENABLE,
//            'updater_id' => Auth::id(),
//        ];
//        return $this->repository->update($model, $update);
//    }

    public function update($id, array $data)
    {

        $model = $this->repository->findOrFail($id);

        $data['updater_id'] = Auth::id();

        return $this->repository->update($model, $data);
    }

    /**
     * 主要用处是下拉，显示已经启用的数据
     * @param array $data
     * @return mixed
     */
    public function streamlining(array $data = [])
    {
        return $this->repository->paginate($data,[]);
    }

    public function edit( $where = [],$data = [] ){
        return $this->repository->edit( $where,$data );
    }

    public function counts(array $where = [])
    {
        return $this->repository->counts($where);
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
    /**
     * @todo 获取一条数据
     * @param array $where
     * @param string $filed
     * @param array $order
     * @return mixed
     */
    public function getOne( $where = [],$filed = "*",$order = [] ){
        return $this->repository->getOne($where,$filed,$order);
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
        return $this->repository->getList($where,$field,$page,$per_page,$order,$group);
    }

}