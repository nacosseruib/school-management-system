@php
  if(Session::get('getSchoolProfile') and Session::get('path'))
  {
    $getSchoolProfile = Session::get('getSchoolProfile');
    $newPath = Session::get('path');
  }else{
    $getSchoolProfile = DB::table('school_profile')->where('id', DB::table('school_profile')->orderBy('id', 'Desc')->value('id'))->where('active', 1)->first();
    $newPath = "appAssets/schoolLogo/";
  }
@endphp
@if($showHeader == 1)
<div class="row">
    <div class="col-md-4 col-sm-4">
        <div align="left">
            @if($showLogo == 1)
              <img style="width: 100px; height: 120px" src="{{ $getSchoolProfile ? asset($newPath . $getSchoolProfile->logo) : '' }}" class="img-responsive" alt=" " /><!--rounded-circle-->
            @endif
        </div> 
    </div>
    <div align="center" class="col-md-8 col-sm-8">
        <div align="left">
            @if($showSchooName == 1)
            <div class="mt-0 mb-0">
              <span style="font-weight:bolder; font-size:17px; color:purple;"><b> <b> 
                {{ $getSchoolProfile ? strtoupper($getSchoolProfile->school_full_name) : '' }} 
              </b></b></span>
              @if($showSlogan == 1)
              <br />
              <span style="font-weight:100; font-size:12px; color:gold;">
                <b><i>{{ $getSchoolProfile ? ucfirst(strtolower($getSchoolProfile->slogan)) : '' }}</i></b>
              </span>
              @endif
            </div>
            @endif
            @if($showTitle == 1 and isset($titleText))
                <span style="font-weight:bolder; font-size:12px;">
                  <b class="text-info">@if(isset($titleText)) {!! strtoupper($titleText) !!} @endif</b>
                </span>
            @else
                <span style="font-weight:bolder; font-size:12px;">
                    {{ $getSchoolProfile ? ucfirst(strtolower($getSchoolProfile->address)) : '' }}
                    <div><small>{{ $getSchoolProfile ? ($getSchoolProfile->phone_no) : '' }}</small></div>
                    <small>{{ $getSchoolProfile ? strtolower($getSchoolProfile->email) : '' }}</small>
                </span>
            @endif
        </div> 
    </div>
</div>
@endif