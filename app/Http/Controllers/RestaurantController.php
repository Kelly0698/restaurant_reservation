<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Attachment;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Mail\RestaurantCreated;
use App\Mail\RestaurantRegistrationSuccess;
use App\Mail\RestaurantRegistrationRejected;
use App\Mail\ReservationApprovalNotification;
use App\Mail\ForgotPassword;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = Restaurant::all();
        return view('admin.admin_restaurant', compact('restaurant'));
    }

    public function restaurant_search(Request $request)
    {
        $query = $request->get('query');
        $restaurants = Restaurant::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%")
                        ->orWhere('phone_num', 'LIKE', "%{$query}%")
                        ->orWhere('address', 'LIKE', "%{$query}%")
                        ->orWhere('status', 'LIKE', "%{$query}%")
                        ->where('status', 'Approved')
                        ->get();

        return response()->json($restaurants);
    }


    public function index_req()
    {
        $restaurant = Restaurant::all();
        return view('admin.admin_restaurant_req', compact('restaurant'));
    }

    public function login(Request $request)
    {
        Auth::logout();

        // Retrieve credentials from the request
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        // Attempt authentication using the 'restaurant' guard
        if (Auth::guard('restaurant')->attempt($credentials)) {
            // Retrieve authenticated restaurant user
            $restaurant = Auth::guard('restaurant')->user();
            
            // Check if the restaurant's status is pending
            if ($restaurant->status === 'Pending') {
                // If status is pending, redirect back with a status message
                return redirect('/your-restaurant')->with('status', 'pending');
            }

            // If authentication succeeds, retrieve restaurant data
            $name = $restaurant->name;
            $id = $restaurant->id;
            $email = $restaurant->email;
            
            // Store restaurant data in the session
            session(['name' => $name]);
            session(['email' => $email]);
            session(['id' => $id]);
            
            // Redirect to the dashboard or any other authorized page
            return redirect('dashboard');
        }
        
        // If authentication fails, redirect back with a status message
        return redirect('/your-restaurant')->with('status', 'failed');
    }


    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/your-restaurant');
    }

    public function adminCreate(Request $req)
    {
        // Create a new Restaurant instance
        $restaurant = new Restaurant();
        $restaurant->name = $req->name;
        $restaurant->email = $req->email;
        $restaurant->phone_num = $req->phone_num;
        $generatedPassword = Str::random(8);
        $restaurant->password = bcrypt($generatedPassword);

        $restaurant->address = $req->address;
        $file = $req->file('license_pdf');
        $filename = time() . '_' . $file->getClientOriginalName(); // Generate a unique filename
        $file->storeAs('public/license_pdf', $filename); // Store the file with the generated filename in the public/storage/license_pdf directory
        $restaurant->license_pdf = $filename; // Store the filename in the database
        
        $restaurant->status = $req->status;

        // Save the restaurant to the database
        $restaurant->save();

        // Send email only if status is Approved
        if ($restaurant->status === 'Approved') {
            Mail::to($restaurant->email)->send(new RestaurantCreated($restaurant, $generatedPassword));
        }

        // Response
        $response = [
            'status' => 'success',
            'data' => $restaurant
        ];

        return response()->json($response, 200);
    }

    public function create(Request $req)
    {
        // Check if the email already exists
        $existingRestaurant = Restaurant::where('email', $req->email)->first();
        if ($existingRestaurant) {
            // If the email already exists, return an error response
            $response = [
                'status' => 'error',
                'message' => 'Email is already registered.'
            ];
            return response()->json($response, 400); // 400 status code for bad request
        }
    
        // If the email is unique, proceed with creating the restaurant
        $restaurant = new Restaurant();
        $restaurant->name = $req->name;
        $restaurant->email = $req->email;
        $restaurant->phone_num = $req->phone_num;
        $restaurant->password = bcrypt($req->password);
    
        // Handle logo picture upload
        if ($req->hasFile('logo_pic')) {
            $imagePath = $req->file('logo_pic')->store('res_first_img', 'public');
            $restaurant->logo_pic = $imagePath;
        } else {
            // Set default profile picture path if no file is uploaded
            $restaurant->logo_pic = 'public\assets\dist\img\defaultPic';
        }
    
        // Other restaurant details
        $restaurant->address = $req->address;
        $file = $req->file('license_pdf');
        $filename = time() . '_' . $file->getClientOriginalName(); // Generate a unique filename
        $file->storeAs('public/license_pdf', $filename); // Store the file with the generated filename in the public/storage/license_pdf directory
        $restaurant->license_pdf = $filename; // Store the filename in the database
        $restaurant->status = "Pending";
        $restaurant->operation_time = "9:00 AM - 9:00 PM";
        $restaurant->availability = "Yes";
        $restaurant->description = null;
    
        // Save the restaurant to the database
        $restaurant->save();
    
        // Response for successful creation
        $response = [
            'status' => 'success',
            'data' => $restaurant
        ];
    
        return response()->json($response, 200); // 200 status code for OK
    }
    
    public function show($id)
    {
        $restaurant = Restaurant::find($id);
        return view('admin.admin_show_restaurant', compact('restaurant'));
    }

    public function updateStatus(Request $request, $id) {
        $restaurant = Restaurant::findOrFail($id);
        
        // Store the old status for comparison later
        $oldStatus = $restaurant->status;
    
        // Update the status
        $restaurant->status = $request->status;
        $restaurant->save();
    
        // Check if the status has changed to 'Approved' or 'Rejected' from 'Pending'
        if ($oldStatus === 'Pending' && ($request->status === 'Approved' || $request->status === 'Rejected')) {
            // Determine which email to send based on the new status
            $mailClass = ($request->status === 'Approved') ? RestaurantRegistrationSuccess::class : RestaurantRegistrationRejected::class;
    
            // Send email to inform restaurant about the status change
            Mail::to($restaurant->email)->send(new $mailClass($restaurant));
        }
        
        return response()->json(['message' => 'Status updated successfully']);
    }
    
    public function updateCompleteness(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $completeness = $request->input('completeness');
        
        if (in_array($completeness, ['Done', 'Confirmed Absent', 'Eating'])) {
            $reservation->completeness = $completeness;
            $reservation->save();
            
            return redirect()->back()->with('success', 'Reservation status updated successfully.');
        }
        
        return redirect()->back()->with('error', 'Invalid status update.');
    }
    

    public function edit(Restaurant $restaurant, Request $req)
    {
        // Define validation rules
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_num' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'license_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'operation_time' => 'nullable|string|max:255',
            'availability' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
    
        // Update restaurant details
        $restaurant->name = $req->name;
        $restaurant->email = $req->email;
        $restaurant->phone_num = $req->phone_num;
        $restaurant->address = $req->address;
    
        // Handle file upload if present
        if ($req->hasFile('license_pdf')) {
            $file = $req->file('license_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/license_pdf', $filename);
            $restaurant->license_pdf = $filename;
        }
    
        // Update the operation_time only if it's provided in the request
        if ($req->has('operation_time')) {
            $restaurant->operation_time = $req->operation_time;
        }
    
        // Update the availability only if it's provided in the request
        if ($req->has('availability')) {
            $restaurant->availability = $req->availability;
        }
    
        // Update the description only if it's provided in the request
        if ($req->has('description')) {
            $restaurant->description = $req->description;
        }
    
        $restaurant->status = "Approved";
    
        // Save changes to the restaurant
        $restaurant->save();
    
        return response()->json(['status' => 'success'], 200);
    }
    

    public function restaurantRegister()
    {
        $restaurant = Restaurant::all();
        return view('restaurant.restaurant_register', compact('restaurant'));
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function restaurant_profile()
    {
        $restaurant = Restaurant::with('attachments')->find(Auth::guard('restaurant')->user()->id);
        return view('restaurant.restaurant_profile', compact('restaurant'));
    }

    public function updateLogo(Request $req, Restaurant $restaurant)
    {
        if (!$req->hasFile('logo_pic')) {
            return response()->json(['error' => 'No image was uploaded.'], 400);
        }

        $oldImagePath = $restaurant->logo_pic;
        $newImagePath = $req->file('logo_pic')->store('res_first_img', 'public');

        $restaurant->logo_pic = $newImagePath;
        $restaurant->save();

        // delete old image from storage
        if ($oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::delete($oldImagePath);
        }

        return response()->json(['success' => 'Image saved successfully'], 200);
    }

    public function uploadPicture(Request $request)
    {
        // Check if the request contains any preview images
        if ($request->preview_images) {
            foreach ($request->preview_images as $base64Image) {
                // Extract the base64 data from the string
                $base64ImageParts = explode(',', $base64Image);
                $base64ImageEncoded = end($base64ImageParts); // Get the base64 data
                
                // Decode the base64 data and generate a unique filename
                $decodedImage = base64_decode($base64ImageEncoded);
                $imageName = uniqid() . '.jpg'; // Generate a unique filename (you may use different logic to generate filenames)
                $imagePath = public_path('storage/res_pic/' . $imageName); // Set the image path
                
                // Create the directory if it doesn't exist
                if (!File::exists(public_path('storage/res_pic'))) {
                    File::makeDirectory(public_path('storage/res_pic'), 0777, true, true);
                }
                
                // Save the image to the storage directory
                File::put($imagePath, $decodedImage);
                
                // Create a new attachment record for the uploaded image
                Attachment::create([
                    'restaurant_id' => $request->input('restaurant_id'),
                    'picture' => $imageName,
                ]);
            }

            return redirect()->back()->with('success', 'Pictures uploaded successfully!');
        }

        // Handle case where no preview images were uploaded
        return redirect()->back()->with('error', 'No preview images were uploaded!');
    }
 
    public function deleteAttachment($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Delete the attachment from the database
        $attachment->delete();

        // Delete the associated file from storage
        Storage::delete($attachment->picture);

        return response()->json(['status' => 'success'], 200);
    }

    public function approveResPage(Request $request)
    {
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();
    
        // Get query parameters
        $query = $request->input('query');
        $date = $request->input('date');
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending
    
        // Build the query
        $reservationsQuery = Reservation::where('status', 'Approved')
                                        ->whereIn('completeness', ['Pending', 'No_Show','Eating']);
    
        // Apply search filter if query is provided
        if ($query) {
            $reservationsQuery->where(function ($q) use ($query) {
                $q->whereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('restaurant', function ($restaurantQuery) use ($query) {
                    $restaurantQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('time', 'LIKE', "%{$query}%")
                ->orWhere('remark', 'LIKE', "%{$query}%")
                ->orWhere('party_size', 'LIKE', "%{$query}%");
            });
        }
    
        // Apply date filter if date is provided
        if ($date) {
            $reservationsQuery->whereDate('date', $date);
        }
    
        // Apply sorting
        $reservationsQuery->orderBy('date', $sort);
    
        // Get the filtered, sorted reservations
        $approvedReservations = $reservationsQuery->get();
    
        // Check if the request expects a JSON response (AJAX request)
        if ($request->expectsJson()) {
            return response()->json($approvedReservations);
        }
    
        // For regular HTTP requests, return the view with data
        return view('restaurant.approved_reservation', compact('approvedReservations'));
    }

    public function showDoneReservations(Request $request)
    {
        // Define the base query to fetch completed reservations
        $doneReservationsQuery = Reservation::where('status', 'Approved')
                                            ->where('completeness', 'Done');
    
        // Retrieve request parameters for sorting
        $sortField = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'asc');
    
        // Retrieve the search query parameter
        $searchQuery = $request->input('query');
    
        // Retrieve the date query parameter
        $dateQuery = $request->input('date');
    
        // Apply search filter if a query is provided
        if ($searchQuery) {
            $doneReservationsQuery->where(function ($query) use ($searchQuery) {
                $query->whereHas('user', function ($userQuery) use ($searchQuery) {
                    $userQuery->where('name', 'LIKE', '%' . $searchQuery . '%');
                })
                ->orWhereHas('restaurant', function ($restaurantQuery) use ($searchQuery) {
                    $restaurantQuery->where('name', 'LIKE', '%' . $searchQuery . '%');
                })
                ->orWhere('time', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('party_size', 'LIKE', '%' . $searchQuery . '%')
                ->orWhere('remark', 'LIKE', '%' . $searchQuery . '%');
            });
        }
    
        // Apply date filter if a date is provided
        if ($dateQuery) {
            $doneReservationsQuery->whereDate('date', $dateQuery);
        }
    
        // Apply sorting
        $doneReservationsQuery->orderBy($sortField, $sortOrder);
    
        // Get the filtered and sorted reservations
        $doneReservations = $doneReservationsQuery->get();
    
        // Pass the reservations to the view
        return view('restaurant.complete_reservation', compact('doneReservations'));
    }

    public function approveReservation($id)
    {
        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);
        
        // Update the status to "Approved"
        $reservation->status = 'Approved';
        $reservation->save();
        
        // Read the message types from the user
        $messageTypes = ["WhatsApp", "Email"]; // Placeholder for user input
        
        // Check if "Email" is selected
        if (in_array("Email", $messageTypes)) {
            Mail::to($reservation->user->email)->send(new ReservationApprovalNotification($reservation, 'Approved'));
        }
        
        return redirect()->back()->with('success', 'Reservation request approved successfully.');
    }
    
    public function rejectReservation($id)
    {
        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);
        
        // Update the status to "Rejected"
        $reservation->status = 'Rejected';
        $reservation->save();
        
        // Read the message types from the user
        $messageTypes = ["WhatsApp", "Email"]; // Placeholder for user input
        
        // Check if "Email" is selected
        if (in_array("Email", $messageTypes)) {
            Mail::to($reservation->user->email)->send(new ReservationApprovalNotification($reservation, 'Rejected'));
        }
        
        return redirect()->back()->with('success', 'Reservation request rejected');
    }

    public function rejectResPage(Request $request)
    {
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();
    
        // Retrieve rejected reservations for the current restaurant
        $query = $request->input('query');
        $date = $request->input('date');
        $sort = $request->input('sort', 'asc');
    
        $rejectedReservationsQuery = Reservation::where([
            ['status', 'Rejected'],
            ['restaurant_id', $restaurant->id]
        ]);
    
        // Apply search filter if query is provided
        if ($query) {
            $rejectedReservationsQuery->where(function ($q) use ($query) {
                $q->whereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('time', 'LIKE', "%{$query}%")
                ->orWhere('party_size', 'LIKE', "%{$query}%")
                ->orWhere('remark', 'LIKE', "%{$query}%");
            });
        }
    
        // Apply date filter if date is provided
        if ($date) {
            $rejectedReservationsQuery->whereDate('date', $date);
        }
    
        // Apply sorting
        $rejectedReservationsQuery->orderBy('date', $sort);
    
        $rejectedReservations = $rejectedReservationsQuery->get();
    
        // Pass the rejected reservation records to the view
        return view('restaurant.rejected_reservation', compact('rejectedReservations'));
    }
    
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = Restaurant::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function forgotPassword()
    {
        return view('restaurant.restaurant_forgot_password');
    }

    public function ForgetPasswordStore(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email',
        ]);
    
        // Find the restaurant user by email
        $restaurant = Restaurant::where('email', $request->email)->first();
    
        // Check if the restaurant exists
        if (!$restaurant) {
            return back()->withErrors(['email' => 'Restaurant not found.']);
        }
    
        // Generate a new password
        $newPassword = Str::random(8); // Generate an 8-character random password
    
        // Update the restaurant's password
        $restaurant->password = Hash::make($newPassword);
        $restaurant->save();
    
        // Send the new password to the restaurant via email
        try {
            Mail::to($restaurant->email)->send(new ForgotPassword($newPassword));
        } catch (\Exception $e) {
            // Email sending failed
            return back()->withErrors(['email' => 'Failed to send the new password.']);
        }
    
        // Redirect to the restaurant's login page with a success message
        return redirect('/your-restaurant')->with('status', 'success');
    }

    public function showResetForm()
    {
        return view('restaurant.restaurant_reset_password');
    }

    public function resetPassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Get the authenticated restaurant user
        $restaurant = Auth::guard('restaurant')->user();

        // Check if the email matches the logged-in restaurant user
        if (!$restaurant || $restaurant->email !== $request->email) {
            return redirect('/restaurant/password/reset')->withErrors(['email' => 'Email does not match the logged-in user.']);
        }

        // Update the restaurant's password
        $restaurant->password = Hash::make($request->password);
        $restaurant->save();

        // Redirect with success message
        return redirect('/your-restaurant')->with('status', 'Change');
    }
    
    public function restaurant_table()
    {
        return view('restaurant.table_arrangement');
    }

    public function upload_table_pic(Request $request, Restaurant $restaurant)
    {
        // Validate the request data
        $request->validate([
            'table_arrange_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_num' => 'required|integer|min:1',
        ]);
    
        // Get the restaurant by ID
        $restaurant = Restaurant::findOrFail($request->restaurant_id);
    
        // Handle the uploaded image
        $oldImagePath = $restaurant->table_arrange_pic ?? null;
        $newImagePath = $request->file('table_arrange_pic')->store('table_arrangement', 'public');
    
        // Update the restaurant's table arrangement picture and table number
        $restaurant->table_arrange_pic = $newImagePath;
        $restaurant->table_num = $request->table_num;
        $restaurant->save();
    
        // Delete the old image from storage
        if ($oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::delete($oldImagePath);
        }
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Table arrangement picture uploaded successfully');
    }

    public function AbsentResPage(Request $request)
    {
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();
    
        // Retrieve rejected reservations for the current restaurant
        $query = $request->input('query');
        $date = $request->input('date');
        $sort = $request->input('sort', 'asc');
    
        $ReservationsQuery = Reservation::where([
            ['status', 'Approved'],
            ['completeness','Confirmed Absent'],
            ['restaurant_id', $restaurant->id]
        ]);
    
        // Apply search filter if query is provided
        if ($query) {
            $ReservationsQuery->where(function ($q) use ($query) {
                $q->whereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('time', 'LIKE', "%{$query}%")
                ->orWhere('party_size', 'LIKE', "%{$query}%")
                ->orWhere('remark', 'LIKE', "%{$query}%");
            });
        }
    
        // Apply date filter if date is provided
        if ($date) {
            $ReservationsQuery->whereDate('date', $date);
        }
    
        // Apply sorting
        $ReservationsQuery->orderBy('date', $sort);
    
        $Reservations = $ReservationsQuery->get();
    
        // Pass the rejected reservation records to the view
        return view('restaurant.absent_reservation', compact('Reservations'));
    }
}
