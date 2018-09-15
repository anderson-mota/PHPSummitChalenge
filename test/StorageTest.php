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
            (object) ['folderName' => 'Jobs', 'size' => '23MB'],
            (object) ['folderName' => 'Family', 'size' => '78MB'],
            (object) ['folderName' => 'Projects', 'size' => '130MB'],
            (object) ['folderName' => 'Travels', 'size' => '119MB'],
        ];

        $userFolders2 = [
            (object) ['folderName' => 'Drafts', 'size' => '188MB'],
            (object) ['folderName' => 'Family', 'size' => '205MB'],
            (object) ['folderName' => 'Projects', 'size' => '290MB'],
            (object) ['folderName' => 'Others', 'size' => '320MB'],
            (object) ['folderName' => 'Jobs', 'size' => '1090MB'],
            (object) ['folderName' => 'Travels', 'size' => '1644MB'],
        ];

        return [
            [1, $userFolders1, '2GB', '18%'],
            [2, $userFolders2, '5GB', '73%'],
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
