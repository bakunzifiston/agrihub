<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FarmProfileInputsController extends Controller
{
    public function availableInputs(Request $request): JsonResponse
    {
        $farmProfileId = $request->query('farm_profile_id');

        if (! $farmProfileId) {
            return response()->json([]);
        }

        $farmProfile = FarmProfile::where('id', $farmProfileId)
            ->where('farmer_id', auth()->id())
            ->first();

        if (! $farmProfile || empty($farmProfile->inputs_availability)) {
            return response()->json([]);
        }

        $agriculturalInputs = config('agricultural-inputs');
        $availableInputs = [];

        foreach ($farmProfile->inputs_availability as $inputKey) {
            $parts = explode(':', $inputKey);
            if (count($parts) !== 2) {
                continue;
            }

            [$categoryKey, $itemKey] = $parts;

            if (! isset($agriculturalInputs[$categoryKey])) {
                continue;
            }

            $category = $agriculturalInputs[$categoryKey];

            if (! isset($category['items'][$itemKey])) {
                continue;
            }

            $availableInputs[] = [
                'category_key' => $categoryKey,
                'category_label' => $category['label'],
                'item_key' => $itemKey,
                'item_label' => $category['items'][$itemKey],
                'value' => $inputKey,
            ];
        }

        usort($availableInputs, fn ($a, $b) => strcmp($a['category_label'] . $a['item_label'], $b['category_label'] . $b['item_label']));

        return response()->json($availableInputs);
    }

    public function allCategories(): JsonResponse
    {
        $agriculturalInputs = config('agricultural-inputs');
        $categories = [];

        foreach ($agriculturalInputs as $key => $category) {
            $categories[] = [
                'key' => $key,
                'label' => $category['label'],
            ];
        }

        return response()->json($categories);
    }
}
