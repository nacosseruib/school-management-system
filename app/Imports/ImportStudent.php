<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\TempStudent; 
use App\Models\StudentClass;

class ImportStudent  implements ToModel
{

    public function model(array $row)
    {
        try {

          return new TempStudent([

            'admitted_date'         => $row[0],
            'student_regID'         => $row[1],
            'student_class'         => ($row[2] ? $classID = StudentClass::where('class_code', $row[2])->value('classID') : $classID = ''),
            'class_name'            => ($classID ? StudentClass::where('classID', $classID)->value('class_name') : ''),
            'student_roll'          => $row[3],
            'student_lastname'      => $row[4],
            'student_firstname'     => $row[5], 
            'student_gender'        => $row[6], 
            'student_address'       => $row[7],
            'parent_lastname'       => $row[8],
            'parent_firstname'      => $row[9],
            'parent_address'        => $row[10],
            'parent_telephone'      => $row[11], 
            'parent_email'          => $row[12],
            'parent_occupation'     => $row[13], 
            
         ]);
        
        } catch (\Exception $e) {
        
            return; //$e->getMessage();
        }

        

    }

}
