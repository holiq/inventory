<?php

namespace App\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use NumberFormatter;
use Stringable;

final class FormatRupiah implements JsonSerializable, Stringable
{
    public function __construct(public readonly int $amount) {}

    public static function fromInt(int $amount): self
    {
        return new self($amount);
    }

    public static function fromString(string $value): self
    {
        // "Rp 1.234.567", "1.234.567", "1234567", "-1.234"
        $digits = preg_replace('/[^\d-]/', '', $value);
        if ($digits === '' || $digits === '-') {
            throw new InvalidArgumentException('Cannot create Rupiah from empty or dash value. Use null instead.');
        } else {
            $int = (int) $digits;
        }

        return new self($int);
    }

    public static function from(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }
        if (is_int($value)) {
            return self::fromInt($value);
        }
        if (is_string($value)) {
            return self::fromString($value);
        }

        throw new InvalidArgumentException('Unsupported value for Rupiah.');
    }

    public function toInt(): int
    {
        return $this->amount;
    }

    public function format(bool $withSymbol = true): string
    {
        if (class_exists(NumberFormatter::class)) {
            $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
            $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            $formatted = $fmt->formatCurrency($this->amount, 'IDR');

            if (! $withSymbol) {
                $dec = new NumberFormatter('id_ID', NumberFormatter::DECIMAL);
                $dec->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);

                return $dec->format($this->amount);
            }

            return $formatted;
        }

        $formatted = number_format($this->amount, 0, ',', '.');

        return $withSymbol ? 'Rp '.$formatted : $formatted;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function jsonSerialize(): mixed
    {
        return $this->format();
    }
}
