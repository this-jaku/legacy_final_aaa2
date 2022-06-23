<?php

namespace App\Tests\Domain\Service;

use App\Application\Repository\SiteCountryMappingRepository;
use App\Application\Repository\SiteRepository;
use App\Domain\Model\Site;
use App\Domain\Model\SiteCountryMapping;
use App\Domain\Service\SiteService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SiteServiceTest extends TestCase
{
    public function testSave()
    {
        // given
        /** @var SiteRepository|MockObject $entityManagerMock */
        $trackerRepositoryMock = $this->createMock(SiteRepository::class);

        /** @var SiteCountryMappingRepository|MockObject $entityManagerMock */
        $trackerMappingRepositoryMock = $this->createMock(SiteCountryMappingRepository::class);

        /** @var EntityManager|MockObject $entityManagerMock */
        $entityManagerMock = $this->createMock(EntityManager::class);

        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(
                function ($key) use ($trackerRepositoryMock, $trackerMappingRepositoryMock) {
                    switch ($key) {
                        case Site::class:
                            return $trackerRepositoryMock;
                        case SiteCountryMapping::class:
                            return $trackerMappingRepositoryMock;
                    }
                }
            );


        $trackerService = new SiteService($entityManagerMock, './tests/Domain/Service');

        $params = [
            'url' => 'test.url',
            'mappings' => ['US', 'FR'],
        ];

        // when
        $tracker = $trackerService->save($params);
        $tracker = json_decode(json_encode($tracker), true);

        // then
        $this->assertSame($params['url'], $tracker['url']);
        $this->assertSame($params['mappings'][0], $tracker['mappings'][0]['countryIso']);
        $this->assertSame($params['mappings'][1], $tracker['mappings'][1]['countryIso']);
    }
}
