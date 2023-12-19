<?php
namespace App\Enums;

enum RoomTypeEnum: string {
    case Standard = 'Standart';
    case Suite = 'Suit';
    case Family = 'Aile';

    public static function getValues(): array
    {
        return [
            'Standard' => 'Standart',
            'Suite' => 'Suit',
            'Family' => 'Aile',
        ];
    }

    public static function getStringValue(): array
    {
        return [
            'Standard',
            'Suite',
            'Family',
        ];
    }
}