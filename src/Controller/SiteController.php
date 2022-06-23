<?php

namespace App\Controller;

use App\Domain\Service\SiteService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SiteController
{
    /** @var SiteService */
    private $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    public function save(Request $request)
    {
        try {
            $content = json_decode($request->getContent(), true);
            $jsonIsValid = json_last_error() === JSON_ERROR_NONE;
            if (!$jsonIsValid) {
                throw new \Exception('Unable to decode data as json.');
            }

            $site = $this->siteService->save($content);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }

        return new JsonResponse($site->getGuid());
    }

    public function get(Request $request): JsonResponse
    {
        $siteId = $request->get('id');
        if (empty($siteId)) {
            return new JsonResponse('Missing id.', 418);
        }

        $site = $this->siteService->get($siteId);
        return new JsonResponse($site);
    }
}
