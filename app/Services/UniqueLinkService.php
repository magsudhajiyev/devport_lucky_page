<?php

namespace App\Services;
use Illuminate\Support\Carbon;

class UniqueLinkService
{
    public function generateToken($userId): string
    {
        return $this->encodeToken(). '&'. $userId;
    }

    public function encodeToken(): string
    {
        return base64_encode(Carbon::now()->toDateTimeString());
    }

    public function decodeToken($token): string
    {
        return base64_decode($token);
    }

    public function checkToken($token): bool
    {
        $tokenParts = explode("&", $token);

        $timeString = $this->decodeToken($tokenParts[0]);

        $dueDate = Carbon::parse($timeString)->addWeek(1);

        if($dueDate->lessThanOrEqualTo(Carbon::now())) {
            return false;
        }

        return true;

    }
}