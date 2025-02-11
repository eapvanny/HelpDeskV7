<?php

namespace App\Exports;

use App\Models\Translation;
use Maatwebsite\Excel\Concerns\FromCollection;

class TranslationsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Translation::all();  // You can adjust this as needed.
    }
}
