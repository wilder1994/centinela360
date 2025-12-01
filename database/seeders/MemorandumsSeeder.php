<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Memorandum;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemorandumsSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first() ?? Company::factory()->create(['name' => 'Empresa Demo']);
        $autor = User::where('email', 'empresa@centinela.test')->first()
            ?? User::factory()->create([
                'name' => 'Empresa Demo',
                'email' => 'empresa@centinela.test',
                'company_id' => $company->id,
                'password' => bcrypt('password'),
            ]);

        $estados = [
            'pending' => 4,
            'en_proceso' => 4,
            'finalizado' => 4,
        ];

        $prioridades = ['urgente', 'alta', 'media', 'baja'];
        $count = 1;

        foreach ($estados as $estado => $cantidad) {
            for ($i = 1; $i <= $cantidad; $i++, $count++) {
                Memorandum::create([
                    'company_id' => $company->id,
                    'author_id' => $autor->id,
                    'assigned_to' => null,
                    'approved_by' => null,
                    'puesto' => 'Cliente Demo ' . $count,
                    'title' => ucfirst(str_replace('_', ' ', $estado)) . " #{$i}",
                    'body' => "Memorando de ejemplo en estado {$estado}.",
                    'estado' => $estado,
                    'prioridad' => $prioridades[$count % count($prioridades)],
                    'vence_en' => now()->addDays($count),
                ]);
            }
        }
    }
}
