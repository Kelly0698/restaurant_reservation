<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Attachment;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\UserCreated;

class UserController extends Controller
{

    public function adminDashboard()
    {
        return view('dashboard'); 
    }

    public function userDashboard()
    {
        $roles = Role::all();
        $users = User::all();
        $restaurants = Restaurant::all();
        $attachments = Attachment::all();

        return view('user_dashboard', [
            'roles' => $roles,
            'users' => $users,
            'restaurants' => $restaurants,
            'attachments' => $attachments,
        ]);
    }

    public function viewRestaurant($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $attachments = Attachment::where('restaurant_id', $id)->get();
        
        return view('view_restaurant', compact('restaurant', 'attachments')); 
    }

    public function index()
    {
        $role = Role::all();
        $user = User::all();
        return view('admin.admin_user', compact('role','user')); 
    }

    public function adminCreate(Request $req, User $user)
    {
        $data = new User;
        $data->name = $req->input('name');
        $data->role_id = $req->input('role_id');
        $data->email = $req->input('email');
        $data->phone_num = $req->input('phone_num');
    
        $generatedPassword = Str::random(8);
        $data->password = bcrypt($generatedPassword);
    
        if ($req->hasFile('profile_pic')) {
            $imagePath = $req->file('profile_pic')->store('user_img', 'public');
            $data->profile_pic = $imagePath;
        }
    
        $role = Role::where('id', $data->role_id)->first();
        if ($role) {
            $data->role_id = $role->id;
            $data->save();
    
            Mail::to($data->email)->send(new UserCreated($data, $generatedPassword));
    
            $response = [
                'status' => 'success',
                'data' => $data
            ];
    
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Role not found'
            ];
    
            return response()->json($response, 404);
        }
    }

    public function create(Request $req, User $user)
    {
        // Check if role_id 5 exists
        $role = Role::find(5);
    
        if (!$role) {
            // Role with id 5 does not exist
            $response = [
                'status' => 'failed',
                'message' => 'Role with id 5 does not exist'
            ];
    
            return response()->json($response, 404);
        }
    
        // Role with id 5 exists, proceed with user creation
        $data = new User;
        $data->name = $req->input('name');
        $data->role_id = 5;
        $data->email = $req->input('email');
        $data->password = bcrypt($req->input('password')); // Hash the password directly
        $data->phone_num = $req->input('phone_num');
    
        if ($req->hasFile('profile_pic')) {
            $imagePath = $req->file('profile_pic')->store('user_img', 'public');
            $data->profile_pic = $imagePath;
        } else {
            // Set default profile picture path if no file is uploaded
            $data->profile_pic = 'public\assets\dist\img\defaultPic';
        }
    
        // Save the user
        $data->save();
    
        $response = [
            'status' => 'success',
            'message' => 'User created successfully'
        ];
    
        return response()->json($response, 200);
    }
    

    public function userRegister()
    {
        $role = Role::all();
        $user = User::all();
        return view('user_register', compact('role','user')); 
    }

    public function login(Request $request)
    {
        Auth::guard('restaurant')->logout();

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if(Auth::attempt($credentials))
        {
            $user=Auth::user();
            $name = $user->name;
            $id = $user->id;
            $email = $user->email;
            $role = $user->role_id;
            session(['name'=>$name]);
            session(['email'=>$email]);
            session(['id'=>$id]);
            session(['role'=>$role]);
            return redirect('dashboard');
        }
        return redirect('/login')->with('status', 'failed');
    }

    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/login');
    }

    public function forgotPassword()
    {
        return view('forgot_password');
    }

    public function validateUserForm(Request $request)
    {
        // Get the User name from the request
        $userName = $request->input('name');
        $userEmail = $request->input('email');
    
        // Check if the User name or email already exists in the database
        $duplicateUser = User::where('name', $userName)->orWhere('email', $userEmail)->first();
 
        if ($duplicateUser) {
            // User name or email already exists, return error response
            return response()->json(['status' => 'error', 'message' => 'This user name is already exist!'], 500);
        } else {
            // User name or email does not exist, return success response
            return response()->json(['status' => 'success', 'message' => 'User name is unique'], 200);
        }
    }

    public function show($id)
    {
        // Fetch the specific user by ID
        $user = User::with('role')->find($id);
    
        // Check if the user exists
        if (!$user) {
            // Return a JSON response with a 404 status code if the user is not found
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Fetch additional data if needed
        $role = Role::all();
        $data = User::all();
    
        // Return a JSON response with the user and additional data
        return view('admin.admin_show_user', compact('role','user','data'));
    }

    public function getUser($id)
    {
        // Fetch the user details based on the provided ID
        $user = User::with('role')->findOrFail($id);

        // You can also fetch additional data such as roles, countries, etc. if needed

        // Pass the user details to the view for editing
        return view('admin.admin_user', compact('user'));
    }
    
    public function edit(Request $req, User $user)
    {
        $user->name = $req->input('name');
        $user->role_id = $req->input('role_id');
        $user->email = $req->input('email');
        $user->phone_num = $req->input('phone_num');
        $role = Role::where('id', $user->role_id)->first();
        if($role)
        {
            
            $user->role_id = $role->id;
            $user->save();
    
            $response = [
                'status' => 'success',
                'data' => $user
            ];
    
            return response()->json($response, 200);
        }
        else
        {
            $response = [
                'status' => 'error',
                'message' => 'Role not found'
            ];
    
            return response()->json($response, 404);
        }
    }

    public function updatePic(Request $req, User $user)
    {
        if (!$req->hasFile('profile_pic')) {
            return response()->json(['error' => 'No image was uploaded.'], 400);
        }

        $oldImagePath = $user->profile_pic;
        $newImagePath = $req->file('profile_pic')->store('user_img', 'public');

        $user->profile_pic = $newImagePath;
        $user->save();

        // delete old image from storage
        if ($oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::delete($oldImagePath);
        }

        return response()->json(['success' => 'Image saved successfully'], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function user_profile()
    {
        $role = Role::all();
        $user = User::all();
        return view('user_profile', compact('role','user')); 
    }

    public function makeReservation(Request $request)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Get the restaurant ID from the request or any other means
        $restaurantId = $request->input('restaurant_id');

        // Create a new reservation instance
        $reservation = new Reservation;
        $reservation->user_id = $userId;
        $reservation->restaurant_id = $restaurantId;
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->party_size = $request->input('party_size');
        $reservation->remark = $request->input('remark');
        $reservation->status = "Pending";
        
        $reservation->save();

        // // Send email to user
        // Mail::to($reservation->user->email)->send(new ReservationMade($reservation, 'user'));

        // // Send email to restaurant
        // Mail::to($reservation->restaurant->email)->send(new ReservationMade($reservation, 'restaurant'));

        return response()->json(['status' => 'success', 'message' => 'Reservation successfully made'], 200);
    }


    public function reservationRecord()
    {
        if (Auth::guard('restaurant')->check()) {
            $restaurant = Auth::guard('restaurant')->user();
            $reservations = $restaurant->reservations()->where('status', 'Pending')->get();
        } elseif (Auth::check()) {
            $user = Auth::user();
            if ($user->role_id === 5) {
                $reservations = $user->reservations()->whereIn('status', ['Pending', 'Approved', 'Rejected'])->get();
            } elseif ($user->role_id === 4) {
                $reservations = Reservation::all();
            } else {
                // Handle other cases or restrict access
                abort(403, 'Unauthorized action.');
            }
        } else {
            // Handle guest users or restrict access
            abort(403, 'Unauthorized action.');
        }
    
        // Pass the reservation records to the view
        return view('reservation_record', compact('reservations'));
    }

    public function cancelReservation($id)
    {
        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);
        
        // Delete the reservation
        $reservation->delete();
        
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reservation canceled successfully');
    }

    
}
