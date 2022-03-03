@component('mail::message')

Welcome <b>{{$name}}!</b>,

Congrats! <br />

Your account has been created. <br />

Below are your login details:
<br />
<b>Staff Registration No.: {{$staffID}}</b> <br />
<b>Designation:</b> {{$post}} <br />
<b>Email:</b> {{$email}} <br />
<b>Password:</b> {{$password}} <br />

It looks you are good to go.

@component('mail::button', ['url' => $loginUrl])

Login now 

@endcomponent

Best Regards,<br />
{{ $schoolName }} Admin.

@endcomponent
