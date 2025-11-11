<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class UserController extends Controller
{
    

    public function store(Request $req){

       $validated = $this->validateUserRequest($req);

        $user = $this->createUser($validated);

        // ini mo pake domain sender / ada API khusus?
        $this->sendNotificationEmails($user);

        return $this->formatUserResponse($user);

    }


    public function index(Request $req){

        $search = $req->get('search');
        $sortBy = $req->get('sortBy', 'created_at');
        $page = $req->get('page', 1);
        $currentUser = auth()->user();


        $query = $this->buildUserQuery($search, $sortBy);

        $users = $query->paginate(10, ['*'], 'page', $page);

        $transformed = $this->transformUsers($users, $currentUser);

        return response()->json([
            'page' => $users->currentPage(),
            'users' => $transformed
        ]);



    }

    private function validateUserRequest(Request $request)
    {
        return $request->validate([
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'name'      => 'required|min:3|max:50'
        ]);
    }

    private function createUser($data){

        return User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name']
        ]);

    }

    private function buildUserQuery($search, $sortBy){

        $query = User::query()->where('active', true);

        if(!empty($search)){
            $query->where('name', 'like', '%$search%')
                  ->orWhere('email','like', '%$search%');
        }

        $query->orderBy($sortBy, 'desc');
        return $query;

    }

    private function transformUsers($users, $currentUser){

        $result = [];

        foreach($users as $user){
            $ordersCount = Order::where('user_id', $user->id)->count();
            $canEdit = $this->canEditUser($currentUser, $user);

            $result[] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'orders_count' => $ordersCount,
                'can_edit'  => $canEdit
            ];

         }

         return $result;

    }

    private function formatUserResponse(User $user){

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'created_at' => $user->created_at
        ]);

    }

    private function canEditUser($currentUser, $targetUser){

        if(!$currentUser){
            return false;
        }

        if($currentUser->role === 'admin'){
            return true;
        }

        if($currentUser->role === 'manager' && $targetUser->role === 'user'){
            return true;
        }

        if($currentUser->id === $targetUser->id){
            return true;
        }

    }

    private function sendNotificationEmails(User $user){

        // panggil mo pake API 
        // mo pake Domain sender
        // atau mo pake apa nih?

    }

}
