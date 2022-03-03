<?php

namespace App\Exports;

use App\Models\TempStudent; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportStudent  implements FromCollection
{

    public function collection()
    {

        return TempStudent::all();

    }

}
