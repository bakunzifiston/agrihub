<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function countries(): JsonResponse
    {
        $countries = Location::countries()->orderBy('name')->get(['id', 'name', 'code']);
        return response()->json($countries);
    }

    public function districts(Request $request): JsonResponse
    {
        $countryId = $request->query('country_id');
        $locations = Location::districts()
            ->when($countryId, fn ($q) => $q->childrenOf((int) $countryId))
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);
        return response()->json($locations);
    }

    public function sectors(Request $request): JsonResponse
    {
        $districtId = $request->query('district_id');
        $locations = Location::sectors()
            ->when($districtId, fn ($q) => $q->childrenOf((int) $districtId))
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);
        return response()->json($locations);
    }

    public function cells(Request $request): JsonResponse
    {
        $sectorId = $request->query('sector_id');
        $locations = Location::cells()
            ->when($sectorId, fn ($q) => $q->childrenOf((int) $sectorId))
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);
        return response()->json($locations);
    }

    public function villages(Request $request): JsonResponse
    {
        $cellId = $request->query('cell_id');
        $locations = Location::villages()
            ->when($cellId, fn ($q) => $q->childrenOf((int) $cellId))
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);
        return response()->json($locations);
    }
}
