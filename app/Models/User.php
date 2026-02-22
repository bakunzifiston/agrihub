<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_type',
        'organization_id',
        'is_approved',
        'location',
        'country',
        'district',
        'farm_name',
        'farm_type',
        'cooperative_name',
        'cooperative_focus',
        'members_range',
        'business_name',
        'business_type',
    ];

    public const TENANT_FARMER = 'farmer';
    public const TENANT_COOPERATIVE = 'cooperative';
    public const TENANT_AGRIBUSINESS = 'agribusiness';
    public const TENANT_SUPER_ADMIN = 'super_admin';

    public function isSuperAdmin(): bool
    {
        return $this->tenant_type === self::TENANT_SUPER_ADMIN;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(TenantOrganization::class, 'organization_id');
    }

    public function isOrganizationOwner(): bool
    {
        return $this->organization && $this->organization->owner_id === $this->id;
    }

    // Farmer relations
    public function farmProfiles(): HasMany
    {
        return $this->hasMany(FarmProfile::class, 'farmer_id');
    }

    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class, 'farmer_id');
    }

    public function registeredCrops(): HasMany
    {
        return $this->hasMany(FarmerRegisteredCrop::class, 'farmer_id');
    }

    public function livestock(): HasMany
    {
        return $this->hasMany(Livestock::class, 'farmer_id');
    }

    public function productionRecords(): HasMany
    {
        return $this->hasMany(ProductionRecord::class, 'farmer_id');
    }

    public function farmInputs(): HasMany
    {
        return $this->hasMany(FarmInput::class, 'farmer_id');
    }

    public function farmOutputs(): HasMany
    {
        return $this->hasMany(FarmOutput::class, 'farmer_id');
    }

    public function farmerClients(): HasMany
    {
        return $this->hasMany(FarmerClient::class, 'farmer_id');
    }

    public function farmerEmployees(): HasMany
    {
        return $this->hasMany(FarmerEmployee::class, 'farmer_id');
    }

    public function preOrderListings(): HasMany
    {
        return $this->hasMany(PreOrderListing::class, 'farmer_id');
    }

    public function farmSales(): HasMany
    {
        return $this->hasMany(FarmSale::class, 'farmer_id');
    }

    public function farmInputApplications(): HasMany
    {
        return $this->hasMany(FarmInputApplication::class, 'farmer_id');
    }

    public function registeredProducts(): HasMany
    {
        return $this->hasMany(FarmerRegisteredProduct::class, 'farmer_id');
    }

    public function farmerSuppliers(): HasMany
    {
        return $this->hasMany(FarmerSupplier::class, 'farmer_id');
    }

    // Cooperative relations (when user is cooperative)
    public function cooperativeMembers(): HasMany
    {
        return $this->hasMany(CooperativeMember::class, 'cooperative_id');
    }

    public function cooperativeProfile(): HasOne
    {
        return $this->hasOne(CooperativeProfile::class, 'cooperative_id');
    }

    public function cooperativeCrops(): HasMany
    {
        return $this->hasMany(CooperativeCrop::class, 'cooperative_id');
    }

    public function cooperativeLivestock(): HasMany
    {
        return $this->hasMany(CooperativeLivestock::class, 'cooperative_id');
    }

    public function cooperativeClients(): HasMany
    {
        return $this->hasMany(CooperativeClient::class, 'cooperative_id');
    }

    public function cooperativeOrders(): HasMany
    {
        return $this->hasMany(CooperativeOrder::class, 'cooperative_id');
    }

    public function produceCollections(): HasMany
    {
        return $this->hasMany(ProduceCollection::class, 'cooperative_id');
    }

    public function cooperativeWarehouses(): HasMany
    {
        return $this->hasMany(CooperativeWarehouse::class, 'cooperative_id');
    }

    public function cooperativeInventory(): HasMany
    {
        return $this->hasMany(CooperativeInventory::class, 'cooperative_id');
    }

    public function cooperativePayments(): HasMany
    {
        return $this->hasMany(CooperativePayment::class, 'cooperative_id');
    }

    // Cooperative relations (when user is farmer - member of cooperatives)
    public function cooperativeMemberships(): HasMany
    {
        return $this->hasMany(CooperativeMember::class, 'farmer_id');
    }

    public function produceCollectionsReceived(): HasMany
    {
        return $this->hasMany(ProduceCollection::class, 'farmer_id');
    }

    public function cooperativePaymentsReceived(): HasMany
    {
        return $this->hasMany(CooperativePayment::class, 'farmer_id');
    }

    // Agribusiness relations
    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'agribusiness_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'agribusiness_id');
    }

    public function processingRecords(): HasMany
    {
        return $this->hasMany(ProcessingRecord::class, 'agribusiness_id');
    }

    public function agribusinessWarehouses(): HasMany
    {
        return $this->hasMany(AgribusinessWarehouse::class, 'agribusiness_id');
    }

    public function agribusinessInventory(): HasMany
    {
        return $this->hasMany(AgribusinessInventory::class, 'agribusiness_id');
    }

    public function agribusinessCustomers(): HasMany
    {
        return $this->hasMany(AgribusinessCustomer::class, 'agribusiness_id');
    }

    public function agribusinessEmployees(): HasMany
    {
        return $this->hasMany(AgribusinessEmployee::class, 'agribusiness_id');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class, 'agribusiness_id');
    }

    public function employeeTrainings(): HasMany
    {
        return $this->hasMany(EmployeeTraining::class, 'farmer_id');
    }
}
