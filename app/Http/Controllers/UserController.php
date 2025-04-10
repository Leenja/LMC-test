<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
    public function register(Request $request){
        $data = $request->validate([
            'Name' => 'required|string',
            'Email' => 'required|email',
            'Password' => 'required|string|min:8'
        ]);
        return response()->json($this->userService->register($data));
    }

    public function login(Request $request){

    }

    public function logout(){

    }

    public function editAccount(Request $request){
        $request->validate([]);
    }

    public function removeAccount(Request $request){
        $request->validate([]);
    }

    public function viewAnnouncement() {

    }
}
