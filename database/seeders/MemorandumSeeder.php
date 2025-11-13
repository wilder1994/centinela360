<?php

namespace Database\Seeders;

use App\Enums\MemorandumStatus;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Memorandum;
use App\Models\MemorandumStatusHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class MemorandumSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            return;
        }

        $author = $company->users()->first();

        if (!$author) {
            return;
        }

        $employees = Employee::where('company_id', $company->id)->get();
        $faker = fake('es_CO');
        $statuses = MemorandumStatus::cases();

        foreach (range(1, 10) as $index) {
            $status = Arr::random($statuses);
            $issuedAt = Carbon::now()->subDays(rand(1, 60));

            $employeeId = $employees->isNotEmpty() ? $employees->random()->id : null;

            $memorandum = Memorandum::create([
                'company_id' => $company->id,
                'user_id' => $author->id,
                'employee_id' => $employeeId,
                'subject' => ucfirst($faker->words(rand(3, 7), true)),
                'body' => $faker->paragraphs(3, true),
                'status' => $status->value,
                'issued_at' => $issuedAt,
                'acknowledged_at' => $status === MemorandumStatus::ACKNOWLEDGED
                    ? (clone $issuedAt)->addDays(rand(1, 5))
                    : null,
            ]);

            $this->seedHistory($memorandum, $author->id, $status, $issuedAt);
        }
    }

    private function seedHistory(Memorandum $memorandum, int $userId, MemorandumStatus $finalStatus, Carbon $issuedAt): void
    {
        $statusFlow = [
            MemorandumStatus::DRAFT,
            MemorandumStatus::IN_REVIEW,
            MemorandumStatus::ACKNOWLEDGED,
            MemorandumStatus::ARCHIVED,
        ];

        $previousStatus = null;
        $timestamp = (clone $issuedAt);

        foreach ($statusFlow as $status) {
            if ($status === MemorandumStatus::ARCHIVED && $finalStatus !== MemorandumStatus::ARCHIVED) {
                break;
            }

            MemorandumStatusHistory::create([
                'memorandum_id' => $memorandum->id,
                'from_status' => $previousStatus?->value,
                'to_status' => $status->value,
                'changed_by' => $userId,
                'notes' => $status === MemorandumStatus::IN_REVIEW
                    ? 'El memorándum pasó a revisión del supervisor.'
                    : ($status === MemorandumStatus::ACKNOWLEDGED
                        ? 'El colaborador confirmó la recepción del memorándum.'
                        : ($status === MemorandumStatus::ARCHIVED ? 'Memorándum archivado para consulta futura.' : null)),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            if ($status === $finalStatus) {
                break;
            }

            $previousStatus = $status;
            $timestamp = (clone $timestamp)->addDays(rand(2, 6));
        }
    }
}
