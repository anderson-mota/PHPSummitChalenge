<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Email\Storage;

class StorageTest extends TestCase
{
    /** @var Storage */
    private $storage;

    public function providerFoldersUsed(): array
    {
        $userFolders1 = [
            (object) ['folderName' => 'Jobs', 'size' => '105MB'],
            (object) ['folderName' => 'Family', 'size' => '265MB'],
            (object) ['folderName' => 'Projects', 'size' => '381MB'],
            (object) ['folderName' => 'Travels', 'size' => '273MB'],
        ];

        $userFolders2 = [
            (object) ['folderName' => 'Drafts', 'size' => '931MB'],
            (object) ['folderName' => 'Projects', 'size' => '365MB'],
            (object) ['folderName' => 'Others', 'size' => '1198MB'],
            (object) ['folderName' => 'Jobs', 'size' => '1090MB'],
        ];

        return [
            [1, $userFolders1, '2GB', '50%'],
            [2, $userFolders2, '5GB', '70%'],
        ];
    }

    /**
     * @dataProvider providerFoldersUsed
     */
    public function testCalcFolderUsedPercent(int $userId, array $userFolders, string $userStorage, string $userPercentageUsedRoundExpected): void
    {
        $folders = $this->getMockBuilder(\Email\Models\Folders::class)
            ->setMethods(['list'])
            ->getMock();

        $folders->expects($this->once())
            ->method('list')
            ->with($userId)
            ->willReturn($userFolders);

        $this->storage = new Storage($userId);
        $percentUsed = $this->storage->calcFolderUsedPercent($folders, $userStorage);

        $this->assertSame($userPercentageUsedRoundExpected, $percentUsed);
    }
}
