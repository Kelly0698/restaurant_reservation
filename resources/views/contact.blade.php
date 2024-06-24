@extends('layouts')
@section('title', 'Contact')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <h2 style="background-color: #bc601528; padding: 10px; padding-left: 20px;">Contact</h2><br>
        
        <div>
            <h3>Contact Information for Admin User:</h3>
            <ul>
                <li><strong>Name:</strong> {{ $adminUser->name }}</li>
                <li><strong>Email:</strong> {{ $adminUser->email }}</li>
                <li><strong>Phone:</strong> {{ $adminUser->phone }}</li>
                <!-- Add other fields as needed -->
            </ul>
        </div>
        
    </div>
</div>

@endsection
