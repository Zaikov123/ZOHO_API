<?php

namespace App\Http\Controllers;

use App\Services\ZohoService;
use Illuminate\Http\Request;

class ZohoController extends Controller
{
    protected $zohoService;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
    }

    public function getDeals()
    {
        try {
            $deals = $this->zohoService->getDeals();
            logger()->log('info',$deals);
            return $deals;
        } catch (\Exception $e) {
            logger()->error('Error fetching deals:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch deals.'], 500);
        }
    }
    public function createDeal(Request $request)
    {
        try{
            $data = $request->validate([
                'deal_name' => 'required|string',
                'deal_stage' => 'required|string',
            ]);

            $result = $this->zohoService->createDeal($data);
            return response()->json($result);
        }catch (\Exception $e) {
            print ('Error creating deal: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create deal.'], 500);
        }
    }

    public function createAccount(Request $request)
    {
        $data = $request->validate([
            'account_name' => 'required|string',
            'account_website' => 'required|string',
            'account_phone' => 'required|string',
        ]);

        $result = $this->zohoService->createAccount($data);
        return response()->json($result);
    }
}
