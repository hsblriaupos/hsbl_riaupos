<?php

namespace App\Exports;

use Illuminate\Support\Facades\Response;

class TeamsExport
{
    protected $teams;
    protected $columns;
    
    protected $columnNames = [
        'team_id' => 'ID Tim',
        'school_name' => 'Nama Sekolah',
        'team_category' => 'Kategori Tim',
        'competition' => 'Kompetisi',
        'season' => 'Season/Tahun',
        'series' => 'Series',
        'registered_by' => 'Didaftar Oleh',
        'referral_code' => 'Referral Code',
        'locked_status' => 'Status Kunci',
        'verification_status' => 'Status Verifikasi',
        'payment_status' => 'Status Pembayaran',
        'created_at' => 'Tanggal Dibuat',
        'updated_at' => 'Terakhir Update',
    ];
    
    public function __construct($teams, $columns = [])
    {
        $this->teams = $teams;
        $this->columns = !empty($columns) ? $columns : [
            'team_id', 'school_name', 'team_category', 'competition', 
            'season', 'series', 'registered_by', 'referral_code',
            'locked_status', 'verification_status', 'payment_status', 
            'created_at', 'updated_at'
        ];
    }
    
    public function download($filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF"); // BOM untuk UTF-8
            
            // Header
            $headings = [];
            foreach ($this->columns as $col) {
                $headings[] = $this->columnNames[$col] ?? ucfirst(str_replace('_', ' ', $col));
            }
            fputcsv($output, $headings);
            
            // Data
            foreach ($this->teams as $team) {
                $row = [];
                foreach ($this->columns as $col) {
                    $value = $team->$col ?? '-';
                    
                    if ($col == 'locked_status') {
                        $value = $value == 'locked' ? 'Terkunci' : 'Terbuka';
                    } elseif ($col == 'verification_status') {
                        $value = $value == 'verified' ? 'Terverifikasi' : 'Belum Verifikasi';
                    } elseif ($col == 'payment_status') {
                        $statusMap = ['pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal'];
                        $value = $statusMap[$value] ?? $value;
                    } elseif (in_array($col, ['created_at', 'updated_at']) && $value && $value !== '-') {
                        $value = date('d-m-Y H:i:s', strtotime($value));
                    }
                    
                    $row[] = $value;
                }
                fputcsv($output, $row);
            }
            
            fclose($output);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}