<?php

namespace App\Enums;

enum EntityDocumentType: int
{
    case CPF = 10;
    case CNPJ = 20;
    case OTHER = 30;

    public function label()
    {
        return match ($this) {
            EntityDocumentType::CPF => 'CPF',
            EntityDocumentType::CNPJ => 'CNPJ',
            EntityDocumentType::OTHER => __('Other'),
            default => $this->name ?? null,
        };
    }
}
