<?php

declare(strict_types=1);

namespace LauLamanApps\IzettleApi\Tests\Unit\Client;

use LauLamanApps\IzettleApi\Client\Filter\FilterInterface;
use LauLamanApps\IzettleApi\IzettleClientInterface;
use LauLamanApps\IzettleApi\Tests\Unit\MockeryAssertionTrait;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractClientTest extends TestCase
{
    use MockeryAssertionTrait;

    protected function getIzettleGetMock($url, $data, ?FilterInterface $filter = null): IzettleClientInterface
    {
        $responseMock = Mockery::mock(ResponseInterface::class);

        $izettleClientMock = Mockery::mock(IzettleClientInterface::class);
        if ($filter) {
            $izettleClientMock->shouldReceive('get')->once()->with($url, $filter)->andReturn($responseMock);
        } else {
            $izettleClientMock->shouldReceive('get')->once()->with($url, null)->andReturn($responseMock);
        }
        $izettleClientMock->shouldReceive('getJson')->once()->andReturn(json_encode($data));

        return $izettleClientMock;
    }

    protected function getIzettlePostMock($url, $postData): IzettleClientInterface
    {
        $izettleClientMock = Mockery::mock(IzettleClientInterface::class);
        $izettleClientMock
            ->shouldReceive('post')
            ->once()
            ->with($url, $postData)
            ->andReturn(Mockery::mock(ResponseInterface::class));

        return $izettleClientMock;
    }

    protected function getIzettleDeleteMock($url): IzettleClientInterface
    {
        $izettleClientMock = Mockery::mock(IzettleClientInterface::class);
        $izettleClientMock
            ->shouldReceive('delete')
            ->once()
            ->with($url)
            ->andReturnNull();

        return $izettleClientMock;
    }

    /**
     * @return MockInterface[]
     */
    abstract protected function getDependencyMocks(): array;
}
