<?php

namespace App\Test\Service;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;

class FileUploaderUnitTest extends TestCase
{
    /**
     * @throws FilesystemException
     */
    public function testSuccessUploadBase64File()
    {
        $base64Image = "data:image/jpeg;base64,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";

        // Mock FilesystemOperator for not write image.
        $data       = explode(',', $base64Image);
        $filesystem = $this->getMockBuilder(FilesystemOperator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $filesystem
            ->expects(self::exactly(1))
            ->method('write')
            ->with($this->isType('string'), base64_decode($data[1]));
        $fileUploader = new FileUploader($filesystem);
        $filename     = $fileUploader->uploadBase64File($base64Image);

        $this->assertNotEmpty($filename);
        $this->assertStringContainsString('.jpeg', $filename);
    }
}