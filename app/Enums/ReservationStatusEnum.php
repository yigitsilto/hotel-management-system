<?php
namespace App\Enums;

enum ReservationStatusEnum: string {
    case Pending = 'Onay Bekleniyor';
    case Success = 'Onaylandı';
    case Rejected = 'Reddedildi';


    public static function getValues(): array
    {
        return [
            'Pending' => 'Onay Bekleniyor',
            'Success' => 'Onaylandı',
            'Rejected' => 'Reddedildi',
        ];
    }

    public static function getStringValue(): array
    {
        return [
            'Pending',
            'Success',
            'Rejected',
        ];
    }

    public static function getKeys(): array
    {
        return [
            'Onay Bekleniyor' => 'Pending',
            'Onaylandı' => 'Success',
            'Reddedildi' => 'Rejected',
        ];
    }

    public static function getValueByKey(string $key): string
    {
        return match ($key) {
            'Pending' => 'Onay Bekleniyor',
            'Success' => 'Onaylandı',
            'Rejected' => 'Reddedildi',
            default => throw new \Exception("Invalid key: $key"),
        };
    }
}