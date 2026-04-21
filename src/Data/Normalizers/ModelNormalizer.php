<?php

namespace OmniGuard\Data\Normalizers;

use Illuminate\Database\Eloquent\Model;
use OmniGuard\Data\Normalizers\Normalized\Normalized;
use OmniGuard\Data\Normalizers\Normalized\NormalizedModel;

class ModelNormalizer implements Normalizer
{
    public function normalize(mixed $value): null|array|Normalized
    {
        if (! $value instanceof Model) {
            return null;
        }

        return new NormalizedModel($value);
    }
}
