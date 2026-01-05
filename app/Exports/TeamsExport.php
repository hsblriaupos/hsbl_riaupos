<?php

namespace App\Exports;

use App\Models\TeamList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeamsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $teams;

    public function __construct($teams)
    {
        $this->teams = $teams;
    }

    public function collection()
    {
        return $this->teams;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Sekolah',
            'Kode Referral',
            'Season',
            'Series',
            'Kompetisi',
            'Kategori',
            'Status Verifikasi',
            'Status Kunci',
            'Didaftarkan Oleh',
            'Tanggal Daftar'
        ];
    }

    public function map($team): array
    {
        return [
            '', // No akan diisi nanti
            $team->school_name,
            $team->referral_code,
            $team->season,
            $team->series,
            $team->competition,
            $team->team_category,
            $team->verification_status == 'verified' ? 'Terverifikasi' : 
                 ($team->verification_status == 'pending' ? 'Pending' : 
                 ($team->verification_status == 'rejected' ? 'Ditolak' : 'Belum Verifikasi')),
            $team->locked_status == 'locked' ? 'Terkunci' : 'Terbuka',
            $team->registered_by,
            $team->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);

        // Style header row
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3498db']
                ]
            ],
        ];
    }
}