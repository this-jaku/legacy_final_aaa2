<?php

namespace App\Application\Repository;

use App\Domain\Model\Site;
use Doctrine\ORM\EntityRepository;

class SiteRepository extends EntityRepository
{
    public function persist(Site $site): void
    {
        $this->getEntityManager()->persist($site);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
