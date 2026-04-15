<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => env('ADMIN_1_NAME', 'Admin 1 HSBL'),
                'email' => env('ADMIN_1_EMAIL', 'Admin1RPHSBL@sbl.id'),
                'password' => env('ADMIN_1_PASSWORD', '@adminHSBL_RIAUPOS(1)First_2025'),
            ],
            [
                'name' => env('ADMIN_2_NAME', 'Admin 2 HSBL'),
                'email' => env('ADMIN_2_EMAIL', 'admin2RPHSBL@sbl.id'),
                'password' => env('ADMIN_2_PASSWORD', '@adminHSBL_RIAUPOSSecond(2)_2025'),
            ],
            [
                'name' => env('ADMIN_3_NAME', 'Admin 3 HSBL'),
                'email' => env('ADMIN_3_EMAIL', 'admin3RPHSBL@sbl.id'),
                'password' => env('ADMIN_3_PASSWORD', '@adminHSBL_RIAUPOSThird_2025(3)'),
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(  
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                    'role' => 'admin',
                ]
            );
        }
    }
}
