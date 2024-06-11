@extends('layouts')

@section('title','Table Arrangement')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Table Arrangement</title>
    <style>
        .draggable {
            border: 1px solid #000;
            text-align: center;
            cursor: pointer;
            position: relative;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.15);
        }
        .diningTable, .counter, .door, .toilet {
            width: 100px;
            height: 100px;
            line-height: 100px;
            font-size: 16px;
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
        .window {
            width: 200px; /* Adjust width as needed */
            height: 25px; /* Adjust height as needed */
            background-color: #ffcc00; /* Change color as needed */
            transform-origin: center center;
        }
        .windowV {
            width: 25px; /* Adjust width as needed */
            height: 200px; /* Adjust height as needed */
            background-color: #ffcc00; /* Change color as needed */
            transform-origin: center center;
            writing-mode: vertical-rl; /* Rotate the text to be vertical */
            text-orientation: upright; /* Ensure the text orientation is upright */
            line-height: 25px; /* Set line height to center text vertically */
            text-align: center; /* Center text horizontally */
        }
        .droppable {
            width: 760px;
            height: 600px;
            border: 2px dashed #000;
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<div class="content-wrapper">
    <div class="container-fluid py-4"> 
        <h3>Table Arrangement</h3>
        <br>
        <div class="row">
            <!-- Main Content Card -->
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div id="dragItem1" class="draggable diningTable" draggable="true" ondragstart="drag(event)">Dining Table</div>
                            <div id="dragItem2" class="draggable counter" draggable="true" ondragstart="drag(event)">Counter</div>
                            <div id="dragItem3" class="draggable door" draggable="true" ondragstart="drag(event)">Door</div>
                            <div id="dragItem4" class="draggable toilet" draggable="true" ondragstart="drag(event)">Toilet</div>
                            <div id="dragItem5" class="draggable window" draggable="true" ondragstart="drag(event)">Window</div>
                            <div id="dragItem6" class="draggable windowV" draggable="true" ondragstart="drag(event)">WindowV</div>
                        </div>
                        <div class="col-12 mx-auto" style="width: 800px;">
                            <div>
                                <div id="dropArea" class="droppable" ondrop="drop(event)" ondragover="allowDrop(event)"></div><br>
                                <p style="color:red">*Please confirm the table arrangement, input the number of tables, and click "Start Upload"!</p>
                            </div><br>
                            <button id="download" class="btn blue float-right">Confirm Arrangement</button>
                        </div>
                        <br><br>
                        <div class="col-12 mx-auto">
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
            </div>
            <!-- End of Main Content Card -->
            <!-- Current Table Arrangement Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Current Table Arrangement
                    </div>
                    <div class="card-body">
                        <img id="current-arrangement" class="img-fluid" src="{{ asset('storage/' . Auth::guard('restaurant')->user()->table_arrange_pic) }}" alt="Current Table Arrangement">
                    </div>
                </div>
            </div>
            <!-- End of Current Table Arrangement Card -->
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.esm.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.js"></script>
<script>
    jQuery(document).ready(function() {
        jQuery("#download").click(function() {
            screenshot();
        });
    });

    function screenshot() {
        html2canvas(document.querySelector("#dropArea")).then(function(canvas) {
            canvas.toBlob(function(blob) {
                var file = new File([blob], "table_arrangement.png", { type: "image/png" });
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                var fileInput = document.getElementById('table_arrange_pic');
                fileInput.files = dataTransfer.files;

                // Trigger the change event to preview the image
                previewImage();
            });
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

    var itemCounter = {diningTable: 0, counter: 0, door: 0, toilet: 0, window:0, windowV:0};

    function allowDrop(event) {
        event.preventDefault();
    }

    // Add a click event listener to the window element
    document.getElementById('dragItem5').addEventListener('click', function(event) {
        var rotation = getRotationDegrees(this);
        rotation -= 90; // Rotate 90 degrees to the left
        this.style.transform = 'rotate(' + rotation + 'deg)';
    });

    function drag(event) {
        event.dataTransfer.setData("text", event.target.id);
        var offsetX = event.target.offsetWidth / 2 -11; // Calculate the offset from the center
        var offsetY = event.target.offsetHeight / 2 -11;
        event.dataTransfer.setDragImage(event.target, offsetX, offsetY); // Set the drag image to the center
        
        // Get the current rotation angle of the element
        var rotation = getRotationDegrees(event.target);
        event.target.dataset.rotation = rotation;
    }

    // Function to get the current rotation angle of an element
    function getRotationDegrees(element) {
        var style = window.getComputedStyle(element);
        var matrix = new WebKitCSSMatrix(style.webkitTransform);
        var angle = Math.round(Math.atan2(matrix.b, matrix.a) * (180/Math.PI));
        return angle;
    }

    function drop(event) {
        event.preventDefault();
        var data = event.dataTransfer.getData("text");
        var draggableElement = document.getElementById(data);
        var clone = draggableElement.cloneNode(true);
        var dropArea = document.getElementById('dropArea');

        // Calculate the drop position relative to the drop area
        var dropX = event.clientX - dropArea.getBoundingClientRect().left - draggableElement.offsetWidth / 2;
        var dropY = event.clientY - dropArea.getBoundingClientRect().top - draggableElement.offsetHeight / 2;

        // Ensure the element stays within the bounds of the drop area
        dropX = Math.max(0, Math.min(dropX, dropArea.clientWidth - draggableElement.offsetWidth));
        dropY = Math.max(0, Math.min(dropY, dropArea.clientHeight - draggableElement.offsetHeight));

        var itemType = draggableElement.classList[1];
        itemCounter[itemType]++;
        clone.textContent = itemType.charAt(0).toUpperCase() + itemType.slice(1) + ' ' + itemCounter[itemType];
        clone.style.left = dropX + "px";
        clone.style.top = dropY + "px";
        clone.style.position = "absolute";
        clone.style.transform = draggableElement.style.transform; // Retain the rotation angle
        clone.onclick = function() {
            this.remove();
            itemCounter[itemType]--; // Decrement the counter when removing the box
            this.classList.add('removed'); // Add a class to mark the item as removed
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
    var windowList = document.getElementById('windowCoordinates');
    var windowVList = document.getElementById('windowVCoordinates');
    diningTableList.innerHTML = '';
    counterList.innerHTML = '';
    doorList.innerHTML = '';
    toiletList.innerHTML = '';
    windowList.innerHTML = '';
    windowVList.innerHTML = '';
    itemCounter = {diningTable: 0, counter: 0, door: 0, toilet: 0, window: 0, windowV: 0};
    var items = document.querySelectorAll('#dropArea > div');
    items.forEach(function(item, index) {
        var itemType = item.classList[1];
        itemCounter[itemType]++;
        item.textContent = itemType.charAt(0).toUpperCase() + itemType.slice(1) + ' ' + itemCounter[itemType];
        var listItem = document.createElement('li');
        listItem.textContent = item.textContent + ': ' + event.clientX + ', ' + event.clientY;
        if (item.classList.contains('diningTable')) {
            diningTableList.appendChild(listItem);
        } else if (item.classList.contains('counter')) {
            counterList.appendChild(listItem);
        } else if (item.classList.contains('door')) {
            // Increment the counter for the door item only if it is not being removed
            if (!item.classList.contains('removed')) {
                doorList.appendChild(listItem);
            }
        } else if (item.classList.contains('toilet')) {
            toiletList.appendChild(listItem);
        } else if (item.classList.contains('window')) {
            windowList.appendChild(listItem);
        } else if (item.classList.contains('windowV')) {
            windowVList.appendChild(listItem);
        }
    });
}



    // Handle form submission
    document.getElementById('image-upload-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        
        // Create FormData object to send form data
        var formData = new FormData(this);

        // Send AJAX request to upload the image
        fetch('{{ route("table_arrangement_pic") }}', {
            beforeSend: function() {
                loadingModal();
            }, 
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
