<?php

namespace App\Services;

use App\Contracts\LeadviewClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class WebShopService implements LeadviewClient
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::leadview()->timeout(10)->retry(2, 100);
    }

    /**
     * Get the category list from the webshop.
     *
     * @param string|null $category_id
     * @param int|null $attribute_term_id
     * @param string|null $search_key
     * @param int|null $page
     * @param int|null $limit
     * @return array
     */

    public function getCategoryList(
        ?string $category_id = null,
        ?int $attribute_term_id = null,
        ?string $search_key = null,
        ?int $page = null,
        ?int $limit = null
    ): array {
        $url = '/category-child-list';
        $params = array_filter([
            'category_id' => $category_id,
            'attribute_term_id' => $attribute_term_id,
            'search_key' => $search_key,
            'page' => $page,
            'limit' => $limit,
        ]);
        // dd($url);
        $res = $this->client->post($url, $params);

        $res->throw();

        if($res->successful()) {
            return $res->json();
        }
        
    }

    public function getCategoryDetail(string $category_id): array {
        
    }

    /**
     * Get all the shop categories.
     *
     * @param int $only_web_category
     * @param int $only_primary_category
     * @param int $page
     * @param int $limit
     * @return array
     */

    public function getShopCategories(int $only_web_category = 0, int $only_primary_category = 0, int $page = 1, int $limit = 10): array 
    {
        $url = '/category-list';
        $params = array_filter([
            'page' => $page,
            'limit' => $limit,
            'only_web_category' => $only_web_category,
            'only_primary_category' => $only_primary_category
        ]);

        $res = $this->client->post($url, $params);

        $res->throw();

        if($res->successful()) {
            return $res->json();
        }
    }
}