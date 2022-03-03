<?php

namespace App\Exports;

use App\Models\Student; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportBasicStudent  implements FromCollection
{

    public function collection()
    {
       
       try {
            return Student::where('student.active', 1)->where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->select('student.admitted_date','student.student_regID', 'student_class.class_name', 'student.student_roll', 'student.student_lastname',
                 'student.student_firstname', 'student.student_gender', 'student.created_at')
                ->get();
        } catch (\Exception $e) {
            
           return; //$e->getMessage();
        }

    }


}//
