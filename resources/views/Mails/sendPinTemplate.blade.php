@component('mail::message')

Welcome <b>{{$name}}'s Parent</b>,

Congrats! <br />

Your child report sheet is ready for you to view/print. <br />

Below are your login details:<br />
<b>Student Registration No.:</b> {{$studentID}}<br />
<b>PIN Number:</b> {{$pin}} <br />

Student Information<br />
<b>Full Name:</b> {{$name}} <br />
<b>Class:</b> {{$class}} <br />
<b>Term:</b> {{$term}} <br />
<b>Session:</b> {{$session}} <br />

<i>We are here to serve you better.</i>

@component('mail::button', ['url' => $loginUrl])

Check Result Now 

@endcomponent
OR follow this link : {{ $loginUrl }}
<br />

Best Regards,<br />
{{$schoolName}} Admin.


@endcomponent