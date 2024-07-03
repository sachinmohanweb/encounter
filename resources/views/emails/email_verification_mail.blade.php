<!DOCTYPE html>
<html>
<head>
    <title>Encounter Bible App</title>
</head>
<body>
    <p>Dear {{$data['user']->first_name }},</p>

    <p>
        Your One-Time Password (OTP) for Encounter Bible App login is: 
        <span style="font-size:15px"><b>{{ $data['otp']}}</b></span>.
    </p>

    <p>
        Please use this OTP to complete the verification process and do not share this OTP with anyone.
    </p>

    <p> Warm Regards, <br>Encounter Bible App.</p>
     
    <p>Thank you.</p>
    
</body>
</html>