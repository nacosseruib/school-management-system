<?php

namespace App\Exports;

use App\Models\StudentSubject; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportSubject  implements FromCollection
{

    public function collection()
    {
       
       try {
            return StudentSubject::where('student_subject.active', 1)
                ->join('student_class', 'student_class.classID', '=', 'student_subject.classID')
                ->select('student_class.class_name', 'student_subject.subject_code', 'student_subject.subject_name', 'student_subject.subject_description',
                 'student_subject.max_ca1', 'student_subject.max_ca2', 'student_subject.max_exam', 'student_subject.created_at')
                ->get();
        } catch (\Exception $e) {
            
           return; //$e->getMessage();
        }

    }


}//
