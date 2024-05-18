<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // Create a new Restaurant instance
        $restaurant = new Restaurant();
        $restaurant->name = $req->name;
        $restaurant->email = $req->email;
        $restaurant->phone_num = $req->phone_num;
        $restaurant->password = bcrypt($req->password);

        if ($req->hasFile('logo_pic')) {
            $imagePath = $req->file('logo_pic')->store('res_first_img', 'public');
            $restaurant->logo_pic = $imagePath;
        } else {
            // Set default profile picture path if no file is uploaded
            $restaurant->logo_pic = 'public\assets\dist\img\defaultPic';
        }

        $restaurant->address = $req->address;
        $file = $req->file('license_pdf');
        $filename = time() . '_' . $file->getClientOriginalName(); // Generate a unique filename
        $file->storeAs('public/license_pdf', $filename); // Store the file with the generated filename in the public/storage/license_pdf directory
        $restaurant->license_pdf = $filename; // Store the filename in the database
        
        $restaurant->status = "Pending";
        $restaurant->operation_time = "9:00 AM - 9:00 PM";
        $restaurant->availability = "Yes";
        $restaurant->description = "Restaurant Description";

        // Save the restaurant to the database
        $restaurant->save();
    
        // Send email with generated password
        // Mail::to($restaurant->email)->send(new RestaurantCreated($restaurant, $generatedPassword));
    
        // Response
        $response = [
            'status' => 'success',
            'data' => $restaurant
        ];
    
        return response()->json($response, 200);
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
    
    public function updateCompleteness($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->completeness = 'Done';
        $reservation->save();

        return redirect()->back()->with('success', 'Reservation marked as complete.');
    }

    public function edit(Restaurant $restaurant, Request $req)
    {
        $restaurant->name = $req->name;
        $restaurant->email = $req->email;  
        $restaurant->phone_num = $req->phone_num; 
        $restaurant->address = $req->address;
        if ($req->hasFile('license_pdf')) {
            $file = $req->file('license_pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/license_pdf', $filename);
            $restaurant->license_pdf = $filename;
        }
        $restaurant->status = $req->status;
        $restaurant->operation_time = $req->operation_time;
        $restaurant->availability = $req->availability;
        $restaurant->description = $req->description;
        // Save changes to the restaurant
        $restaurant->save();

        return response()->json(['status' => 'success'], 200);
    }


    public function restaurantRegister()
    {
        $restaurant = Restaurant::all();
        return view('restaurant_register', compact('restaurant'));
    }
   
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function restaurant_profile()
    {
        $restaurant = Restaurant::with('attachments')->find(Auth::guard('restaurant')->user()->id);
        return view('restaurant_profile', compact('restaurant'));
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

    public function approveReservation($id)
    {
        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);
        
        // Update the status to "Approved"
        $reservation->status = 'Approved';
        $reservation->save();
        
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reservation request approved successfully.');
    }

    public function approveResPage()
    {
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();

        // Fetch reservations with completeness 'Pending' and 'No_Show'
        $approvedReservations = Reservation::where('status', 'Approved')
                                           ->whereIn('completeness', ['Pending', 'No_Show'])
                                           ->get();

        return view('approved_reservation', compact('approvedReservations'));
    }

    public function showDoneReservations()
    {
        // Fetch reservations with completeness 'Done'
        $doneReservations = Reservation::where('status', 'Approved')
                                       ->where('completeness', 'Done')
                                       ->get();

        return view('complete_reservation', compact('doneReservations'));
    }

    public function rejectReservation($id)
    {
        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);
        
        $reservation->status = 'Rejected';
        $reservation->save();
        
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reservation request rejected');
    }

    public function rejectResPage()
    {
        // Get the authenticated restaurant
        $restaurant = Auth::guard('restaurant')->user();

        // Retrieve approved reservations for the current restaurant
        $rejectedReservations = Reservation::where([
            ['status', 'Rejected'],
            ['restaurant_id', $restaurant->id]
        ])->get();
        
        // Pass the approved reservation records to the view
        return view('rejected_reservation', compact('rejectedReservations'));
    }
}
