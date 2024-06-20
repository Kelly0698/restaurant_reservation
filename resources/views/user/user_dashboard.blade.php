@extends('layouts')
@section('title','home')
@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .recommended-restaurants {
            overflow-x: scroll;
            white-space: nowrap;
            padding-bottom: 20px;
            scrollbar-width: none; /* For Firefox */
            -ms-overflow-style: none;  /* For Internet Explorer and Edge */
        }
        .recommended-restaurants::-webkit-scrollbar {
            display: none; /* For Chrome, Safari, and Opera */
        }
        .restaurants-container {
            display: inline-flex;
        }
        .restaurant-card {
            display: inline-block;
            vertical-align: top;
            width: calc(100% / 3 - 40px); /* Adjust the width to fit 3 cards in the view */
            margin: 0 20px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 15px;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
            background-color: #bc601528;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .restaurant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .restaurant-img {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .restaurant-img img {
            border-radius: 15px;
            overflow: hidden;
            border: 3px solid #adb5bd;
            width: 200px;
            height: 200px;
        }
        .restaurant-name {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #052d64;
        }
        .rating {
            font-size: 24px;
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .rating .fa-star,
        .rating .fa-star-half-o,
        .rating .fa-star-o {
            color: #ffc107;
        }
        .heart-icon {
            position: absolute;
            top: 6px;
            right: 20px;
            cursor: pointer;
            font-size: 24px;
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
        .search-bar-card {
            background: url('{{ asset('assets/dist/img/1747.jpg') }}') no-repeat center center;
            background-size: cover;
            padding: 60px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            height: 200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .search-bar input[type="text"] {
            color: black;
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 18px;
            box-sizing: border-box;
        }
        .search-bar button {
            color: white;
            background-color: #052d64;
        }
                .title-container h4 {
            margin: 0;
            font-size: 24px;
            color: #052d64;
        }
    </style>
</head>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Search Bar -->
        <div class="search-bar-card">
            <div class="search-bar">
                <form action="{{ route('search_restaurants') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control rounded-pill" placeholder="Search for restaurants" name="query" style="width: 70%;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary yellow rounded-pill" type="submit" style="width: 100%;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br><br>
        <!-- Recommended Restaurants -->
            <h4 style="text-align: center; font-weight: bold; border-radius: 15px;">Recommended Restaurants</h4><hr>
            <div class="recommended-restaurants">
            <div class="restaurants-container" id="restaurants-container">
            @foreach($restaurants->take(15) as $restaurant)
            <div class="restaurant-card">
                <div>
                    <a href="{{ route('view_restaurant', ['id' => $restaurant->id]) }}">
                        <div class="restaurant-img">
                            @if($restaurant->logo_pic)
                                <img src="{{ asset('storage/' . $restaurant->logo_pic) }}" alt="{{ $restaurant->name }}">
                            @else
                                <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Default">
                            @endif
                            <!-- Heart Icon -->
                            @php
                                $liked = Auth::user()->likes()->where('restaurant_id', $restaurant->id)->exists();
                            @endphp
                            <span class="heart-icon" onclick="saveRestaurant(event, {{ $restaurant->id }})">
                                <i class="fa {{ $liked ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
                            </span>
                        </div>
                        <p class="restaurant-name">{{ $restaurant->name }}</p>
                    </a>
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
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function saveRestaurant(event, restaurantId) {
        event.preventDefault();
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
            } else {
                alert('Failed to save restaurant.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection
