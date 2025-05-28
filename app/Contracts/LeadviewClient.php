<?php

namespace App\Contracts;

interface LeadviewClient 
{
    public function getCategoryList(?string $category_id, 
                                    ?int $attribute_term_id, 
                                    ?string $search_key, 
                                    ?int $page, 
                                    ?int $limit
                                ): array;
    public function getCategoryDetail(string $category_id): array;
}
