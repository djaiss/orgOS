<?php

declare(strict_types=1);

namespace App\Interfaces;

interface HasAddress
{
    public function getAddressLine1(): ?string;
    public function getAddressLine2(): ?string;
    public function getCity(): ?string;
    public function getStateProvince(): ?string;
    public function getPostalCode(): ?string;
    public function getCountryName(): ?string;
}
