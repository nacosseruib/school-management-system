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
<div class="col-sm-12 text-center">
    <div align="center">
        @if($showLogo == 1)
          <img style="width: 100px; height: 120px" src="{{ $getSchoolProfile ? asset($newPath . $getSchoolProfile->logo) : '' }}" class="img-responsive" alt=" " /><!--rounded-circle-->
        @endif
        @if($showSchooName == 1)
        <div class="mt-0 mb-0">
          <span style="font-weight:bolder; font-size:20px; color:purple;"><b> <b> 
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
        @endif
    </div> 
</div>
</div>
@endif