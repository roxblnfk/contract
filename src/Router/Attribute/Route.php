<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Attribute;

use Attribute;
use Yiisoft\Http\Method;

#[Attribute(Attribute::TARGET_METHOD)]
final class Route
{
    public function __construct(string $pattern = null, string $name = null, array|string $method = Method::ALL)
    {
    }
}
