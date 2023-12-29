<?php
namespace App\Enums;

enum ReservationStatusEnum: string {
    case Pending = 'Onay Bekleniyor';
    case Success = 'Onaylandı';
    case Rejected = 'Reddedildi';

    case CanselledByUser = 'Kullanıcı iptali';

    public static function getValues(): array
    {
        return [
            'Pending' => 'Onay Bekleniyor',
            'Success' => 'Onaylandı',
            'Rejected' => 'Reddedildi',
            'CanselledByUser' => 'Kullanıcı iptali',
        ];
    }

    public static function getStringValue(): array
    {
        return [
            'Pending',
            'Success',
            'Rejected',
            'CanselledByUser',
        ];
    }

    public static function getKeys(): array
    {
        return [
            'Onay Bekleniyor' => 'Pending',
            'Onaylandı' => 'Success',
            'Reddedildi' => 'Rejected',
            'Kullanıcı iptali' => 'CanselledByUser',
        ];
    }

    public static function getValueByKey(string $key): string
    {
        return match ($key) {
            'Pending' => 'Onay Bekleniyor',
            'Success' => 'Onaylandı',
            'Rejected' => 'Reddedildi',
            'CanselledByUser' => 'Kullanıcı iptali',
            default => throw new \Exception("Invalid key: $key"),
        };
    }
}