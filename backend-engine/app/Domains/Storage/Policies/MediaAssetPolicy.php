<?php

namespace App\Domains\Storage\Policies;

use App\Domains\Storage\Models\MediaAsset;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;
use Marvel\Enums\Permission;

class MediaAssetPolicy
{
    public function view(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->ownsAsset($user, $mediaAsset);
    }

    public function confirm(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->ownsAsset($user, $mediaAsset);
    }

    public function download(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->ownsAsset($user, $mediaAsset);
    }

    public function delete(User $user, MediaAsset $mediaAsset): bool
    {
        return $this->ownsAsset($user, $mediaAsset);
    }

    protected function ownsAsset(User $user, MediaAsset $mediaAsset): bool
    {
        if ($user->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return true;
        }

        return match ($mediaAsset->owner_type) {
            'user' => (int) $mediaAsset->owner_id === (int) $user->id,
            'profile' => (int) $mediaAsset->profile_id === (int) optional($user->profile)->id,
            'shop' => $this->ownsShop($user, (int) $mediaAsset->shop_id),
            default => false,
        };
    }

    protected function ownsShop(User $user, int $shopId): bool
    {
        $shop = Shop::with('staffs')->find($shopId);

        if (!$shop) {
            return false;
        }

        if ($user->hasPermissionTo(Permission::STORE_OWNER) && (int) $shop->owner_id === (int) $user->id) {
            return true;
        }

        return $user->hasPermissionTo(Permission::STAFF) && $shop->staffs->contains('id', $user->id);
    }
}
