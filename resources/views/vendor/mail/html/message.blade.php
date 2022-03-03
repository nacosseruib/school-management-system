@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
           
    {{-- Footer --}}
     @php
        if(Session::get('getSchoolProfile') and Session::get('path'))
        {
            $getSchoolProfile = Session::get('getSchoolProfile');
            $newPath = Session::get('path');
        }else{
            $getSchoolProfile = DB::table('school_profile')->where('id', DB::table('school_profile')->orderBy('id', 'Desc')->value('id'))->where('active', 1)->first();
            $newPath = "appAssets/schoolLogo/";
        }
        $schoolDetails  = DB::table('school_profile')->where('id', DB::table('school_profile')->orderBy('id', 'Desc')->value('id'))->where('active', 1)->select('school_full_name', 'email', 'address', 'phone_no')->first();
    @endphp
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {!! ($schoolDetails ? $schoolDetails->school_full_name .'<br />'. $schoolDetails->address .'<br />'. $schoolDetails->phone_no .'<br />' : 'SCHOOL E-PORTAL.COM' .'<br />') !!}. @lang('All rights reserved.')
            {!! '<br /><img style="width: 60px; height: 80px" src="'. ($getSchoolProfile ? asset($newPath . $getSchoolProfile->logo) : '') .'" class="img-responsive" alt=" " />' !!}
        @endcomponent
    @endslot
@endcomponent
