<?php

namespace App\Exports;

use App\User; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportBasicTeacher implements FromCollection
{

    public function collection()
    {
       
       try {
            return User::where('deleted', 0)->where('suspend', 0)->where('id', '<>', 1)
                ->select('admitted_date', 'userRegistrationId', 'name', 'other_name', 'gender', 'designation', 'email', 'telephone')
                ->get();
        } catch (\Exception $e) {
            
           return; //$e->getMessage();
        }

    }


}//
