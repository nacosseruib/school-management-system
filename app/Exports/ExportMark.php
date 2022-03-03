<?php

namespace App\Exports;

use App\Models\TempMark; 
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportMark  implements FromCollection
{

    public function collection()
    {

        return TempMark::all();

    }

}
