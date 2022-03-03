<?php

namespace App\Exports;

use App\User; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportFullTeacher implements FromCollection
{

    public function collection()
    {
       
       try {
            return User::where('deleted', 0)->where('suspend', 0)->where('id', '<>', 1)
                ->join('teacher', 'teacher.userID', '=', 'users.id')
                ->select('users.admitted_date', 'users.userRegistrationId', 'users.name', 'users.other_name', 'users.gender', 'users.designation',
                 'users.email', 'users.telephone', 'users.address', 'teacher.guarantor_firstname', 'teacher.guarantor_lastname', 'teacher.guarantor_address',
                 'teacher.guarantor_telephone', 'teacher.guarantor_email', 'teacher.guarantor_occupation', 'users.updated_at')
                ->get();
        } catch (\Exception $e) {
            
           return; //$e->getMessage();
        }

    }


}//
