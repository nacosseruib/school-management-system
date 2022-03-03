@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('View Student Result Sheet')) }} @endsection 
@section('homePageActive') active @endsection 
@section('content')
<div class="main-content">
    <div class="content-wrapper">
            @if(isset($student))
                <section id="extended">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card {{$classWaterMark}}">
                                <div class="card-header">
                                    <div style="margin-bottom: 10px; height: 1500px;"><!--start report-->
                                        <!--report Template 1-->
                                        @if($templateCode == 1)

                                            @include('report.reportCardTemplate1.head')
                                            <br />
                                            @if($midFullTermResult == 1)
                                                <!--report body MID-TERM-->
                                                @include('report.reportCardSheet.bodyMidTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryMidTerm')
                                            @else
                                                <!--report body FULL-TERM-->
                                                @include('report.reportCardSheet.bodyFullTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryFullTerm')
                                            @endif
                                            
                                            <!--report Template 2-->
                                        @elseif($templateCode == 2)

                                            @include('report.reportCardTemplate2.head')
                                            <br />
                                            @if($midFullTermResult == 1)
                                                <!--report body MID-TERM-->
                                                @include('report.reportCardSheet.bodyMidTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryMidTerm')
                                            @else
                                                <!--report body FULL-TERM-->
                                                @include('report.reportCardSheet.bodyFullTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryFullTerm')
                                            @endif

                                            <!--report Template 3-->
                                        @elseif($templateCode == 3)

                                            @include('report.reportCardTemplate3.head')
                                            <br />
                                            @if($midFullTermResult == 1)
                                                <!--report body MID-TERM-->
                                                @include('report.reportCardSheet.bodyMidTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryMidTerm')
                                            @else
                                                <!--report body FULL-TERM-->
                                                @include('report.reportCardSheet.bodyFullTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryFullTerm')
                                            @endif

                                            <!--report Template 4-->
                                        @elseif($templateCode == 4)

                                            @include('report.reportCardTemplate4.head')
                                            <br />
                                            @if($midFullTermResult == 1)
                                                <!--report body MID-TERM-->
                                                @include('report.reportCardSheet.bodyMidTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryMidTerm')
                                            @else
                                                <!--report body FULL-TERM-->
                                                @include('report.reportCardSheet.bodyFullTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryFullTerm')
                                            @endif

                                            <!--report Template 0 or Default-->
                                        @else

                                            @include('report.reportCardTemplate0.head')
                                            <br />
                                            @if($midFullTermResult == 1)
                                                <!--report body MID-TERM-->
                                                @include('report.reportCardSheet.bodyMidTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryMidTerm')
                                            @else
                                                <!--report body FULL-TERM-->
                                                @include('report.reportCardSheet.bodyFullTerm')
                                                <hr class="mb-0 mt-0">
                                                <!--report summary-->
                                                @include('report.reportCardSheet.summaryFullTerm')
                                            @endif

                                        @endif
                                    </div><!--//end repot-->
                                </div>
                            </div>
                        </div><!--//row-->
                    </div>
                </section>
                <!--report COMMENT-->
                @include('report.reportCardSheet.comment')
            @endif
    </div><!--end content-wrapper-->
</div><!--end main content-->
@endsection

@section('styles')
<style>
        .font1{
            font-family: "Montserrat", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size:9px !important;
            font-weight:200 !important;
        }
        .font2{
            font-family: "Montserrat", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size:10px !important;
            font-weight:300 !important;
        }
        .font3{
            font-family: "Montserrat", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size:11px !important;
            font-weight:400 !important;
        }
        .font4{ /*for names */
            font-family: "Montserrat", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-size:13px !important;
            font-weight:400 !important;
        }
        .font5{
            font-family: 'Merriweather', serif !important;
            font-size:14px !important;
        }
        .font6{
            font-family: 'Merriweather', serif !important;
            font-size:16px !important;
        }
        .mainTable{
            opacity: 0.9;
            border: 1px;
            width: 100%;
            background: transparent;
            background-color: #FFF;
        }
        .bgImage{
            background-image: url("{{ ((Session::get('getSchoolProfile')) ? asset('appAssets/schoolLogo/'. Session::get('getSchoolProfile')->logo) : '') }}"); 
            background-repeat:no-repeat; 
            background-position: center; 
            background-size:200px; 
            height:600px; 
            top: -25px; 
        }
        .bgImageNotOfficial{ 
            background-image: url("{{ ((Session::get('getSchoolProfile')) ? asset('appAssets/app/not-official.png') : '') }}"); 
            background-repeat:repeat; 
            background-position: center; 
            background-size:300px; 
        }
        .bgImageOriginal{ 
            background-image: url("{{ ((Session::get('getSchoolProfile')) ? asset('appAssets/app/original.png') : '') }}"); 
            background-repeat:repeat; 
            background-position: center; 
            background-size:300px; 
        }
        .table_morecondensed>thead>tr>th, 
        .table_morecondensed>tbody>tr>th, 
        .table_morecondensed>tfoot>tr>th, 
        .table_morecondensed>thead>tr>td, 
        .table_morecondensed>tbody>tr>td, 
        .table_morecondensed>tfoot>tr>td{ 
            padding: 2px !important;
            border:1px solid #333 !important;
            width: 10% !important;
         }
         .custom-control-label{
             float:none !important;
         }
    </style>
@endsection
