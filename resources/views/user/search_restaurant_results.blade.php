@extends('layouts')
@section('title','Search Results')
@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

        /* New styles for card content */
        .search-container {
            padding: 20px;
            background-color: #bc601528;
            max-width: 96%; 
            margin: 0 auto;
        }

        .restaurant-container {
            max-height: 400px; /* Adjust as needed */
            overflow-y: auto;
        }

        .restaurant-card {
            border: 1px solid #ddd; /* Add border */
            border-radius: 5px;
            margin-bottom: 0px; /* Add margin for spacing */
            background-color: white;
        }

        .restaurant-details {
            padding: 10px;
        }

        .restaurant-img {
            width: 100px; /* Adjust image width */
            height: 100px; /* Adjust image height */
            overflow: hidden; /* Hide overflowing content */
            float: left;
            margin-right: 10px;
        }

        .restaurant-img img {
            width: 100%; /* Make image fill its container */
            height: 100%; /* Make image fill its container */
            object-fit: cover; /* Maintain aspect ratio */
        }

        .restaurant-info {
            overflow: hidden; /* Clear floated image */
        }

        .restaurant-name {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 5px;
            display: inline-block; /* Display inline with the star rating */
        }

        .restaurant-address {
            font-size: 17px;
            color: #555;
            margin-bottom: 5px;
        }

        .restaurant-description {
            font-size: 17px;
            margin-bottom: 5px;
        }

        .rating {
            font-size: 16px;
            color: #555;
            margin-left: 5px;
            display: inline-block; /* Display inline with the restaurant name */
        }

        .rating .fa {
            color: gold; /* Set star color */
        }
    </style>
</head>
<div class="content-wrapper">
    <div class="container-fluid">
        <br><br>
        <div class="search-container">
            <!-- Search bar -->
            <form action="{{ route('search_restaurants') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" class="form-control rounded-pill" placeholder="Search for restaurants" name="query" style="width: 70%;">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary yellow rounded-pill" type="submit" style="width: 100%;">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="restaurant-container">
            <div style="max-width: 95%; margin: 0 auto;">
                @if($restaurants->isEmpty())
                    <p>No restaurants found.</p>
                @else
                    <div class="row">
                        @foreach($restaurants as $restaurant)
                            <div class="col-md-12 restaurant-card">
                                <div class="restaurant-details">
                                    <div class="restaurant-info">
                                        <div class="restaurant-img">
                                            @if($restaurant->logo_pic)
                                                <img src="{{ asset('storage/' . $restaurant->logo_pic) }}" alt="{{ $restaurant->name }}">
                                            @else
                                                <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Default">
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('view_restaurant', ['id' => $restaurant->id]) }}">
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
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
