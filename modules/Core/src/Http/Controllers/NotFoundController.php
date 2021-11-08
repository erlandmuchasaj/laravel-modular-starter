<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class NotFoundController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return response()->view('errors.404', [], 404);
    }
}

