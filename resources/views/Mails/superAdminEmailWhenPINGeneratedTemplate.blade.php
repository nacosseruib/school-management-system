@component('mail::message')

Hello <b> Administrator!</b>,

This is to notify you that below are the list of PINs generated for the above school for {{ $session .'-'. $term }}


<table border="1" cellspacing="0" cellpadding="10" align="center" class="table table-striped table-bordered table-hover" style="width:100%;">
    <caption align='top'></caption>
    <tr>
        <th color="black">SN</th>
        <th color="black">PIN</th>
        <th color="black">REGNO</th>
        <th color="black">CLASS</th>
        <th color="black">NAME</th>
        <th color="black">PIN TYPE</th>
    </tr>
    <tr>
        @forelse($getStudentPIN as $key=>$list)
        <tr>
            <td>{{ 1+$key }}</td>
            <td style="color: red"><b>{{ $list->pin }}</b></td>
            <td style="color: green">{{ $list->student_regID }}</td>
             <td>{{ $list->class_name }}</td>
            <td><small>{{ $list->student_firstname .' '. $list->student_lastname }}</small></td>
            <td>{{ $list->pin_type }}</td>
        </tr>
        @empty
            <tr>
                <td colspan="7">No record fund!</td>
            </tr>
        @endforelse
    
</table>


@component('mail::button', ['url' => $loginUrl])

Login to {{ $schoolName }} E-portal 

@endcomponent

Best Regards,<br />
{{ $schoolName }} Admin.

@endcomponent
