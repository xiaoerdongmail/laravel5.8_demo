<?php
/**
 * Created by PhpStorm.
 * User: EdwardShaw
 * Date: 2020/6/4
 * Time: 18:38
 * Description:
 */

namespace App\Http\Controllers\Api\Test;


use App\Http\Controllers\Controller;
use App\Services\Test\TestService;
use App\Vo\ResultVo;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class Erdongtest  extends Controller
{
    public function __construct()
    {
        $this->service = new TestService();
    }
    public function test1()
    {
        $name = Input::get('name');
        echo "123\n";
        echo $name;
        exit(1);
    }


    public function erdongtest(Request $request){
        $data = $this->service->erdongTest($request);
        return ResultVo::success('获取列表成功！ ', $data);
    }
}