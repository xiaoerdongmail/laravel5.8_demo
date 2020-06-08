<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
//use EloquentFilter\Filterable;

/**
 *  基类model
 * Class BaseModel
 *
 * @package App\Models
 */
class BaseModel extends Model
{
    //use Filterable,SoftDeletes;
    //use SoftDeletes;
    /**
     * 启用状态
     */
    const ENABLED_DISABLE = 0;                 //禁用
    const ENABLED_ENABLE = 1;                 //启用

    /**
     *
     */
    const ENABLED_DESCRIBE_MAP = [
        self::ENABLED_ENABLE  => '已启用',
        self::ENABLED_DISABLE => '已禁用',
    ];

    /**
     * 默认选项
     */
    const NOT_DEFAULT = 0;
    const DEFAULT = 1;

    /**
     * 默认选项值映射
     */
    const IS_DEFAULT_DESCRIBE_MAP = [
        self::DEFAULT     => '是',
        self::NOT_DEFAULT => '否',
    ];

    /**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function __construct(array $attributes = [])
    {
        $this->perPage = (int)Request::get('per_page', config('app.pageSize'));
        parent::__construct($attributes);
    }


    public function getIsDefaultDescribeAttribute()
    {
        return self::IS_DEFAULT_DESCRIBE_MAP[$this->is_default] ?? null;
    }

    public function getEnabledDescribeAttribute()
    {
        return self::ENABLED_DESCRIBE_MAP[$this->enabled] ?? null;
    }


    /**
     * 创建者
     */
    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

    /**
     * 更新者
     */
    public function updater()
    {
        return $this->hasOne(User::class, 'id', 'updater_id');
    }

    /**
     * @todo  start 单表共同操作 新增开始
     * @author yefujun
     * @time 2019-12-12
     */

    /**
     * @todo 获取一条数据
     * @param array $where
     * @param string $filed
     * @param array $order
     * @return mixed
     */
    public function getOne( $where = [],$filed = "*",$order = [] ){
        $obj = $this->setWhere( $this,$where );
        if( $filed != "*" )
        {
            if( ! is_array( $filed ) )
                $filed = explode( ',' ,$filed );
            $obj = $obj->select( $filed );
        }
        if( $order )
        {
            if( ! is_array( $order[0] ) )
            {
                $order = [ $order ];
            }
            foreach ( $order as $val )
            {
                $obj =  $obj->orderBy( $val[0],$val[1] );
            }
        }
        return $obj->first();
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
        $obj = $this->setWhere( $this,$where );
        $obj = $this->getFieldAndPaginate( $obj,$field,$page,$per_page,$order );
        return $obj->get()->toArray();
    }

    /**
     * @todo 对In特殊处理
     * @param $obj
     * @param $where
     * @return mixed
     */
    public function setWhere( $obj, $where = [] ){
        if( $where )
        {
            foreach ( $where as $key=>$val )
            {
                if( is_array( $val ) && count( $val ) > 2 && strtolower( $val[1] )== 'in' )
                {
                    $obj = $obj->whereIn( $val[0],$val[2] );
                    unset( $where[ $key ] );
                }
            }
        }
        return $obj->where( $where );
    }

    /**
     * @param $obj
     * @param $field
     * @param $page
     * @param $per_page
     * @param $order
     * @return mixed
     */
    protected function getFieldAndPaginate( $obj,$field,$page,$per_page,$order ){
        if( $field != "*" )
        {
            if( ! is_array( $field ) )
                $field = explode( ',' ,$field );
            $obj = $obj->select( $field );
        }
        if( 'all' != $page )
        {
            $page = ( ( $page - 1 ) * $per_page ) ?? 0 ;
            $obj = $obj->offset($page)->limit($per_page);//paginate
        }
        if( $order )
        {
            if( ! is_array( $order[0] ) )
            {
                $order = [ $order ];
            }
            foreach ( $order as $val )
            {
                $obj =  $obj->orderBy( $val[0],$val[1] );
            }
        }
        return $obj;
    }

    /**
     * @todo 批量插入数据
     * @param array $data
     * @return mixed
     */
    public function addAll( $data = [] ){
        return $this->insert( $data );
    }
    /**
     * @todo 修改数据
     * @param array $where
     * @param array $data
     * @return mixed
     */
    public function edit( $where = [] , $data = [] ){
        return $obj = $this->setWhere( $this,$where )->update( $data );
    }

    /**
     * @todo 删除数据
     * @param $where
     * @return mixed
     */
    public function del( $where )
    {
        return $obj = $this->setWhere( $this,$where )->delete();
    }

    /**
     * @todo 统计条数
     * @param $where
     * @return mixed
     */
    public function counts( $where = [] ){
        return $obj = $this->setWhere( $this,$where )->count();
    }

    /** end 单表共同操作 新增结束 */
}
