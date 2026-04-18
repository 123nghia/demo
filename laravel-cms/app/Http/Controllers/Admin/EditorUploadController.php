<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:6144',
        ]);

        $image = $request->file('image');
        if (!$image || !$image->isValid()) {
            return response()->json([
                'message' => 'Không thể tải ảnh lên. Vui lòng thử lại.',
            ], 422);
        }

        $uploadDirectory = public_path('uploads/editor');
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $image->getClientOriginalExtension());
        $fileName = 'editor-' . date('YmdHis') . '-' . Str::random(10) . ($extension ? '.' . $extension : '');
        $destinationPath = $uploadDirectory . '/' . $fileName;

        ImageProcessor::processAndSave($image, $destinationPath);

        return response()->json([
            'location' => '/uploads/editor/' . $fileName,
        ]);
    }
}
