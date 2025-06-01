<?php

use Illuminate\Support\Facades\File;

define('PAGINATION_COUNT', 10);
define('PAGINATION_COUNT_FRONT', 9);

function deleteImage($filePath)
{
    if (file_exists(public_path($filePath))) {
        return unlink(public_path($filePath));
    }
    return 0;
}

function responseJson($status = 200, $msg = '', $data = null)
{
    return response()->json([
        'status' => $status,
        'msg' => $msg,
        'data' => $data,
    ], $status); // <-- This sets the HTTP status code
}

function generateCustomUUID()
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomPart = substr(str_shuffle($characters), 0, 4);
    $timestamp = substr(time(), -5);
    return $randomPart . $timestamp;
}

function uploadIamge($photo, $folder, $quality = 50)
{
    $destinationPath = 'admin/assets/images/' . $folder . '/';
    $extension = strtolower($photo->getClientOriginalExtension());

    if (!File::exists(public_path($destinationPath))) {
        File::makeDirectory(public_path($destinationPath), 0755, true);
    }

    $fileName = time() . rand(11111, 99999) . '.' . $extension;
    $photo_move = $photo->move(public_path($destinationPath), $fileName);

    if ($photo_move) {
        $compressedImagePath = compressImage($fileName, $destinationPath, $quality);
        return $compressedImagePath;
    }

    throw new \Exception('Failed to upload the image.');
}

function compressImage($fileName, $path, $quality = 50)
{
    $destinationPath = public_path($path);
    $imagePath = $destinationPath . $fileName;

    if (!File::exists($imagePath)) {
        throw new \Exception('Image does not exist at the specified path: ' . $imagePath);
    }

    // Detect real MIME type
    $info = getimagesize($imagePath);
    if (!$info || !isset($info['mime'])) {
        throw new \Exception('Cannot determine image type.');
    }
    $mime = $info['mime'];
    $imageResource = null;

    switch ($mime) {
        case 'image/jpeg':
            $imageResource = imagecreatefromjpeg($imagePath);
            imagejpeg($imageResource, $imagePath, $quality);
            break;
        case 'image/png':
            $imageResource = imagecreatefrompng($imagePath);
            $pngQuality = (int) round(9 - ($quality / 100 * 9));
            imagepng($imageResource, $imagePath, $pngQuality);
            break;
        case 'image/gif':
            $imageResource = imagecreatefromgif($imagePath);
            imagegif($imageResource, $imagePath);
            break;
        case 'image/webp':
            $imageResource = imagecreatefromwebp($imagePath);
            imagewebp($imageResource, $imagePath, $quality);
            break;
        case 'image/bmp':
            $imageResource = imagecreatefrombmp($imagePath);
            imagebmp($imageResource, $imagePath);
            break;
        default:
            throw new \Exception('Unsupported image type. Only JPG, PNG, GIF, WEBP, and BMP are supported.');
    }

    if ($imageResource) {
        imagedestroy($imageResource);
    }
    return $path . $fileName;
}
function generateGoogleMapsLink(float $latitude, float $longitude): string
{
    return "https://www.google.com/maps?q={$latitude},{$longitude}";
}
