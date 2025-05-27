<?php
use Illuminate\Support\Facades\File;
	define('PAGINATION_COUNT', 10);
	define('PAGINATION_COUNT_FRONT', 9);

	// function uploadIamge($photo, $folder){
	// 	$destinationPath = 'admin/assets/images/' . $folder . '/'; // upload path
	// 	$extension = $photo->getClientOriginalExtension(); // getting image extension
    //     $fileName = time() . rand(11111, 99999) . '.' . $extension;
	// 	$photo_move = $photo->move(public_path($destinationPath), $fileName);
	// 	return $destinationPath . $fileName;
	// }

	// function uploadIamges($photos, $folder){
	// 	$images = [];
	// 	foreach ($photos as $photo){
	// 		$destinationPath = 'admin/assets/images/' . $folder . '/'; // upload path
	// 		$extension = $photo->getClientOriginalExtension(); // getting image extension
	// 		$fileName = time() . rand(11111, 99999) . '.' . $extension;
	// 		$photo_move = $photo->move(public_path($destinationPath), $fileName);
	// 		$images[] = $destinationPath . $fileName;
	// 	}
	// 	$files = implode(",", $images);
	// 	return $files;
	// }

	function deleteImage($filePath) {
		if (file_exists(public_path($filePath))) {
			return unlink(public_path($filePath));
		}
		return 0;
	}

	function responseJson($status, $msg, $data = null)
	{
		$response = [
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		];
		return response()->json($response);
	}

    // function generateCustomUUID()
 	// {
 	// 	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
 	// 	$prefix = substr(str_shuffle($characters), 0, 4);
 	// 	$suffix = substr(str_shuffle($characters), 0, 2);
 	// 	return $prefix . time() . $suffix;
 	// }
 
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

        // نقل الصورة أولاً ثم ضغطها
        $fileName = time() . rand(11111, 99999) . '.' . $extension;
        $photo_move = $photo->move(public_path($destinationPath), $fileName);

        // التحقق من أنه تم نقل الصورة بنجاح
        if ($photo_move) {
            // الآن قم بضغط الصورة بعد نقلها
            $compressedImagePath = compressImage($fileName, $destinationPath, $quality);
            return $compressedImagePath;
        }

        throw new \Exception('Failed to upload the image.');
    }

    function uploadIamges($photos, $folder, $quality = 50)
    {
        $images = [];
        foreach ($photos as $photo) {
            $destinationPath = 'admin/assets/images/' . $folder . '/';
            $extension = strtolower($photo->getClientOriginalExtension());

            // تحديد اسم الملف الفريد باستخدام الوقت والرقم العشوائي
            $fileName = time() . rand(11111, 99999) . '.' . $extension;

            // رفع الصورة
            $photo_move = $photo->move(public_path($destinationPath), $fileName);

            // التحقق من أنه تم نقل الصورة بنجاح
            if ($photo_move) {
                // ضغط الصورة بعد رفعها
                $compressedImagePath = compressImage($fileName, $destinationPath, $quality);
                $images[] = $compressedImagePath;
            } else {
                throw new \Exception('Failed to upload the image: ' . $fileName);
            }
        }

        // دمج المسارات المضغوطة للفصل بينهم بفواصل
        $files = implode(",", $images);

        return $files;
    }

    function compressImage($fileName, $path, $quality = 10)
    {
        $destinationPath = public_path($path);
        $imagePath = $destinationPath . $fileName;

        // تحقق من وجود الصورة
        if (!File::exists($imagePath)) {
            throw new \Exception('Image does not exist at the specified path: ' . $imagePath);
        }

        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        $imageResource = null;

        if ($extension === 'jpeg' || $extension === 'jpg') {
            $imageResource = imagecreatefromjpeg($imagePath);
        } elseif ($extension === 'png') {
            $imageResource = imagecreatefrompng($imagePath);
        } elseif ($extension === 'gif') {
            $imageResource = imagecreatefromgif($imagePath);
        } elseif ($extension === 'webp') {
            $imageResource = imagecreatefromwebp($imagePath);
        } elseif ($extension === 'bmp') {
            $imageResource = imagecreatefrombmp($imagePath);
        } else {
            throw new \Exception('Unsupported image type. Only JPG, PNG, GIF, WEBP, and BMP are supported.');
        }

        $outputPath = $destinationPath . $fileName;

        if ($extension === 'jpeg' || $extension === 'jpg') {
            imagejpeg($imageResource, $outputPath, $quality);
        } elseif ($extension === 'png') {
            $pngQuality = (int) round(9 - ($quality / 100 * 9));
            imagepng($imageResource, $outputPath, $pngQuality);
        } elseif ($extension === 'gif') {
            imagegif($imageResource, $outputPath);
        } elseif ($extension === 'webp') {
            imagewebp($imageResource, $outputPath, $quality);
        } elseif ($extension === 'bmp') {
            imagebmp($imageResource, $outputPath);
        } else {
            throw new \Exception('Unsupported image type. Only JPG, PNG, GIF, WEBP, and BMP are supported.');
        }

        imagedestroy($imageResource);

        return $path . $fileName;
    }

    function generateGoogleMapsLink(float $latitude, float $longitude): string
    {
        return "https://www.google.com/maps?q={$latitude},{$longitude}";
    }

    // "autoload": {
    //     "psr-4": {
    //         "App\\": "app/",
    //         "Database\\Factories\\": "database/factories/",
    //         "Database\\Seeders\\": "database/seeders/"
    //     },
    //     "files" : [
    //         "app/helper/functions.php"
    //     ]
    // },
