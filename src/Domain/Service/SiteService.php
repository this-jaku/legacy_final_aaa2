<?php

namespace App\Domain\Service;

use App\Application\Repository\SiteCountryMappingRepository;
use App\Application\Repository\SiteRepository;
use App\Application\ServiceCore;
use App\Domain\Model\Site;
use App\Domain\Model\SiteCountryMapping;
use App\Domain\Utils\CountryIsoValidator;
use Doctrine\ORM\EntityManager;

class SiteService extends ServiceCore
{
    /** @var SiteRepository */
    public $_siteRepository;

    /** @var SiteCountryMappingRepository */
    public $_siteCountryMappingRepository;

    public function __construct(EntityManager $entityManager, string $logDirectory)
    {
        parent::__construct($logDirectory);
        $this->_siteRepository = $entityManager->getRepository(Site::class);
        $this->_siteCountryMappingRepository = $entityManager->getRepository(SiteCountryMapping::class);
    }

    public function save(array $params): Site
    {
        if (empty($params['mappings'])) {
            throw new \Exception('At least one mapping is required.');
        }

        $site = new Site($params['url']);
        $this->_siteRepository->persist($site);

        $this->info("stored Site {$site->getGuid()}");

        for ($i = 0; $i < count($params['mappings']); $i++) {
            $country_iso = $params['mappings'][$i];

            if (!CountryIsoValidator::validate($country_iso)) {
                throw new \Exception("Invalid countryIso: $country_iso");
            }

            $siteCountryMapping = new SiteCountryMapping($country_iso, $site);
            $this->_siteCountryMappingRepository->persist($siteCountryMapping);
            $this->info("stored SiteCountryMapping {$siteCountryMapping->getGuid()}");
            $site->addMapping($siteCountryMapping);
        }

        $this->_siteRepository->flush();

        return $site;
    }

//    public function delete(string $siteId): bool
//    {
//        /** @var Site $site */
//        $site = $this->_siteRepository->find($siteId);
//
//        if (!$site) {
//            return false;
//        }
//
//        foreach ($site->getMappings() as $mapping) {
//            $this->_siteCountryMappingRepository->delete($mapping);
//        }
//
//        $this->_siteRepository->delete($site);
//
//        $this->_siteRepository->flush();
//
//        return true;
//    }

    public function info(string $logMessage): void
    {
        $logMessage = $this->getTransactionId() . $logMessage . PHP_EOL;
        file_put_contents($this->logDirectory . '/site.log', $logMessage, FILE_APPEND);
    }

    /**
     * @throws \Exception
     */
    public function error(string $logMessage): void
    {
        throw new \Exception('Method not handled in this class.');
    }

    public function get(string $siteId): ?Site
    {
        return $this->_siteRepository->find($siteId);
    }
}
