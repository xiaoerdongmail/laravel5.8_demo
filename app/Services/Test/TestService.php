<?php
/**
 * Created by PhpStorm.
 * User: EdwardShaw
 * Date: 2020/6/8
 * Time: 16:43
 * Description:
 */

namespace App\Services\Test;


use App\Exceptions\CustomException;
use App\Models\Test\Test;
use App\Services\Service;
use App\Repositories\Test\TestRepository;

class TestService extends Service
{
    public function __construct()
    {
        $this->repository = new TestRepository();
    }

    /* *
     * @function 查询数据
     *
     * */
    public function erdongTest( $request){
        $code = $request->input('code', '');
        $status = $request->input('status', '');

        if(empty($code)){
            throw new CustomException (500, "code 没有参数传入 ");
        }

        $model = Test::query();
        $total = $model->count();

        return  $data = ['list' => [1,2,3],'count' => $total];
    }
}