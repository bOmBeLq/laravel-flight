<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "0.7",
    title: "Laravel Flight"
)]
#[OA\Server(
    url: "http://127.0.0.1:5543/",
    description: "Dev env"
)]
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
