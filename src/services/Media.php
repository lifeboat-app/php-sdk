<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Media as File;
use Lifeboat\Resource\ListResource;

/**
 * Class Media
 * @package Lifeboat\Services
 */
class Media extends ApiService {

    /**
     * @param array $data
     * @return File|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function upload(array $data): ?File
    {
        /** @var File|null $create */
        $create = $this->_post('api/media/upload', $data);
        return $create;
    }

    /**
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/media/all', [], 50);
    }
}
