<?php

namespace Email;

use Email\Models\Folders;

class Storage
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function calcFolderUsedPercent(Folders $folders, $userStorage): string
    {
        $conversion = 1024;
        $storageGB = intval(str_replace('GB', '', $userStorage));
        $userFolders = $folders->list($this->userId);
        $spaceUsed = $this->spaceUsed($userFolders);

        $storageMB = $storageGB * $conversion;
        $percentUsed = ($spaceUsed * 100) / $storageMB;

        return ceil($percentUsed) . '%';
    }

    private function spaceUsed(array $userFolders): int
    {
        $size = 0;

        foreach ($userFolders as $folder) {
            $size += intval(str_replace('MB', '', $folder->size));
        }

        return $size;
    }
}
