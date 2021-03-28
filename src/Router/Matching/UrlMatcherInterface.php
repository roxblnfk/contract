<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Matching;

use Psr\Http\Message\UriInterface;
use Yiisoft\Http\Method;

interface UrlMatcherInterface
{
    public function matchUrl(string $url, string $method = Method::GET): MatchingResult;
    public function matchUri(UriInterface $uri): ?MatchingResult;
}
