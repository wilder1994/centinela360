<?php

namespace App\Enums;

enum MemorandumStatus: string
{
    case DRAFT = 'draft';
    case PENDING_ACKNOWLEDGMENT = 'pending_acknowledgment';
    case ACKNOWLEDGED = 'acknowledged';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Borrador',
            self::PENDING_ACKNOWLEDGMENT => 'Pendiente de acuse',
            self::ACKNOWLEDGED => 'Acusado',
            self::ARCHIVED => 'Archivado',
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
