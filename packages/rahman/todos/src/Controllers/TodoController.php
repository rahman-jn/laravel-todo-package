<?php

namespace Rahman\Todos\Controllers;
use App\Http\Controllers\Controller;


class TodoController extends Controller{

    public function index(){
        return "TODO index";
        return view('todos::create');

    }
}


?>