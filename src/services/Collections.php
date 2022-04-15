<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Collection;
use Lifeboat\Resource\ListResource;

/**
 * Class Collections
 * @package Lifeboat\Services
 */
class Collections extends ApiService {

    /**
     * @param int $id
     * @return Collection|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Collection
    {
        /** @var Collection|null $fetch */
        $fetch = $this->_get('api/collections/collection' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return Collection|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Collection
    {
        /** @var Collection|null $create */
        $create = $this->_post('api/collections/collection', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Collection|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Collection
    {
        /** @var Collection|null $post */
        $post = $this->_post('api/collections/collection/' . $id, $data);
        return $post;
    }

    /**
     * @param int $id
     * @return bool
     * @throws ApiException
     * @throws OAuthException
     */
    public function delete(int $id): bool
    {
        return $this->_delete('api/collections/collection/' . $id);
    }

    /**
     * @param bool $detailed
     * @return ListResource
     */
    public function all(bool $detailed = false): ListResource {
        $data = ($detailed) ? ['data' => 'detailed'] : [];
        return new ListResource($this->getClient(), 'api/collections/all', $data, 20);
    }
}
