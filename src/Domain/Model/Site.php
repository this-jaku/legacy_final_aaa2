<?php

namespace App\Domain\Model;

use App\Domain\Model\Components\GetGuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Application\Repository\SiteRepository")
 * @ORM\Table(name="sites")
 */
class Site implements \JsonSerializable
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
    protected $url;

    /**
     * @var SiteCountryMapping[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="SiteCountryMapping", mappedBy="site")
     */
    protected $mappings;

    public function __construct(string $url)
    {
        $this->guid = Uuid::uuid4();
        $this->url = $url;
        $this->mappings = new ArrayCollection();
    }

    public function addMapping(SiteCountryMapping $siteCountryMapping): void
    {
        $this->mappings->add($siteCountryMapping);
    }

    public function jsonSerialize()
    {
        return [
            'guid' => $this->guid,
            'url' => $this->url,
            'mappings' => $this->mappings->toArray(),
        ];
    }
}
