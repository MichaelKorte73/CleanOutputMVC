<?php

namespace App\Controller;

use CHK\Core\Controller;

class RedirectController extends Controller
{
    public function go()
    {
        // später: slug aus DB auflösen
        return "RedirectController::go() – Slug-Redirect kommt später.";
    }
}