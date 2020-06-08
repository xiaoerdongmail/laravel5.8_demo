<?php
/**
 * Created by PhpStorm.
 * User: EdwardShaw
 * Date: 2020/6/8
 * Time: 18:01
 * Description:
 */
namespace App\Repositories\Test;



use App\Models\Test\Test;
use App\Repositories\Repository;

class TestRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Test();

    }
}