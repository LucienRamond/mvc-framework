<?php declare(strict_types = 1);

namespace Lram\Foundation;

use Valitron\Validator as ValitronValidator;

class Validator
{
    public static function get(array $data): ValitronValidator
    {
        $validator = new ValitronValidator(
            data: $data,
            lang: 'fr'
        );
        $validator->labels(require ROOT.'/resources/lan/validation.php');
        static::addCustomRules($validator);
        return $validator;
    }

    protected static function addCustomRules(ValitronValidator $validator): void
    {
        // Custom rules here
    }
}