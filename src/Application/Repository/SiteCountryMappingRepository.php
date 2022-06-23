<?php

namespace App\Application\Repository;

use App\Domain\Model\SiteCountryMapping;
use Doctrine\ORM\EntityRepository;

class SiteCountryMappingRepository extends EntityRepository
{
    public function persist(SiteCountryMapping $siteCountryMapping): void
    {
        $this->getEntityManager()->persist($siteCountryMapping);
    }
}
