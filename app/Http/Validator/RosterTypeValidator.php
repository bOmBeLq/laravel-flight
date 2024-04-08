<?php

namespace App\Http\Validator;

use App\Service\RosterImport\Parser\RosterParserProvider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RosterTypeValidator implements ValidationRule
{
    public function __construct(private readonly RosterParserProvider $rosterParserProvider)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->rosterParserProvider->hasParserForType($value)) {
            $fail('Roster type ' . $value . ' is not supported');
        }
    }
}
