<?php

namespace App\Domain\Model;

use App\Domain\Model\Components\GetGuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Application\Repository\SiteCountryMappingRepository")
 * @ORM\Table(name="site_mappings")
 */
class SiteCountryMapping implements \JsonSerializable
{
    use GetGuidTrait;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    protected $guid;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $countryIso;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="mappings")
     * @ORM\JoinColumn(name="site", referencedColumnName="guid")
     */
    protected $site;

    public function __construct(string $countryIso, Site $site)
    {
        $this->guid = Uuid::uuid4();
        $this->countryIso = $countryIso;
        $this->site = $site;
    }

    public function jsonSerialize()
    {
        return [
            'guid' => $this->guid,
            'countryIso' => $this->countryIso
        ];
    }
}
