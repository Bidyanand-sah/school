/* ============================================================
   SHARED IMAGE CONVERTER
   Koi bhi image (AVIF, WebP, PNG, GIF, BMP, HEIC/HEIF, JPG, JPEG)
   upload se pehle browser mein JPEG ban jaati hai.
   Use: const blob = await convertToJpeg(file);
   ============================================================ */

const MAX_WIDTH = 1600;
const JPEG_QUALITY = 0.85;
const MAX_INPUT_MB = 40;
const HEIC_LIB_URL = 'https://cdn.jsdelivr.net/npm/heic-to@1.5.2/dist/iife/heic-to.js';

function isHeic(file) {
    const name = (file.name || '').toLowerCase();
    return /image\/hei[cf]/.test(file.type) || /\.(heic|heif)$/.test(name);
}

let heicLoader = null;
function loadHeicLib() {
    if (window.HeicTo) return Promise.resolve();
    if (heicLoader) return heicLoader;

    heicLoader = new Promise((resolve, reject) => {
        const s = document.createElement('script');
        s.src = HEIC_LIB_URL;
        s.onload = resolve;
        s.onerror = () => {
            heicLoader = null;
            reject(new Error('HEIC converter load nahi hua. Internet check karo.'));
        };
        document.head.appendChild(s);
    });
    return heicLoader;
}

async function decodeToBitmap(file) {
    try {
        return await createImageBitmap(file, { imageOrientation: 'from-image' });
    } catch (e1) {
        try {
            return await createImageBitmap(file);
        } catch (e2) {
            if (!isHeic(file)) {
                throw new Error('Is browser mein ye image format support nahi hai. JPG ya PNG use karo.');
            }
        }
    }

    // Yahan sirf HEIC/HEIF pahunchti hai
    await loadHeicLib();
    const result = await window.HeicTo({ blob: file, type: 'image/jpeg', quality: 0.9 });
    return await createImageBitmap(result);
}

async function convertToJpeg(file) {
    if (!file.type.startsWith('image/') && !isHeic(file)) {
        throw new Error('Sirf image file select karo.');
    }
    if (file.size > MAX_INPUT_MB * 1024 * 1024) {
        throw new Error('File ' + MAX_INPUT_MB + 'MB se badi hai.');
    }

    const bitmap = await decodeToBitmap(file);

    let w = bitmap.width;
    let h = bitmap.height;
    if (w > MAX_WIDTH) {
        h = Math.round(h * (MAX_WIDTH / w));
        w = MAX_WIDTH;
    }

    const canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, w, h);
    ctx.drawImage(bitmap, 0, 0, w, h);
    bitmap.close();

    return await new Promise((resolve, reject) => {
        canvas.toBlob(blob => {
            if (blob) resolve(blob);
            else reject(new Error('Image convert nahi ho payi.'));
        }, 'image/jpeg', JPEG_QUALITY);
    });
}