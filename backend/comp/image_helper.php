<?php
/**
 * backend/comp/image_helper.php
 * Shared helper: image check + resize + compress (achievement, gallery, teacher)
 * Output hamesha JPEG hota hai.
 */

/**
 * Allowed image types (sirf wahi jo GD sach mein khol sakta hai)
 */
function getAllowedImageMimes() {
    $mimes = [
        'image/jpeg', 'image/jpg', 'image/pjpeg',
        'image/png',
        'image/gif',
    ];
    if (function_exists('imagecreatefromwebp')) {
        $mimes[] = 'image/webp';
    }
    if (function_exists('imagecreatefrombmp')) {
        $mimes[] = 'image/bmp';
        $mimes[] = 'image/x-ms-bmp';
    }
    if (function_exists('imagecreatefromavif')) {
        $mimes[] = 'image/avif';
    }
    return $mimes;
}

/**
 * File ka asli type content se check karta hai (extension se nahi).
 * Allowed ho to mime return karta hai, warna false.
 */
function getImageMime($tmpPath) {
    $info = @getimagesize($tmpPath);
    if ($info === false) {
        return false;
    }
    return in_array($info['mime'], getAllowedImageMimes(), true) ? $info['mime'] : false;
}

/**
 * Compress and save an uploaded image (hamesha JPEG mein).
 *
 * @param string $sourceTmpPath  $_FILES['image']['tmp_name']
 * @param string $destPath       Final image ka full path
 * @param int    $maxWidth       Max width (height auto)
 * @param int    $quality        JPEG quality 0-100
 * @return bool                  true success, false failure
 */
function compressAndSaveImage($sourceTmpPath, $destPath, $maxWidth = 1600, $quality = 80) {

    $mime = getImageMime($sourceTmpPath);
    if ($mime === false) {
        return false;
    }

    // Bahut bade pixels (RAM crash se bachne ke liye) reject karo
    $info = @getimagesize($sourceTmpPath);
    if (!$info || ($info[0] * $info[1]) > 40000000) {
        return false;
    }

    // Step 1: Image memory mein load karo
    switch ($mime) {
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/pjpeg':
            $src = @imagecreatefromjpeg($sourceTmpPath);
            break;
        case 'image/png':
            $src = @imagecreatefrompng($sourceTmpPath);
            break;
        case 'image/gif':
            $src = @imagecreatefromgif($sourceTmpPath);
            break;
        case 'image/webp':
            $src = @imagecreatefromwebp($sourceTmpPath);
            break;
        case 'image/bmp':
        case 'image/x-ms-bmp':
            $src = @imagecreatefrombmp($sourceTmpPath);
            break;
        case 'image/avif':
            $src = @imagecreatefromavif($sourceTmpPath);
            break;
        default:
            return false;
    }

    if (!$src) {
        return false;
    }

    // Step 2: JPEG ki EXIF rotation sahi karo (phone ki photo)
    if (in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg'], true)
        && function_exists('exif_read_data')) {
        $exif = @exif_read_data($sourceTmpPath);
        $orientation = $exif['Orientation'] ?? 1;
        $angle = 0;
        if ($orientation == 3) $angle = 180;
        elseif ($orientation == 6) $angle = -90;
        elseif ($orientation == 8) $angle = 90;
        if ($angle !== 0) {
            $rotated = imagerotate($src, $angle, 0);
            if ($rotated) {
                imagedestroy($src);
                $src = $rotated;
            }
        }
    }

    // Step 3: Nayi size nikalo
    $origW = imagesx($src);
    $origH = imagesy($src);
    $newW  = $origW;
    $newH  = $origH;
    if ($origW > $maxWidth) {
        $newW = $maxWidth;
        $newH = max(1, intval($origH * ($maxWidth / $origW)));
    }

    // Step 4: Safed background par draw karo (transparent hissa kala na ho)
    $dst = imagecreatetruecolor($newW, $newH);
    if (!$dst) {
        imagedestroy($src);
        return false;
    }
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
    imagedestroy($src);

    // Step 5: JPEG save karo
    $saved = imagejpeg($dst, $destPath, $quality);
    imagedestroy($dst);

    return $saved;
}
?>