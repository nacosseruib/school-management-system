<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\TempMark; 
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\StudentClass;
use App\Models\Term;
use Auth;

class ImportMark  implements ToModel
{

    public function model(array $row)
    {
        try {
            
            return new TempMark([
                'studentID'         => ($row[0] ? $studentID = Student::where('student_regID',  trim($row[0]))->value('studentID') : $studentID = ''),
                'student_regID'     => ($studentID ? Student::where('studentID', $studentID)->value('student_regID') : ''),
                'classID'           => ($row[1] ? $classID = StudentClass::where('class_code', $row[1])->value('classID') : $classID = ''),
                'class_name'        => ($classID ? StudentClass::where('classID', $classID)->value('class_name') : ''),
                'subjectID'         => (($row[2] and $classID) ? $subjectID = StudentSubject::where('subject_code', $row[2])->where('classID', $classID)->value('subjectID') : $subjectID = ''),
                'subject_name'      => ($subjectID ? StudentSubject::where('subjectID', $subjectID)->value('subject_name') : ''),
                'session_code'      => $row[3],
                'termID'            => ($row[4] ? $termID = Term::where('term_code', $row[4])->value('termID') : $termID = ''),
                'term_name'         => ($termID ? Term::where('termID', $termID)->value('term_name') : ''),
                'test1'             => str_replace(',', '', (number_format($row[5], 1))), 
                'test2'             => str_replace(',', '', (number_format($row[6], 1))), 
                'exam'              => str_replace(',', '', (number_format($row[7], 1))), 
                'computed_by_ID'    => Auth::User()->id,
                'computed_by'       => Auth::User()->name .' '. Auth::User()->other_name,
                'created_at'        => date('Y-m-d'),
            ]);
        } catch (\Exception $e) {
        
            return; //$e->getMessage();
        }

    }

}
