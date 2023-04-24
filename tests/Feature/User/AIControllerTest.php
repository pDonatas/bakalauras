<?php

namespace Tests\Feature\User;

use App\Http\Controllers\AIController;
use App\Http\Services\AIService;
use App\Services\AI\AILanguageService;
use Closure;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AIControllerTest extends TestCase
{
    private AIController $controller;
    private AILanguageService $aiLanguageServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aiLanguageServiceMock = $this->createMock(AILanguageService::class);
        $aiServiceMock = $this->createMock(AIService::class);

        $this->controller = new AIController(
            $this->aiLanguageServiceMock,
            $aiServiceMock
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testGenerateImage(): void
    {
        $query = ['text' => 'Test query'];

        $requestMock = $this->mock(Request::class);
        $requestMock->shouldReceive('all')->andReturn($query);

        assert($this->aiLanguageServiceMock instanceof MockObject);

        $this->aiLanguageServiceMock
            ->expects($this->once())
            ->method('buildQuery')
            ->with($query)
            ->willReturn('built query');

        Cache::shouldReceive('get')
            ->once()
            ->with('built query', Mockery::type('Closure'))
            ->andReturn(['cached image data']);

        $response = $this->controller->generateImage($requestMock);

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame(['data' => ['cached image data']], $response->getData(true));
    }
}
