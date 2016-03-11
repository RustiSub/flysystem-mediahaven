<?php

use League\Flysystem\MediaHaven\MediaHavenAdapter;

class MediaHavenAdapterTest extends PHPUnit_Framework_TestCase
{
    public function mediaHavenProvider()
    {
        return [
            [new MediaHavenAdapter(
                'https://integration.mediahaven.com',
                'apikey',
                'apikey'
            )],
        ];
    }

    /**
     * @param MediaHavenAdapter $adapter
     * @dataProvider  mediaHavenProvider
     */
    public function testServiceResponsive(MediaHavenAdapter $adapter)
    {
        $this->assertTrue($adapter->isServiceResponsive());
    }

    /**
     * @param MediaHavenAdapter $adapter
     * @dataProvider  mediaHavenProvider
     */
    public function testServiceError(MediaHavenAdapter $adapter)
    {
        $this->assertEquals(200, $adapter->getServiceStatus());
    }

    /**
     * @param MediaHavenAdapter $adapter
     * @dataProvider  mediaHavenProvider
     */
    public function testListContentsReturnsFilledArray(MediaHavenAdapter $adapter)
    {
        $result = $adapter->listContents();

        $this->assertInternalType('array', $result);
        $this->assertTrue(count($result) > 0);
    }

    /**
     * @param MediaHavenAdapter $adapter
     * @dataProvider  mediaHavenProvider
     */
    public function testReadExistingFile(MediaHavenAdapter $adapter)
    {
        $result = $adapter->listContents();

        $firstResult = $result[0];

        $readResult = $adapter->read($firstResult);

        $this->assertInternalType('array', $readResult);
        $this->assertNotFalse($readResult);
        $this->assertNotEmpty($readResult);
    }
}
