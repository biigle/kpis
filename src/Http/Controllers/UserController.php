<?php

namespace Biigle\Modules\Kpis\Http\Controllers;

use Biigle\Modules\Kpis\User;
use Biigle\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getUser(int $year, int $month)
    {
        //TODO: add check for user role
        return User::getUser($year, $month);

    }

    public function getUniqueUser(int $year, int $month)
    {
        //TODO: add check for user role
        return User::getUniqueUser($year, $month);
    }
}
