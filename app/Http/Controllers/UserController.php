<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Attachment;
use App\Models\Rating;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Holiday;
use App\Mail\UserCreated;
use App\Mail\ForgotPassword;

class UserController extends Controller
{

    public function adminDashboard()
    {
        if (auth()->guard('restaurant')->check()) {
            // Get the authenticated restaurant's ID
            $restaurantId = auth()->guard('restaurant')->user()->id;

            // Get all reservations for today for the authenticated restaurant
            $reservations = Reservation::where('restaurant_id', $restaurantId)
                ->whereDate('date', Carbon::today())
                ->where('status', 'Approved')
                ->get();

            // Initialize an array to store counts for each hour
            $reservationCounts = array_fill(0, 24, 0);

            // Loop through reservations and count reservations for each hour
            foreach ($reservations as $reservation) {
                $hour = Carbon::parse($reservation->time)->hour;
                $reservationCounts[$hour]++;
            }

            // Count reservation requests for the authenticated restaurant
            $reservationRequests = Reservation::where('status', 'Pending')
                ->where('restaurant_id', $restaurantId)
                ->count();

            // Count today's approved reservations for the authenticated restaurant
            $todaysApprovedReservationsCount = Reservation::whereDate('date', Carbon::today())
                ->where('status', 'Approved')
                ->where('restaurant_id', $restaurantId)
                ->count();

            // Get restaurant-specific data
            $restaurantData = compact('reservationCounts', 'reservationRequests', 'todaysApprovedReservationsCount');
            
            // Return both restaurant and user data
            return view('dashboard', compact('restaurantData'));
        } elseif (Auth::check() && auth()->user()->role_id == '1') {
            // For regular users with role ID 1
            $totalUsers = User::count();
            $approvedRestaurants = Restaurant::where('status', 'Approved')->count();
            $pendingRequests = Restaurant::where('status', 'Pending')->count();

            // Get user-specific data
            $userData = compact('totalUsers', 'approvedRestaurants', 'pendingRequests');

            // Return both restaurant and user data
            return view('dashboard', compact('userData'));
        } else {
            // If neither restaurant nor regular user is authenticated
            abort(403, 'Unauthorized access');
        }
    }

    public function userDashboard()
    {
        $roles = Role::all();
        $users = User::all();
    
        $restaurants = Restaurant::with('ratings')
            ->where('status', 'Approved') // Filter restaurants by status
            ->get()
            ->map(function ($restaurant) {
                $restaurant->averageRating = $restaurant->ratings->avg('mark') ?? 0;
                return $restaurant;
            })
            ->sortByDesc('averageRating');
    
        $attachments = Attachment::all();
    
        return view('user.user_dashboard', [
            'roles' => $roles,
            'users' => $users,
            'restaurants' => $restaurants,
            'attachments' => $attachments,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $restaurants = Restaurant::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with('ratings')
            ->where('status', 'Approved')
            ->get()
            ->map(function ($restaurant) {
                $restaurant->averageRating = $restaurant->ratings->avg('mark') ?? 0;
                return $restaurant;
            })
            ->sortByDesc('averageRating');

        return view('user.search_restaurant_results', [
            'restaurants' => $restaurants,
            'query' => $query,
        ]);
    }

    public function viewRestaurant($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $attachments = Attachment::where('restaurant_id', $id)->get();
        
        // Fetch holiday data for the restaurant
        $holidays = Holiday::where('restaurant_id', $id)->get();
        
        // Format the holiday data for FullCalendar
        $events = [];
        foreach ($holidays as $holiday) {
            $events[] = [
                'title' => $holiday->holiday_name,
                'start' => $holiday->start_date,
                'end' => $holiday->end_date,
                'allDay' => true,
                'color' => '#6c757d' // Red color for holidays
            ];
        }
        
        return view('user.view_restaurant', compact('restaurant', 'attachments', 'events'));
    }

    public function getRatings(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = 4; // Number of comments to fetch at a time

        // Fetch ratings with user details and pagination
        $ratings = Rating::with(['user' => function ($query) {
            $query->with('profile_pic');
        }])->skip($start)->take($limit)->get();

        return response()->json($ratings);
    }

    public function index()
    {
        $role = Role::all();
        $user = User::all();
        return view('admin.admin_user', compact('role','user')); 
    }

    public function user_search(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('phone_num', 'LIKE', "%{$query}%")
                    ->with('role') // Ensure you load the related role
                    ->get();

        return response()->json($users);
    }


    public function adminCreate(Request $req, User $user)
    {
        $data = new User;
        $data->name = $req->input('name');
        $data->role_id = $req->input('role_id');
        $data->email = $req->input('email');
        $data->phone_num = $req->input('phone_num');
        $messageType = $req->input('message_type');
        $data->message_type = json_encode($messageType);

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
        // Check if role_id 2 exists
        $role = Role::find(2);
    
        if (!$role) {
            // Role with id 2 does not exist
            $response = [
                'status' => 'failed',
                'message' => 'Role with id 2 does not exist'
            ];
    
            return response()->json($response, 404);
        }
    
        // Role with id 2 exists, proceed with user creation
        $data = new User;
        $data->name = $req->input('name');
        $data->role_id = 2;
        $data->email = $req->input('email');
        $data->password = bcrypt($req->input('password')); // Hash the password directly
        $data->phone_num = $req->input('phone_num');
    
        if ($req->hasFile('profile_pic')) {
            $imagePath = $req->file('profile_pic')->store('user_img', 'public');
            $data->profile_pic = $imagePath;
        }
    
        // Handle the preferred message type
        $messageType = $req->input('message_type');
        $data->message_type = json_encode($messageType);
    
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
        return view('user.user_register', compact('role','user')); 
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
            $user = Auth::user();
            $role = $user->role_id;

            if ($role == 1) {
                return redirect()->route('dashboard');
            } elseif ($role == 2) {
                return redirect()->route('home');
            }
        }
        
        return redirect('/login')->with('status', 'failed');
    }

    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/login');
    }

    public function forgotPassword()
    {
        return view('user.forgot_password');
    }

    public function ForgetPasswordStore(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email',
        ]);
    
        // Check if the user exists
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect('/login')->with('status', 'fail');
        }
    
        // Generate a new password
        $newPassword = Str::random(8); // Generate an 8-character random password
    
        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();
    
        // Send the new password to the user via email
        try {
            Mail::to($request->email)->send(new ForgotPassword($newPassword));
        } catch (\Exception $e) {
            // Email sending failed
            return back()->withErrors(['email' => 'Failed to send the new password.']);
        }
    
        // Email sent successfully
        return redirect('/login')->with('status', 'success');
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
        $loggedInUserRole = auth()->user()->role_id;
    
        // Define validation rules
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_num' => 'required|string|max:20',
            'role_id' => 'sometimes|required|integer|exists:roles,id',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
    
        // Update user details
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->phone_num = $req->input('phone_num');
    
        if ($loggedInUserRole == 1) {
            $user->role_id = $req->input('role_id');
        } elseif ($loggedInUserRole == 2) {
            $user->role_id = 2;
        }
    
        $role = Role::find($user->role_id);
    
        if ($role) {
            $user->save();
    
            $response = [
                'status' => 'success',
                'data' => $user
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
        return view('user.user_profile', compact('role','user')); 
    }

    public function makeReservation(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'party_size' => 'required|integer|min:1',
            'remark' => 'nullable|string|max:255',
            'table_num' => 'nullable|integer',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
    
        // Get the restaurant ID from the request
        $restaurantId = $request->input('restaurant_id');
        $restaurant = Restaurant::find($restaurantId);
    
        // Check if the table number is provided
        if ($request->input('table_num') !== null) {
            $tableNum = $request->input('table_num');
            
            // Check if the table number is within the valid range for the restaurant
            if ($tableNum < 1 || $tableNum > $restaurant->table_num) {
                return response()->json(['status' => 'error', 'errors' => ['table_num' => 'Invalid table number']], 422);
            }
    
            // Parse the reservation date and time
            $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('date') . ' ' . $request->input('time'));
    
            // Calculate the start and end time for the unavailable period
            $unavailableStart = $reservationDateTime->copy()->subHour();
            $unavailableEnd = $reservationDateTime->copy()->addHours(2);
    
            // Check if the table is already reserved within the unavailable period
            $existingReservation = Reservation::where('restaurant_id', $restaurantId)
                ->where('date', $request->input('date'))
                ->where('table_num', $tableNum)
                ->where('status', '!=', 'Rejected') // Ensure that rejected reservations are not considered
                ->whereNotIn('completeness', ['Done', 'Confirmed Absent', 'Cancel'])
                ->where(function($query) use ($unavailableStart, $unavailableEnd) {
                    $query->whereBetween('time', [$unavailableStart->format('H:i'), $unavailableEnd->format('H:i')])
                          ->orWhere(function($query) use ($unavailableStart, $unavailableEnd) {
                              $query->where('time', '<', $unavailableStart->format('H:i'))
                                    ->whereRaw('? < ADDTIME(time, "03:00:00")', [$unavailableEnd->format('H:i')]);
                          });
                })
                ->first();
    
            if ($existingReservation) {
                return response()->json(['status' => 'error', 'errors' => ['table_num' => 'This table is already reserved within the specified time range']], 422);
            }
        }
    
        // Get the authenticated user's ID
        $userId = Auth::id();
    
        // Create a new reservation instance
        $reservation = new Reservation;
        $reservation->user_id = $userId;
        $reservation->restaurant_id = $restaurantId;
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->party_size = $request->input('party_size');
        $reservation->remark = $request->input('remark');
        $reservation->table_num = $request->input('table_num');
        $reservation->status = "Pending";
        $reservation->completeness = "Pending";
    
        // Save the reservation
        $reservation->save();
    
        // Optionally send emails to user and restaurant
        // Mail::to($reservation->user->email)->send(new ReservationMade($reservation, 'user'));
        // Mail::to($reservation->restaurant->email)->send(new ReservationMade($reservation, 'restaurant'));
    
        return response()->json(['status' => 'success', 'message' => 'Reservation successfully made'], 200);
    }
    
    public function getAvailableTables(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $date = $request->query('date');
        $time = $request->query('time');
    
        $restaurant = Restaurant::find($restaurantId);
    
        // Calculate the start and end time for the unavailable period
        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        $unavailableStart = $reservationDateTime->copy()->subHour(); // Adjusted to exactly 1 hour before
        $unavailableEnd = $reservationDateTime->copy()->addHours(2);   // Adjusted to 2 hours after
    
        // Get all tables for the restaurant
        $allTables = range(1, $restaurant->table_num);
    
        // Get unavailable tables, filtering out reservations with completeness 'Done' or 'Confirmed Absent'
        $unavailableTables = Reservation::where('restaurant_id', $restaurantId)
            ->where('date', $date)
            ->where('status', '!=', 'Rejected')
            ->whereNotIn('completeness', ['Done', 'Confirmed Absent'])
            ->where(function($query) use ($unavailableStart, $unavailableEnd) {
                $query->whereBetween('time', [$unavailableStart->format('H:i'), $unavailableEnd->format('H:i')]);
            })
            ->pluck('table_num')
            ->toArray();
    
        // Get available tables
        $availableTables = array_diff($allTables, $unavailableTables);
    
        return response()->json([
            'available_tables' => array_values($availableTables), // Ensure this is an array
            'all_tables' => array_values($allTables),
            'unavailable_tables' => array_values($unavailableTables)
        ]);
    }
    
    
    public function reservationRecord()
    {
        if (Auth::guard('restaurant')->check()) {
            $restaurant = Auth::guard('restaurant')->user();
            $reservations = $restaurant->reservations()->where('status', 'Pending')->get();
        } elseif (Auth::check()) {
            $user = Auth::user();
            if ($user->role_id === 2) {
                $reservations = $user->reservations()
                                     ->whereIn('status', ['Pending', 'Approved', 'Rejected'])
                                     ->orderBy('created_at', 'desc')
                                     ->get();            
            } elseif ($user->role_id === 1) {
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
        // Retrieve the reservation by its ID
        $reservation = Reservation::find($id);
    
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    
        // Update the status and completeness
        $reservation->status = 'Cancel';
        $reservation->completeness = 'Cancel';
    
        // Save the changes to the database
        $reservation->save();
    
        return response()->json(['message' => 'Reservation cancelled successfully'], 200);
    }

    public function viewCanceledReservations(Request $request)
    {
        // Retrieve the authenticated user's ID
        $userId = auth()->user()->id;
    
        // Query canceled reservations for the authenticated user
        $canceledReservationsQuery = Reservation::where('user_id', $userId)
                                                 ->where('status', 'Cancel');
    
        // Apply search filter if query is provided
        $query = $request->input('query');
        if ($query) {
            $canceledReservationsQuery->where(function ($q) use ($query) {
                $q->whereHas('restaurant', function ($restaurantQuery) use ($query) {
                    $restaurantQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('time', 'LIKE', "%{$query}%")
                ->orWhere('party_size', 'LIKE', "%{$query}%")
                ->orWhere('remark', 'LIKE', "%{$query}%");
            });
        }
    
        // Apply date filter if date is provided
        $date = $request->input('date');
        if ($date) {
            $canceledReservationsQuery->whereDate('date', $date);
        }
    
        // Apply sorting
        $sort = $request->input('sort_order', 'asc');
        $canceledReservationsQuery->orderBy('date', $sort);
    
        // Paginate the results
        $perPage = 5; // Number of records per page
        $canceledReservations = $canceledReservationsQuery->paginate($perPage);
    
        // Return the view with the paginated canceled reservations
        return view('user.cancel_reservation', compact('canceledReservations'));
    }
       
    public function pendingReservation(Request $request)
    {
        // Retrieve the authenticated user's ID
        $userId = auth()->user()->id;
    
        // Query pending reservations for the authenticated user
        $pendingReservationsQuery = Reservation::where('user_id', $userId)
            ->where('status', 'pending');
    
        // Apply search filter if query is provided
        $query = $request->input('query');
        if ($query) {
            $pendingReservationsQuery->where(function ($q) use ($query) {
                $q->whereHas('restaurant', function ($restaurantQuery) use ($query) {
                    $restaurantQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('time', 'LIKE', "%{$query}%")
                ->orWhere('party_size', 'LIKE', "%{$query}%")
                ->orWhere('remark', 'LIKE', "%{$query}%");
            });
        }
    
        // Apply date filter if date is provided
        $date = $request->input('date');
        if ($date) {
            $pendingReservationsQuery->whereDate('date', $date);
        }
    
        // Apply sorting
        $sort = $request->input('sort', 'asc');
        $pendingReservationsQuery->orderBy('date', $sort);
    
        // Paginate the results
        $perPage = 5; // Number of records per page
        $pendingReservations = $pendingReservationsQuery->paginate($perPage);
    
        // Return the paginated pending reservations to the user_reservation_req view
        return view('user.user_reservation_req', compact('pendingReservations'));
    }
       
    public function showUserResetForm()
    {
        return view('user.reset_password');
    }
    
    public function resetUserPassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
        ]);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Check if the email matches the logged-in user
        if (!$user || $user->email !== $request->email) {
            return redirect('/user/password/reset')->withErrors(['email' => 'Email does not match the logged-in user.']);
        }
    
        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Redirect with success message
        return redirect('/login')->with('status', 'Change');
    }
    

}
