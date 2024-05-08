@extends('layouts')
@section('title','home')
@section('content')
<head>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .recommended-restaurants {
            overflow-x: auto;
        }

        .restaurants-container {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            padding-bottom: 20px; /* Adjust as needed */
        }

        .restaurant-card {
            flex: 0 0 20%; /* Each card occupies approximately 30% of the container width */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px; /* Add padding to the cards */
            margin: 0 5px; /* Add margin to the cards */
            border: 1px solid #ccc; /* Add outline to the card */
            border-radius: 8px; /* Optional: Add border radius for a rounded look */
        }

        .restaurant-card a {
            display: block;
            text-align: center;
        }

        .restaurant-img {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .restaurant-img img {
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #adb5bd;
            width: 170px;
            height: 170px;
        }

        .restaurant-name {
            text-align: center;
        }

        .show-more-btn {
            margin: 10px auto;
            cursor: pointer;
            text-align: right;
            color: #052d64;
            font-size: 20px;
        }

        .rating {
        font-size: 24px; /* Adjust the size of the stars */
        }

        .rating .fa-star {
            color: #ffc107; /* Set color for filled stars */
            
        }

        .rating .far.fa-star {
            /* Use the Font Awesome outlined star icon for empty stars */
            color: transparent; /* Set transparent color for the outlined star */
            border: 1px solid yellow !important; /* Add border to create an outline */
            padding: 0 3px; /* Adjust padding to maintain the shape */
        }

        .rating .fas.fa-star-half-alt {
            /* Use the Font Awesome half-filled star icon */
            color: #ffc107; /* Set color for the half-filled star */
        }
    </style>

</head>

<div class="content-wrapper">
    <div class="container-fluid">
        <br><br><br><br>
        <!-- Search Bar -->
        <div class="text-center" style="max-width: 800px; margin: 0 auto;">
            <div class="search-bar">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control rounded-pill" placeholder="Search for restaurants" name="query" style="width: 70%;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary yellow rounded-pill" type="submit" style="width: 100%;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br><br><br><br>
        <!-- Recommended Restaurants -->
        <div class="recommended-restaurants">
            <h4>Recommended Restaurants</h4><br>
            <div class="restaurants-container" id="restaurants-container">
                @foreach($restaurants as $restaurant)
                <div class="col-md-4 restaurant-card">
                    <div>
                        <a href="{{ route('view_restaurant', ['id' => $restaurant->id]) }}">
                            <div class="restaurant-img">
                                @if($restaurant->logo_pic)
                                    <img src="{{ asset('storage/' . $restaurant->logo_pic) }}" alt="{{ $restaurant->name }}">
                                @else
                                    <img src="{{ asset('assets/dist/img/defaultPic.png') }}" alt="Default">
                                @endif
                            </div>
                            <p class="lucida-handwriting" style="font-size: 20px; color: #052d64; ">{{ $restaurant->name }}</p>
                        </a>
                        <div class="d-flex justify-content-center">
                            <div class="rating">
                                @php
                                    // Retrieve ratings for the current restaurant
                                    $ratings = $restaurant->ratings;
                                    
                                    // Calculate average rating
                                    $totalRating = 0;
                                    $count = count($ratings);
                                    foreach ($ratings as $rating) {
                                        $totalRating += $rating->mark;
                                    }
                                    $averageRating = $count > 0 ? $totalRating / $count : 0;

                                    // Determine the number of filled stars
                                    $filledStars = floor($averageRating);
                                    // Determine if there should be a half-filled star
                                    $halfStar = $averageRating - $filledStars >= 0.5;
                                @endphp

                                <!-- Display filled stars -->
                                @for ($i = 0; $i < $filledStars; $i++)
                                    <span class="fa fa-star"></span>
                                @endfor

                                <!-- Display half-filled star if needed -->
                                @if ($halfStar)
                                    <span class="fa fa-star-half-o"></span>
                                @endif

                                <!-- Display empty stars -->
                                @for ($i = $filledStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                    <span class="fa fa-star-o"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="show-more-btn" id="prev-btn" style="display: none;" onclick="showPrevious()">&#171; Previous</div>
            @if(count($restaurants) > 4)
                <div class="show-more-btn" id="next-btn" onclick="showNext()">Next &#187;</div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var currentPage = 1;
    var totalPages = Math.ceil({{ count($restaurants) }}/4);

    function showPage(page) {
        var start = (page - 1) * 4;
        var end = start + 4;
        var cards = document.querySelectorAll('.restaurant-card');
        for (var i = 0; i < cards.length; i++) {
            if (i >= start && i < end) {
                cards[i].style.display = 'flex';
            } else {
                cards[i].style.display = 'none';
            }
        }

        if (currentPage < totalPages) {
            document.getElementById('next-btn').style.display = 'block';
        } else {
            document.getElementById('next-btn').style.display = 'none';
        }

        if (currentPage > 1) {
            document.getElementById('prev-btn').style.display = 'block';
        } else {
            document.getElementById('prev-btn').style.display = 'none';
        }
    }

    function showNext() {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    }

    function showPrevious() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    }

    // Initially show the first page
    showPage(currentPage);
</script>
@endsection
