<?php

namespace App\Exports;

use App\Models\StudentClass; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportClass  implements FromCollection
{

    public function collection()
    {

        //return StudentClass::all();
        try {
            return StudentClass::where('active', 1)
                ->select('class_code', 'class_name', 'description', 'created_at')
                ->get();
        } catch (\Exception $e) {
            
           return; //$e->getMessage();
        }

    }

}
