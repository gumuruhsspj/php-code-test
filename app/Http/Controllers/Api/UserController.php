<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class UserController extends Controller
{
    /**
     * Handle new user registration process.
     * Steps: validate input → create user → send notification → return response.
     */
    public function store(Request $req)
    {
        // 1. Validate incoming request data (email, password, name)
        $validated = $this->validateUserRequest($req);

        // 2. Create a new user record using validated data
        $user = $this->createUser($validated);

        // 3. (Optional) Send email notification to the user
        $this->sendNotificationEmails($user);

        // 4. Return JSON response with created user info
        return $this->formatUserResponse($user);
    }

    /**
     * Display paginated user list with optional search and sorting.
     * Steps: get params → build query → paginate → transform → respond.
     */
    public function index(Request $req)
    {
        // Get optional search keyword, sort field, and current page number
        $search = $req->get('search');
        $sortBy = $req->get('sortBy', 'created_at');
        $page = $req->get('page', 1);

        // Get current authenticated user (for edit permission logic)
        $currentUser = auth()->user();

        // Build the base query (filter active users, apply search & sorting)
        $query = $this->buildUserQuery($search, $sortBy);

        // Get paginated results (10 per page)
        $users = $query->paginate(10, ['*'], 'page', $page);

        // Add extra info to each user (orders count & permission flag)
        $transformed = $this->transformUsers($users, $currentUser);

        // Return paginated JSON response
        return response()->json([
            'page' => $users->currentPage(),
            'users' => $transformed
        ]);
    }

    /** Validate request data before user creation */
    private function validateUserRequest(Request $request)
    {
        return $request->validate([
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'name'      => 'required|min:3|max:50'
        ]);
    }

    /** Create user record in database (password is securely hashed) */
    private function createUser(array $data): User
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'name' => $data['name']
        ]);
    }

    /** Build user query with active filter, search, and sorting */
    private function buildUserQuery($search, $sortBy)
    {
        $query = User::query()->where('active', true);

        if (!empty($search)) {
            // Apply search by name or email
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort results by given field (default: newest first)
        $query->orderBy($sortBy, 'desc');
        return $query;
    }

    /** Transform user data before returning response */
    private function transformUsers($users, $currentUser)
    {
        $result = [];

        foreach ($users as $user) {
            // Count user’s total orders
            $ordersCount = Order::where('user_id', $user->id)->count();

            // Check if current user has permission to edit this user
            $canEdit = $this->canEditUser($currentUser, $user);

            // Format user data with additional info
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

    /** Format single user object into JSON */
    private function formatUserResponse(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'created_at' => $user->created_at
        ]);
    }

    /** Define user edit permission logic */
    private function canEditUser($currentUser, $targetUser)
    {
        if (!$currentUser) return false;             // Not logged in
        if ($currentUser->role === 'admin') return true;  // Admin: can edit all
        if ($currentUser->role === 'manager' && $targetUser->role === 'user') return true; // Manager: edit users
        if ($currentUser->id === $targetUser->id) return true; // User: edit self

        return false; // Default: no permission
    }

    /** Placeholder for sending notification emails */
    private function sendNotificationEmails(User $user)
{
    // This function sends a notification email after user registration.
    // The email sending method depends on your MAIL_MAILER configuration in .env
    // Options:
    // 1. SMTP using company domain
    // 2. Third-party service (Mailgun, SendGrid, etc.)

    // modify the .env first before proceed

    try {
        // Send email to the new user
        // activate this one below as needed
        /*Mail::to($user->email)->send();

      
        $adminEmail = config('mail.admin_address'); // define in .env
        if ($adminEmail) {
            Mail::to($adminEmail)->send();
        } */

    } catch (\Exception $e) {
        // Log the error for debugging; email failure won't block user creation
        \Log::error('Failed to send notification email: ' . $e->getMessage());
    }
}

}
