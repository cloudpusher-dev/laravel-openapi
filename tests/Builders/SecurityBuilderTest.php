<?php

declare(strict_types=1);

namespace Vyuldashev\LaravelOpenApi\Tests\Builders;

use Vyuldashev\LaravelOpenApi\Builders\SecurityBuilder;
use Vyuldashev\LaravelOpenApi\Tests\TestCase;

class SecurityBuilderTest extends TestCase
{
    /**
     * @dataProvider providerBuild
     *
     * @param  array  $config
     * @param  array  $expected
     * @return void
     */
    public function testBuild(array $config, array $expected): void
    {
        $SUT = new SecurityBuilder();
        $security = $SUT->build($config);
        $this->assertSameAssociativeArray($expected[0], $security[0]->toArray());
    }

    public function providerBuild(): array
    {
        return [
            'If the scopes field does not exist, it is possible to output the correct json.' => [
                [[
                    'securityScheme' => 'BearerToken',
                ]],
                [[
                    'BearerToken' => [],
                ]],
            ],
            'If the scopes field is present, it can output the correct json.' => [
                [[
                    'securityScheme' => 'OAuth2',
                    'scopes' => [ 'read', 'write' ],
                ]],
                [[
                    'OAuth2' => [ 'read', 'write' ],
                ]],
            ],
        ];
    }

    /**
     * Assert equality as an associative array.
     *
     * @param  array  $expected
     * @param  array  $actual
     * @return void
     */
    protected function assertSameAssociativeArray(array $expected, array $actual): void
    {
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertSameAssociativeArray($value, $actual[$key]);
                unset($actual[$key]);
                continue;
            }
            self::assertSame($value, $actual[$key]);
            unset($actual[$key]);
        }
        self::assertCount(0, $actual, sprintf('[%s] does not matched keys.', join(', ', array_keys($actual))));
    }
}
