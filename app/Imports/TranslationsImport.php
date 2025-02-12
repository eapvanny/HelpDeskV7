<?php

namespace App\Imports;

use App\Models\Translation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TranslationsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Translation::updateOrCreate(
            [
                'item' => $row['item'],
                'locale' => $row['locale']
            ],
            ['text' => $row['text']]
        );
    }
}

