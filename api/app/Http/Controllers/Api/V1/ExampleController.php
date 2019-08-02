<?php


namespace App\Http\Controllers\Api\V1;


use App\Models\User;

class ExampleController extends BaseController
{
    public function example()
    {
        return $this->jsonSuccess(['user' => User::query()->first()]);
    }

}
