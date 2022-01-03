<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\TaxCode;
use Lifeboat\Resource\ListResource;

/**
 * Class TaxCodes
 * @package Lifeboat\Services
 */
class TaxCodes extends ApiService {

    /**
     * @param int $id
     * @return TaxCode|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?TaxCode
    {
        /** @var TaxCode|null $fetch */
        $fetch = $this->_get('api/tax_code/' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return TaxCode|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?TaxCode
    {
        /** @var TaxCode|null $create */
        $create = $this->_post('api/tax_code/', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return TaxCode|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?TaxCode
    {
        /** @var TaxCode|null $post */
        $post = $this->_post('api/tax_code/' . $id, $data);
        return $post;
    }

    /**
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/tax_code/all', [], 20);
    }
}
