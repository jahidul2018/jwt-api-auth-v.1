<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;



class UserController extends Controller
{
    
    // user controller 

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        
    }

    public function me(){

        // use auth()->user() to get authenticated user \data_fill

        return response()->json([
            'meta' =>[
                'code' => 200,
                'status' => 'success',
                'message' => 'User has been fatched successfully!',
            ],
            'data' =>[
                'user' => auth()->user(),
            ],
        ]);
    }

    

}