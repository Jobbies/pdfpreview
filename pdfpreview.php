<?php
file::$methods['pdfPreview'] = function($file, $size='800') {
    $key  = 'pdfpreview_'. md5($file->root());
    $thumb = kirby()->roots()->content() .DS. $key .'.jpg';
    $thumbUrl = '/' . $key .'.jpg';

    if (f::exists($thumb)) {
        if ( (f::modified($thumb) > $file->modified())) {
            return image($thumbUrl);
        }
        f::remove($thumb);
    }

    $image = new Imagick($file->root().'[0]'); // first page only
    $image->setImageColorspace(Imagick::COLORSPACE_RGB);
    $image->setImageBackgroundColor('white');
    $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
    $image->setImageFormat('jpg');
    $image->setImageCompression(Imagick::COMPRESSION_JPEG);
    $image->setImageCompressionQuality(80);
    $image->thumbnailImage($size, 0);
    $image->writeImage($thumb);
    $image->clear();
    $image->destroy();

    return image($thumbUrl);
};
