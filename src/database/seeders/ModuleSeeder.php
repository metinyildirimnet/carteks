<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::updateOrCreate(
            ['key' => 'bank_transfer'],
            [
                'name' => 'Havale ile Ödeme',
                'description' => 'Müşterilerin banka havalesi/EFT ile ödeme yapmasını sağlar. Ayarlarından banka hesap bilgilerinizi yönetebilirsiniz.',
                'is_active' => true,
                'settings' => json_encode([
                    'accounts' => [
                        [
                            'bank_name' => 'Örnek Banka A.Ş.',
                            'account_holder' => 'Hesap Sahibi Adı',
                            'iban' => 'TR00 0000 0000 0000 0000 0000',
                            'description' => 'Açıklama alanına sipariş numaranızı yazınız.'
                        ]
                    ]
                ])
            ]
        );
    }
}
