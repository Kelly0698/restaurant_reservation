<!DOCTYPE html>
<html>
<head>
    <title>{{ $status === 'Approved' ? 'Reservation Approved' : 'Reservation Rejected' }}</title>
</head>
<body>
    <h1>{{ $status === 'Approved' ? 'Reservation Approved' : 'Reservation Rejected' }}</h1>
    
    <p>Dear {{ $reservation->user->name }},</p>
    
    <p>Your reservation request for {{ $reservation->restaurant->name }} has been 
    {{ $status === 'Approved' ? 'Approved' : 'Rejected' }}.</p>
    
    @if($status === 'Approved')
    <p>Details:</p>
    <ul>
        <li>Reservation Date: {{ $reservation->date }}</li>
        <li>Time: {{ $reservation->time }}</li>
        <li>Party Size: {{ $reservation->party_size }}</li>
    </ul>
    @endif
    
    <p>Thank you!</p>
</body>
</html>
