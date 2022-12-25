<?php

namespace Gulacsi\TeamMember\Model;

defined('ABSPATH') or die();

abstract class Model
{
    abstract protected function list(): void;

    abstract protected function insert(array $data): bool;

    abstract protected function update(array $data, bool $has_photo = false): bool;

    abstract protected function delete(int $id): bool;

    abstract protected function verify_nonce(string $action): void;
}
