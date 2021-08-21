<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __invoke()
    {
        return StateResource::collection(State::get());
    }
}
