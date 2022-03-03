<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\SchoolProfile;
use App\Models\RegistrationFormat;
use Auth;
use Schema;
use Session;
use Image;
use file;
use DB;

class SchoolProfileController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Image uploda path
    public function basePath()
    {
        //Live
        //return "/home/domain/public_html/"; 

        //Local
        return base_path() . '/public/appAssets/schoolLogo/';
    }

    //View all students
    public function createProfile()
    {
        $data['schoolProfile'] = $this->schoolProfile();
        $data['registrationIDFormat'] = RegistrationFormat::where('active', 1)->get();
        $data['path'] = $this->schoolImagePath();
        //Get Watermark and Template
        $returnedData = $this->templateAndWatermark($data['schoolProfile']->report_sheet_template, $data['schoolProfile']->result_sheet_watermark);
        $data['template'] = $returnedData['template'];
        $data['templateCode'] = $returnedData['templateCode'];
        $data['watermark'] = $returnedData['watermark'];
        $data['watermarkCode'] = $returnedData['watermarkCode'];
        //
        return view('setup.schoolProfile', $data);
    }


    public function saveUpdateProfile(Request $request)
    {   
        $this->validate($request, [
            //'schoolRegistrationNo'  => 'string|max:255', 
            //'establishmentDate'     => 'date|max:100', 
            'schoolFullName'         => 'required|string|max:100',
            'schoolShortName'       => 'required|alpha_num|max:100',
            'smsSenderName'         => 'required|alpha_num|max:11',
            //'schoolWebsite'         => 'required|string|max:200',
            //'schoolEmailAddress'    => 'required|email|max:200', 
            //'schoolPhoneNumber'     => 'required|string|max:200',
            //'schoolMotto'           => 'required|string|max:100',
            //'schoolAddress'         => 'required|string|max:500',
            //'studentRegistrationIdFormat' => 'required|numeric|max:100',
            //'allowToRepulishResult' => 'required|numeric|max:10',
        ]);

        $path   = $this->basePath();
        $file   = $request->schoolLogo;
        $signature   = $request->signature; 
        //School Logo
        if($file){
            $this->validate($request, [
                'schoolLogo' => 'image|mimes:png,jpg,JPE,JPG,JPEG,jpe,jpeg,gif|max: 2048',
            ]);
            $extension = $file->getClientOriginalExtension();
            $newFileName = rand(11111,99999).time().'.'.$extension;
            $file->move($path, $newFileName);
            $getImage = Image::make(asset('appAssets/schoolLogo/') .'/'. $newFileName);
            $getImage->resize(300, 200);
        }else{
            $newFileName = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->value('logo');
        }
        //School Signature
        if($signature){
            $this->validate($request, [
                'signature' => 'image|mimes:png,jpg,JPE,JPG,JPEG,jpe,jpeg,gif|max: 2048',
            ]);
            $extension2 = $signature->getClientOriginalExtension();
            $newSignature = rand(11111,99999).time().'.'.$extension2;
            $signature->move($path, $newSignature);
            $getImage2 = Image::make(asset('appAssets/schoolLogo/') .'/'. $newSignature);
            $getImage2->resize(300, 100);
        }else{
            $newSignature = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->value('signature');
        }

        if(SchoolProfile::orderBy('id', 'Desc')->first()){
            $saved = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->update(
                array( 
                    'school_short_name'         => $request->schoolShortName,
                    'school_full_name'          => $request->schoolFullName,
                    'address'                   => $request->schoolAddress,
                    'phone_no'                  => $request->schoolPhoneNumber,
                    'website'                   => $request->schoolWebsite,
                    'email'                     => $request->schoolEmailAddress,
                    'registration_no'           => $request->schoolRegistrationNo,
                    'establishment_date'        => $request->establishmentDate,
                    'slogan'                    => $request->schoolMotto,
                    'updated_at'                => date('Y-m-d H:i:s-A'),
                    'logo'                      => $newFileName,
                    'signature'                 => $newSignature,
                    'student_regID_format'      => $request->studentRegistrationIdFormat,
                    'update_pulish_result'      => $request->allowToRepulishResult,
                    'school_resumption_date'    => $request->schoolResumptionDate,
                    'day_school_open'           => $request->numberOfDaySchoolOpen,
                    'send_email'                => $request->allowEmailToBeSent,
                    'report_sheet_template'     => $request->resultCardTemplate,
                    'result_sheet_watermark'    => $request->resultCardWatermark,
                    'sms_sender_name'           => $request->smsSenderName,
                    'show_fee_breakdown'        => $request->showFeeOnReport,
            ));
        }else{
            $schoolProfile                          =  New SchoolProfile;
            $schoolProfile->school_short_name       = $request->schoolShortName;
            $schoolProfile->school_full_name        = $request->schoolFullName;
            $schoolProfile->address                 = $request->schoolAddress;
            $schoolProfile->phone_no                = $request->schoolPhoneNumber;
            $schoolProfile->website                 = $request->schoolWebsite;
            $schoolProfile->email                   = $request->schoolEmailAddress;
            $schoolProfile->registration_no         = $request->schoolRegistrationNo;
            $schoolProfile->establishment_date      = $request->establishmentDate;
            $schoolProfile->slogan                  = $request->schoolMotto;
            $schoolProfile->created_at              = date('Y-m-d H:i:s-A');
            $schoolProfile->updated_at              = date('Y-m-d H:i:s-A');
            $schoolProfile->schoolID                = date('Y-').rand(11111, 99999).time();
            $schoolProfile->student_regID_format    = $request->studentRegistrationIdFormat;
            $schoolProfile->update_pulish_result    = $request->allowToRepulishResult;
            $schoolProfile->logo                    = $newFileName;
            $schoolProfile->school_resumption_date  = $request->schoolResumptionDate;
            $schoolProfile->day_school_open         = $request->numberOfDaySchoolOpen;
            $schoolProfile->send_email              = $request->allowEmailToBeSent;
            $schoolProfile->report_sheet_template   = $request->resultCardTemplate;
            $schoolProfile->result_sheet_watermark  = $request->resultCardWatermark;
            $schoolProfile->sms_sender_name         = $request->smsSenderName;
            $schoolProfile->show_fee_breakdown      = $request->showFeeOnReport;
            $saved                                  = $schoolProfile->save();
        }
        //
        if($saved){
            //set school profile again
            Session::forget('getSchoolProfile');
            Session::put('getSchoolProfile', $this->schoolProfile());

            return redirect()->route('createProfile')->with('message', 'School profile was updated successfully.');
        }else{
            return redirect()->route('createProfile')->with('error', 'Sorry, we cannot update your school profile! Try again.');
        }
    }


    public function updateRegNumberByJSON(Request $request)
    {
        $data['code'] = $request->enableDisableID;
        $saved = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->update(
            array( 
                'use_auto_reg' => $request->enableDisableID,
                'updated_at'   => date('Y-m-d H:i:s-A'),
        ));
        if($saved)
        {   
            $data['message'] = 'Operation was successfully enable.';
        }else{
            $data['message'] = 'Operation was disable successfully.';
        }
        return $data;
    }


}//end class
