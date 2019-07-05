<?php

namespace Email\Models;

class Folders
{
    public function list(int $userId): array
    {
        return [];
    }

    public function getStorageSpace(): string
    {
        return '';
    }
}
