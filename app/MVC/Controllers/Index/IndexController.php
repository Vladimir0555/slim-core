<?php

namespace App\MVC\Controllers\Index;

use \App\Core\CoreController;
use App\MVC\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class IndexController
 * @package App\MVC\Controllers\Index
 */
class IndexController extends CoreController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'index\index\index.twig', []);
    }
}
