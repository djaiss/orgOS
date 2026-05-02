<?php

declare(strict_types=1);

namespace App\Models;

use App\Interfaces\HasAddress;
use Carbon\Carbon;
use Database\Factories\OfficeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Office
 *
 * @property int $id
 * @property int $organization_id
 * @property int|null $country_id
 * @property int|null $office_type_id
 * @property string $name
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $city
 * @property string|null $state_province
 * @property string|null $postal_code
 * @property string|null $timezone
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Address $address
 */
class Office extends Model implements HasAddress
{
    /** @use HasFactory<OfficeFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offices';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'country_id',
        'name',
        'address_line_1',
        'address_line_2',
        'city',
        'state_province',
        'postal_code',
        'timezone',
        'office_type_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Get the organization that owns the office.
     *
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the country of the office.
     *
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the type of the office.
     *
     * @return BelongsTo<OfficeType, $this>
     */
    public function officeType(): BelongsTo
    {
        return $this->belongsTo(OfficeType::class);
    }

    /**
     * Get the first line of the address of the office.
     */
    public function getAddressLine1(): ?string
    {
        return $this->address_line_1;
    }

    /**
     * Get the second line of the address of the office.
     */
    public function getAddressLine2(): ?string
    {
        return $this->address_line_2;
    }

    /**
     * Get the city of the office.
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Get the state or province of the office.
     */
    public function getStateProvince(): ?string
    {
        return $this->state_province;
    }

    /**
     * Get the postal code of the office.
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * Get the country name of the office.
     */
    public function getCountryName(): ?string
    {
        return $this->country?->name;
    }

    /**
     * Get the full address of the office.
     */
    protected function address(): Attribute
    {
        return Attribute::get(fn(): Address => Address::fromModel($this));
    }
}
