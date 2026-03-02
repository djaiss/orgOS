<?php

declare(strict_types=1);

namespace App\Models;

use Stringable;
use App\Interfaces\HasAddress;

class Address implements Stringable
{
    public function __construct(
        public readonly ?string $line1,
        public readonly ?string $line2,
        public readonly ?string $city,
        public readonly ?string $stateProvince,
        public readonly ?string $postalCode,
        public readonly ?string $country,
    ) {}

    public function __toString(): string
    {
        return $this->format();
    }

    public static function fromModel(HasAddress $model): self
    {
        return new self(
            line1: $model->getAddressLine1(),
            line2: $model->getAddressLine2(),
            city: $model->getCity(),
            stateProvince: $model->getStateProvince(),
            postalCode: $model->getPostalCode(),
            country: $model->getCountryName(),
        );
    }

    public function format(): string
    {
        $parts = [
            $this->line1,
            $this->line2,
            $this->city,
            mb_trim("{$this->stateProvince} {$this->postalCode}"),
            $this->country,
        ];

        return implode(', ', array_filter(array_map(mb_trim(...), $parts)));
    }
}
