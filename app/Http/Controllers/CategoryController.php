<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\LeadviewClient;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct(protected LeadviewClient $leadviewClient) {}

    public function index(Request $request)
    {
        $data = $this->leadviewClient->getShopCategories(only_web_category: 1);

        return response()->json(['data'=> $data]);
    }

}
