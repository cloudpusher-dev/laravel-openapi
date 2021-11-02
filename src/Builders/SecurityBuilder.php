<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;
use Illuminate\Support\Arr;

class SecurityBuilder
{
    /**
     * @param  array  $config
     * @return SecurityRequirement[]
     */
    public function build(array $config): array
    {
        return collect($config)
            ->map(static function (array $security) {
                $retval = SecurityRequirement::create()
                    ->securityScheme(Arr::get($security, 'securityScheme'));
                $scopes = Arr::get($security, 'scopes');
                return $scopes ? $retval->scopes(...$scopes) : $retval;
            })
            ->toArray();
    }
}
