<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class UserController extends Controller
{
    
    public function store(Request $req)
    {
        // Validate the incoming user registration data
        $validated = $this->validateUserRequest($req);

        // Create a new user record using the validated data
        $user = $this->createUser($validated);

        // TODO: Decide whether to send emails through a domain sender or API integration
        $this->sendNotificationEmails($user);

        // Return a formatted JSON response for the created user
        return $this->formatUserResponse($user);
    }


    public function index(Request $req)
    {
        // Extract search, sorting, and pagination parameters from the request
        $search = $req->get('search');
        $sortBy = $req->get('sortBy', 'created_at');
        $page = $req->get('page', 1);
        $currentUser = auth()->user(); // Get the authenticated user

        // Build the base query with optional search and sorting
        $query = $this->buildUserQuery($search, $sortBy);

        // Paginate the user list (10 items per page)
        $users = $query->paginate(10, ['*'], 'page', $page);

        // Transform the user list to include extra information (orders count, can_edit flag, etc.)
        $transformed = $this->transformUsers($users, $currentUser);

        // Return the paginated users as JSON
        return response()->json([
            'page' => $users->currentPage(),
            'users' => $transformed
        ]);
    }

    private function validateUserRequest(Request $request)
    {
        // Validate request data before creating a new user
        return $request->validate([
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8', 
            'name'      => 'required|min:3|max:50'
        ]);
    }

    private function createUser(array $data): User
    {
        // BUG: You must wrap this method body with curly braces
        // Also, passwords should be hashed before storing in the database
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']), // Use bcrypt() for security
            'name' => $data['name']
        ]);
    }

    private function buildUserQuery( $search,  $sortBy)
    {
        // Start the query with only active users
        $query = User::query()->where('active', true);

        if (!empty($search)) {
            // BUG: Remove the semicolon after where() – it breaks chaining
            // Use parentheses to group OR conditions properly
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting in descending order
        $query->orderBy($sortBy, 'desc');

        return $query;
    }

    private function transformUsers($users, $currentUser)
    {
        $result = [];

        // Loop through each user in the collection
        foreach ($users as $user) {
            // Count the number of orders associated with this user
            $ordersCount = Order::where('user_id', $user->id)->count();

            // Determine if the current user has permission to edit this user
            $canEdit = $this->canEditUser($currentUser, $user);

            // Transform and append to the response array
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

    private function formatUserResponse(User $user)
    {
        // Format a single user response for JSON output
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'created_at' => $user->created_at
        ]);
    }

    private function canEditUser($currentUser, $targetUser)
    {
        // If no authenticated user, editing is not allowed
        if (!$currentUser) {
            return false;
        }

        // Admins can edit all users
        if ($currentUser->role === 'admin') {
            return true;
        }

        // Managers can edit regular users
        if ($currentUser->role === 'manager' && $targetUser->role === 'user') {
            return true;
        }

        // Users can edit their own profile
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Default: no permission
        return false;
    }

    private function sendNotificationEmails(User $user)
    {
       // Placeholder: This method should trigger an event or dispatch a mail job
       // Example: event(new UserRegistered($user));
       // Keep async (queued) for performance in production
    }

}
