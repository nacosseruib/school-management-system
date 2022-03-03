@component('mail::message')

Hello <b>{{$name}}!</b>,

Your account has been updated. <br />

<span class="text-success">An update was performed on your account. 
We just wanted to make sure it was you who carried out the update</span>

<br />
Below are your details:
<br />
<b>Staff Registration No.: {{$staffID}}</b> <br />
<b>Designation:</b> {{$post}} <br />
<b>Email:</b> {{$email}} <br />
<b>Phone:</b> {{$phone}} <br />
<b>Address:</b> {{$address}} <br />
<b>Date:</b> {{date('d M, Y')}}<br />

<i>If you have not performed this operation, please consult your admin department or email us at admin@schooleportal.com.  </i>
<br />

Thanks<br />

Best Regards,<br />
{{ $schoolName }} Admin.

@endcomponent
