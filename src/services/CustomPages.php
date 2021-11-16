<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\CustomPage;
use Lifeboat\Resource\ListResource;

/**
 * Class CustomPages
 * @package Lifeboat\Services
 */
class CustomPages extends Pages {

    const SORT_TITLE_ASC    = 'title_az';
    const SORT_TITLE_DESC   = 'title_za';
    const SORT_CREATED_ASC  = 'created_az';
    const SORT_CREATED_DESC = 'created_za';
    const SORT_EDITED_ASC   = 'edited_az';
    const SORT_EDITED_DESC  = 'edited_za';
    const SORT_DEFAULT      = '';

    const SORT_OPTIONS = [
        self::SORT_TITLE_ASC, self::SORT_TITLE_DESC, self::SORT_CREATED_ASC,
        self::SORT_CREATED_DESC, self::SORT_EDITED_ASC, self::SORT_EDITED_DESC,
        self::SORT_DEFAULT
    ];

    /**
     * @param int $id
     * @return CustomPage|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?CustomPage
    {
        /** @var CustomPage|null $fetch */
        $fetch = $this->_get('api/pages/page' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return CustomPage|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?CustomPage
    {
        /** @var CustomPage|null $create */
        $create = $this->_post('api/pages/page/', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return CustomPage|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?CustomPage
    {
        /** @var CustomPage|null $post */
        $post = $this->_post('api/pages/page/' . $id, $data);
        return $post;
    }

    /**
     * @param string $search
     * @param string $sort
     * @return ListResource
     */
    public function all(string $search = '', string $sort = self::SORT_DEFAULT): ListResource
    {
        if (!in_array($sort, self::SORT_OPTIONS)) {
            throw new InvalidArgumentException("Customers::all() expects parameter 2 to be a valid sort option");
        }

        return new ListResource($this->getClient(), 'api/pages/page/all', ['search' => $search, 'sort' => $sort], 20);
    }
}
