<?php

namespace Vyuldashev\LaravelOpenApi;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Support\Arr;
use Vyuldashev\LaravelOpenApi\Builders\ComponentsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\InfoBuilder;
use Vyuldashev\LaravelOpenApi\Builders\PathsBuilder;
use Vyuldashev\LaravelOpenApi\Builders\SecurityBuilder;
use Vyuldashev\LaravelOpenApi\Builders\ServersBuilder;
use Vyuldashev\LaravelOpenApi\Builders\TagsBuilder;

class Generator
{
    public $version = OpenApi::OPENAPI_3_0_2;

    public const COLLECTION_DEFAULT = 'default';

    protected array $config;
    protected InfoBuilder $infoBuilder;
    protected ServersBuilder $serversBuilder;
    protected TagsBuilder $tagsBuilder;
    protected PathsBuilder $pathsBuilder;
    protected ComponentsBuilder $componentsBuilder;
    protected SecurityBuilder $securityBuilder;

    public function __construct(
        array $config,
        InfoBuilder $infoBuilder,
        ServersBuilder $serversBuilder,
        TagsBuilder $tagsBuilder,
        PathsBuilder $pathsBuilder,
        ComponentsBuilder $componentsBuilder,
        SecurityBuilder $securityBuilder
    ) {
        $this->config = $config;
        $this->infoBuilder = $infoBuilder;
        $this->serversBuilder = $serversBuilder;
        $this->tagsBuilder = $tagsBuilder;
        $this->pathsBuilder = $pathsBuilder;
        $this->componentsBuilder = $componentsBuilder;
        $this->securityBuilder = $securityBuilder;
    }

    public function generate(string $collection = self::COLLECTION_DEFAULT): OpenApi
    {
        $middlewares = Arr::get($this->config, 'collections.'.$collection.'.middlewares');

        $info = $this->infoBuilder->build(Arr::get($this->config, 'collections.'.$collection.'.info', []));
        $servers = $this->serversBuilder->build(Arr::get($this->config, 'collections.'.$collection.'.servers', []));
        $tags = $this->tagsBuilder->build(Arr::get($this->config, 'collections.'.$collection.'.tags', []));
        $paths = $this->pathsBuilder->build($collection, Arr::get($middlewares, 'paths', []));
        $components = $this->componentsBuilder->build($collection, Arr::get($middlewares, 'components', []));
        $security = $this->securityBuilder->build(Arr::get($this->config, 'collections.'.$collection.'.security', []));

        $openApi = OpenApi::create()
            ->openapi(OpenApi::OPENAPI_3_0_2)
            ->info($info)
            ->servers(...$servers)
            ->paths(...$paths)
            ->components($components)
            ->security(...$security)
            ->tags(...$tags);

        return $openApi;
    }
}
