<?php
/**
 * backend/comp/image_helper.php
 * Shared helper — resize + compress uploaded images before saving.
 * Include this file wherever an image is uploaded (teacher, gallery, achievement).
 */

/**
 * Compress and save an uploaded image.
 *
 * @param string $sourceTmpPath  $_FILES['image']['tmp_name']
 * @param string $destPath       Full path where the final image should be saved
 * @param int    $maxWidth       Maximum width in pixels (height auto-scales)
 * @param int    $quality        JPEG quality 0-100 (75 is a good balance)
 * @return bool                  true on success, false on failure
 */
function compressAndSaveImage($sourceTmpPath, $destPath, $maxWidth = 900, $quality = 75) {

    $info = getimagesize($sourceTmpPath);
    if ($info === false) {
        return false;
    }

    $mime = $info['mime'];

    // Step 1: Load the image into memory based on its type
    switch ($mime) {
        case 'image/jpeg':
            $srcImage = imagecreatefromjpeg($sourceTmpPath);
            break;
        case 'image/png':
            $srcImage = imagecreatefrompng($sourceTmpPath);
            break;
        case 'image/webp':
            $srcImage = imagecreatefromwebp($sourceTmpPath);
            break;
        default:
            return false; // unsupported type
    }

    if (!$srcImage) {
        return false;
    }

    // Step 2: Resize only if the image is wider than $maxWidth
    $origWidth  = imagesx($srcImage);
    $origHeight = imagesy($srcImage);

    if ($origWidth > $maxWidth) {
        $newWidth  = $maxWidth;
        $newHeight = intval($origHeight * ($maxWidth / $origWidth));

        $resized = imagescale($srcImage, $newWidth, $newHeight);
        imagedestroy($srcImage);
        $srcImage = $resized;
    }

    if (!$srcImage) {
        return false;
    }

    // Step 3: Save as JPEG (smallest reliable size for photos)
    // Destination path's extension is forced to .jpg for consistency & smaller size.
    $saved = imagejpeg($srcImage, $destPath, $quality);

    // Step 4: Free memory
    imagedestroy($srcImage);

    return $saved;
}
?>