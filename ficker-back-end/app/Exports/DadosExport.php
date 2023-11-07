<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class DadosExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $dados;

    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    public function collection()
    {
        return $this->dados;
    }

    public function headings(): array
    {
        return [
            'NOME',
            'DESCRIÇÃO',
            'DATA',
            'VALOR',
            'PARCELAS',
            'CARTÃO',
            'CATEGORIA',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }
}
