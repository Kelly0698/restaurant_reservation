@extends('layouts')

@section('title', 'Saved Restaurants')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .restaurant-container {
            max-width: 95%;
            margin: 0 auto;
        }
        .restaurant-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            position: relative;
            background-color: #ffe7e2de;
        }
        .restaurant-img {
            flex: 0 0 auto;
            margin-right: 20px;
        }
        .restaurant-img img {
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #adb5bd;
            width: 170px;
            height: 170px;
        }
        .restaurant-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .restaurant-name {
            font-size: 24px;
            font-weight: bold;
            color: #052d64;
        }
        .restaurant-address,
        .restaurant-description {
            margin-top: 10px;
        }
        .rating {
            font-size: 24px;
        }
        .rating .fa-star,
        .rating .fa-star-half-o,
        .rating .fa-star-o {
            position: relative;
            color: #ffc107;
        }
        .heart-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 24px;
            z-index: 10;
        }
        .heart-icon .fa-heart-o {
            color: #e74c3c;
        }
        .heart-icon .fa-heart {
            color: #e74c3c;
        }
        .heart-icon:hover .fa-heart-o,
        .heart-icon:hover .fa-heart {
            color: #c0392b;
        }
    </style>
</head>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="col-12">
            <h2 style="background-color: #bc601528; padding:10px; padding-left: 20px;">Saved Restaurants</h2><br>

            @if($likedRestaurants->isEmpty())
                <p>No saved restaurants.</p>
            @else
            <div class="restaurant-container">
                @foreach($likedRestaurants as $restaurant)
                    <div class="col-md-12 restaurant-card">
                        <a href="{{ route('view_restaurant', ['id' => $restaurant->id]) }}">
                            <div class="restaurant-img">
                                @if($restaurant->logo_pic)
                                    <img src="{{ asset('storage/' . $restaurant->logo_pic) }}" alt="{{ $restaurant->name }}">
                                @else
                                    <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Default">
                                @endif
                            </div>
                            <div class="restaurant-info">
                                <p class="restaurant-name">{{ $restaurant->name }}</p>
                                <div class="rating">
                                    @php
                                        $averageRating = $restaurant->averageRating;
                                        $filledStars = floor($averageRating);
                                        $halfStar = $averageRating - $filledStars >= 0.5;
                                    @endphp
                                    @for ($i = 0; $i < $filledStars; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    @if ($halfStar)
                                        <span class="fa fa-star-half-o"></span>
                                    @endif
                                    @for ($i = $filledStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                        <span class="fa fa-star-o"></span>
                                    @endfor
                                </div>
                                <p class="restaurant-address" style="color: black">{{ $restaurant->address }}</p>
                                <p class="restaurant-description" style="color: black">{{ $restaurant->description }}</p>
                            </a>
                        </div>
                        @php
                            $liked = Auth::user()->likes()->where('restaurant_id', $restaurant->id)->exists();
                        @endphp
                        <span class="heart-icon" onclick="saveRestaurant(event, {{ $restaurant->id }})">
                            <i class="fa {{ $liked ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
                        </span>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function saveRestaurant(event, restaurantId) {
        event.preventDefault();
        
        // Confirm deletion
        if (confirm("Are you sure you want to delete this saved restaurant?")) {
            fetch(`/save_restaurant/${restaurantId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = event.target.classList.contains('fa') ? event.target : event.target.querySelector('.fa');
                    icon.classList.toggle('fa-heart');
                    icon.classList.toggle('fa-heart-o');

                    // Refresh the page
                    location.reload();
                } else {
                    alert('Failed to save restaurant.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>

@endsection
