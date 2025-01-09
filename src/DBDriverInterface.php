<?php

declare(strict_types=1);

namespace Hyperf\Oauth2;

interface DBDriverInterface
{

    public function get(string $key): mixed;

    public function save(array $data):mixed;

    public function update(array $where,array $data):mixed;
}
