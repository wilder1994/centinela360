<?php

namespace App\Enums;

enum MemorandumStatus: string
{
    case DRAFT = 'draft';
    case IN_REVIEW = 'in_review';
    case ACKNOWLEDGED = 'acknowledged';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Borrador',
            self::IN_REVIEW => 'En revisión',
            self::ACKNOWLEDGED => 'Acusado',
            self::ARCHIVED => 'Archivado',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-yellow-100 text-yellow-700',
            self::IN_REVIEW => 'bg-blue-100 text-blue-700',
            self::ACKNOWLEDGED => 'bg-green-100 text-green-700',
            self::ARCHIVED => 'bg-gray-200 text-gray-600',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases()
        );
    }
}
