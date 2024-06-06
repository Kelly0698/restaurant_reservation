@extends('layouts')
@section('title', 'Table Arrangement')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Table Arrangement</title>
    <style>
        .draggable {
            border: 1px solid #000;
            text-align: center;
            cursor: move;
            position: relative;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.15);
        }
        .diningTable, .counter, .door, .toilet {
            width: 100px;
            height: 100px;
            line-height: 100px;
        }
        .diningTable {
            background-color: #f00;
        }
        .counter {
            background-color: #0f0;
        }
        .door {
            background-color: #00f;
        }
        .toilet {
            background-color: #ff0;
        }
        .droppable {
            width: 800px;
            height: 600px;
            border: 2px dashed #000;
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<div class="content-wrapper">
    <div class="container-fluid py-4"> 
        <h3>Set Up Your Restaurant Table Arrangement</h3>
        <br>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div id="dragItem1" class="draggable diningTable" draggable="true" ondragstart="drag(event)">Dining Table</div>
            <div id="dragItem2" class="draggable counter" draggable="true" ondragstart="drag(event)">Counter</div>
            <div id="dragItem3" class="draggable door" draggable="true" ondragstart="drag(event)">Door</div>
            <div id="dragItem4" class="draggable toilet" draggable="true" ondragstart="drag(event)">Toilet</div>
        </div>
        <div class="mx-auto" style="width: 800px;">
            <div>
                <div id="dropArea" class="droppable" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
            </div>
        </div>
        <br><br>
        <div class="col-8 mx-auto">
            <p>Please take a screenshot of the table arrangement and upload it:</p>
            <form id="image-upload-form" method="POST" action="{{ route('table_arrangement_pic') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ Auth::guard('restaurant')->user()->id }}">
                <div class="form-group">
                    <label for="table_num">Number of Tables:</label>
                    <input type="number" class="form-control" name="table_num" id="table_num" min="1" required>
                </div>
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="table_arrange_pic" id="table_arrange_pic" accept="image/*" onchange="previewImage()">
                        <label class="custom-file-label" for="table_arrange_pic">Choose file</label>
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn yellow">Start Upload</button>
                    </div>
                </div>
                <div id="image-preview" class="mt-2"></div> <!-- Image preview container -->
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var itemCounter = {diningTable: 0, counter: 0, door: 0, toilet: 0};

    function allowDrop(event) {
        event.preventDefault();
    }

    function drag(event) {
        event.dataTransfer.setData("text", event.target.id);
    }

    function drop(event) {
        event.preventDefault();
        var data = event.dataTransfer.getData("text");
        var draggableElement = document.getElementById(data);
        var clone = draggableElement.cloneNode(true);
        var dropArea = document.getElementById('dropArea');

        // Calculate the correct drop position
        var dropAreaRect = dropArea.getBoundingClientRect();
        var elementRect = draggableElement.getBoundingClientRect();
        var x = event.clientX - dropAreaRect.left + dropArea.scrollLeft - elementRect.width / 2;
        var y = event.clientY - dropAreaRect.top + dropArea.scrollTop - elementRect.height / 2;

        // Ensure the element stays within the bounds of the drop area
        x = Math.max(0, Math.min(x, dropArea.clientWidth - elementRect.width));
        y = Math.max(0, Math.min(y, dropArea.clientHeight - elementRect.height));

        var itemType = draggableElement.classList[1];
        itemCounter[itemType]++;
        clone.textContent = itemType.charAt(0).toUpperCase() + itemType.slice(1) + ' ' + itemCounter[itemType];
        clone.style.left = x + "px";
        clone.style.top = y + "px";
        clone.style.position = "absolute";
        clone.onclick = function() {
            this.remove();
            itemCounter[itemType]--; // Decrement the counter when removing the box
            updateCoordinatesList();
        };
        clone.id = "";
        clone.ondragstart = function(event) {
            event.dataTransfer.setData("text", this.outerHTML);
            this.parentNode.removeChild(this);
            itemCounter[itemType]--; // Decrement the counter when dragging the box
            updateCoordinatesList();
        };
        clone.classList.add(itemType);
        dropArea.appendChild(clone);
        updateCoordinatesList();
    }

    function updateCoordinatesList() {
        var diningTableList = document.getElementById('diningTableCoordinates');
        var counterList = document.getElementById('counterCoordinates');
        var doorList = document.getElementById('doorCoordinates');
        var toiletList = document.getElementById('toiletCoordinates');
        diningTableList.innerHTML = '';
        counterList.innerHTML = '';
        doorList.innerHTML = '';
        toiletList.innerHTML = '';
        itemCounter = {diningTable: 0, counter: 0, door: 0, toilet: 0};
        var items = document.querySelectorAll('#dropArea > div');
        items.forEach(function(item, index) {
            var itemType = item.classList[1];
            itemCounter[itemType]++;
            item.textContent = itemType.charAt(0).toUpperCase() + itemType.slice(1) + ' ' + itemCounter[itemType];
            var listItem = document.createElement('li');
            listItem.textContent = item.textContent + ': ' + item.style.left + ', ' + item.style.top;
            if (item.classList.contains('diningTable')) {
                diningTableList.appendChild(listItem);
            } else if (item.classList.contains('counter')) {
                counterList.appendChild(listItem);
            } else if (item.classList.contains('door')) {
                doorList.appendChild(listItem);
            } else if (item.classList.contains('toilet')) {
                toiletList.appendChild(listItem);
            }
        });
    }

    function previewImage() {
        var fileInput = document.getElementById('table_arrange_pic');
        var imagePreview = document.getElementById('image-preview');

        // Remove any existing preview
        imagePreview.innerHTML = '';

        // Check if there is a file selected
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-fluid', 'mt-2'); // Add Bootstrap classes for styling
                imagePreview.appendChild(img);
            }

            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    // Handle form submission
    document.getElementById('image-upload-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        
        // Create FormData object to send form data
        var formData = new FormData(this);

        // Send AJAX request to upload the image
        fetch('{{ route("table_arrangement_pic") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => {
            if (response.ok) {
                // If the upload was successful, show SweetAlert success message
                Swal.fire({
                    title: 'Success!',
                    text: 'Table arrangement picture uploaded successfully',
                    icon: 'success',
                }).then(() => {
                    // Reload the page to ensure the success message is displayed
                    location.reload();
                });
            } else {
                // If there was an error, show an error message
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to upload table arrangement picture',
                    icon: 'error',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // If there was a network error, show an error message
            Swal.fire({
                title: 'Error!',
                text: 'Failed to upload table arrangement picture',
                icon: 'error',
            });
        });
    });
</script>
@endsection
