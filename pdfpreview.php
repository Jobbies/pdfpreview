<?php

function preview($file, $size)
{
    $key  = 'pdfpreview_'. md5($file->root());
    $thumb = kirby()->roots()->thumbs() .DS. $key;

    if (f::exists($thumb))
    {
        if ( (f::modified($thumb) > $file->modified())) {
            return kirby()->urls()->thumbs() .'/'. $key;
        }
        f::remove($thumb);
    }

    $image = new Imagick($file->root().'[0]'); // first page only
    $image->setColorspace(COLORSPACE_RGB);
    $image->setImageBackgroundColor('white');
    $image->setImageAlphaChannel(11);
    $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
    $image->setImageFormat('jpg');
    $image->setImageCompression(Imagick::COMPRESSION_JPEG);
    $image->setImageCompressionQuality(80);
    $image->thumbnailImage($size, 0);
    $image->borderImage('black', 1, 1);
    $image->writeImage($thumb);

    return kirby()->urls()->thumbs() .'/'. $key;
};
