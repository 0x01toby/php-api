<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-15
 * Time: 下午2:39
 */

namespace App\Services;


use Illuminate\Http\Request;
use App\Models\Example;

class ExampleService extends Service
{

    public function add(Request $request)
    {
        $example = new Example(['title' => $request->input('title')]);

        $example->save();

        $this->asyncAddRead($example);

        return $example;
    }


    public function AddRead(Example $example)
    {
        $example->read_count ++;
        $example->save();
    }

}
