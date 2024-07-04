<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Completed Reservation Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            margin: 0 auto;
            max-width: 800px; /* Adjust as needed */
            box-sizing: border-box;
        }
        .page-header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
            word-wrap: break-word; /* Ensure long words wrap */
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="w3-display-topright" style="color: #070707; margin-bottom: 10px;">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</div>
        <div class="page-header">
            <h2>{{ Auth::guard('restaurant')->user()->name }}</h2>
            <h2>Completed Reservation Record</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Phone Number</th>
                    <th>Reservation Date</th>
                    <th>Time</th>
                    <th>Party Size</th>
                    <th>Table</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doneReservations as $reservation)
                <tr>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ $reservation->user->phone_num }}</td>
                    <td>{{ $reservation->date }}</td>
                    <td>{{ $reservation->time }}</td>
                    <td>{{ $reservation->party_size }}</td>
                    <td>{{ $reservation->table_num ? $reservation->table_num : 'None' }}</td>
                    <td>{{ $reservation->remark ? $reservation->remark : 'None' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
