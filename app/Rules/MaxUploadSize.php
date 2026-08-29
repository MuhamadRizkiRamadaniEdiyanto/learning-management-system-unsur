<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class MaxUploadSize implements ValidationRule
{
    public function __construct(protected int $maxKb = 10240)
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $sizeInKb = $this->getSizeInKilobytes($value);

        if ($sizeInKb > $this->maxKb) {
            $fail(sprintf('Ukuran file tidak boleh lebih dari %s KB.', $this->maxKb));
        }
    }

    protected function getSizeInKilobytes(mixed $value): int|float
    {
        if ($value instanceof UploadedFile) {
            return $value->getSize() / 1024;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return 0;
    }
}
