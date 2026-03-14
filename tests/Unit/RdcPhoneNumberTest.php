<?php

namespace Tests\Unit;

use App\Rules\RdcPhoneNumber;
use App\Services\RdcPhoneFormatter;
use Closure;
use PHPUnit\Framework\TestCase;

class RdcPhoneNumberTest extends TestCase
{
    /**
     * @dataProvider validPhoneProvider
     */
    public function test_it_accepts_valid_rdc_numbers(string $phone, string $expectedNormalized): void
    {
        $rule = new RdcPhoneNumber();
        $failed = false;

        $rule->validate('customer_number', $phone, function () use (&$failed): void {
            $failed = true;
        });

        $this->assertFalse($failed);
        $this->assertSame($expectedNormalized, RdcPhoneFormatter::normalizeForFreshPay($phone));
    }

    /**
     * @dataProvider invalidPhoneProvider
     */
    public function test_it_rejects_invalid_rdc_numbers(string $phone): void
    {
        $rule = new RdcPhoneNumber();
        $failed = false;

        $rule->validate('customer_number', $phone, function () use (&$failed): void {
            $failed = true;
        });

        $this->assertTrue($failed);
    }

    public static function validPhoneProvider(): array
    {
        return [
            'orange local' => ['0899730021', '899730021'],
            'airtel local' => ['0999730021', '999730021'],
            'airtel compact' => ['999730021', '999730021'],
            'africell local' => ['0901234567', '901234567'],
            'international format' => ['+243 99 973 0021', '999730021'],
        ];
    }

    public static function invalidPhoneProvider(): array
    {
        return [
            'too short' => ['09912345'],
            'wrong prefix' => ['0799730021'],
            'wrong country code length' => ['2439997300219'],
        ];
    }
}
