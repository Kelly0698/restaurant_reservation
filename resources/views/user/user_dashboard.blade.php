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
            padding-bottom: 20px;
        }
        .restaurant-card {
            flex: 0 0 20%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .restaurant-card a {
            display: block;
            text-align: center;
        }
        .restaurant-card:last-child {
            margin-right: 0;
            border: 0px solid #ccc;
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
            font-size: 24px;
        }
        .rating .fa-star,
        .rating .fa-star-half-o,
        .rating .fa-star-o {
            position: relative;
            color: #ffc107;
            border: 1px solid transparent;
        }
        .rating .fa-star-o::before,
        .rating .fa-star-half-o::before {
            content: '\f005';
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            color: transparent;
            -webkit-text-stroke: 1px #ffc107;
        }
        .rating .fa-star-half-o::after {
            content: '\f089';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            overflow: hidden;
            color: #ffc107;
            -webkit-text-stroke: 0;
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
        <br><br><br><br>
        <!-- Search Bar -->
        <div class="text-center" style="max-width: 800px; margin: 0 auto;">
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
                            <!-- Heart Icon -->
                            @php
                                $liked = Auth::user()->likes()->where('restaurant_id', $restaurant->id)->exists();
                            @endphp
                            <span class="heart-icon" onclick="saveRestaurant(event, {{ $restaurant->id }})">
                                <i class="fa {{ $liked ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
                            </span>
                        </div>
                        <p class="lucida-handwriting" style="font-size: 20px; color: #052d64;">{{ $restaurant->name }}</p>
                    </a>
                    <div class="d-flex justify-content-center">
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
        var end = Math.min(start + 4, {{ count($restaurants) }});
        var cards = document.querySelectorAll('.restaurant-card');
        var count = 0;
        for (var i = 0; i < cards.length; i++) {
            if (i >= start && i < end) {
                cards[i].style.display = 'flex';
                count++;
            } else {
                cards[i].style.display = 'none';
            }
        }

        // Add placeholder elements if there are fewer than 4 cards or no cards at all
        var remaining = Math.max(4 - count, 0);
        var placeholderHtml = '';
        for (var j = 0; j < remaining; j++) {
            placeholderHtml += '<div class="col-md-4 restaurant-card"></div>';
        }
        document.getElementById('restaurants-container').innerHTML += placeholderHtml;

        // Apply border style only to cards representing actual restaurants, not placeholders
        var allCards = document.querySelectorAll('.restaurant-card');
        allCards.forEach(function(card) {
            if (card.querySelector('*')) { // Check if card contains any child element
                card.style.border = '1px solid #ccc';
            } else {
                card.style.border = 'none'; // Remove border from empty placeholders
            }
        });

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