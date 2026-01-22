<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AddressType;
use App\Enums\Role;
use App\Models\Scopes\AddressScope;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

#[ScopedBy([AddressScope::class])]
class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'address_type',
        'name',
        'surname',
        'bussiness_name',
        'email',
        'financial_number',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    protected $casts = [
        'address_type' => AddressType::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Address $address): void {
            /** @var ?User $user */
            $user = Auth::getUser();

            if ($user === null || $user->role === Role::Admin) {
                return;
            }

            /** @var int<0, max> $userId */
            $userId = $user->id;

            $address->user_id = $userId;
        });
    }
}
