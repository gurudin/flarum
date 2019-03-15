<?php

namespace App;

use Illuminate\Http\Request;
use Grafika\Grafika;
use Grafika\Color;

class Upload
{
    /**
     * Upload file.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if ($request->file('file')) {
            $file = $request->file('file');
        } else {
            $file = $request->file('upload');
        }
        $extension = $file->getClientOriginalExtension();
        $targetPath = sprintf(
            '/public/images/%s/%s',
            date('Y'),
            date('m')
        );
        $fileName = time() . random_int(1000, 9999) . '.' . $extension;

        $path =  $file->storeAs(
            $targetPath,
            $fileName
        );

        if ($path) {
            return ['status' => true, 'path' => str_replace('public', 'storage', $targetPath) . '/' . $fileName];
        } else {
            return ['status' => false, 'msg' => 'upload error.'];
        }
    }

    /**
     * Generate cover images.
     *
     * @param \Illuminate\Http\Request $request
     * @param string title
     *
     * @return \Illuminate\Http\Response
     *
     * @return mixed
     */
    public function generate(Request $request)
    {
        $width     = 100;
        $height    = 100;
        $font_size = 55;
        $font_ttc  = base_path() . '/public/fonts/PingFang.ttc';
        $text      = $request->post('title');

        // location
        $box  = imagettfbbox($font_size, 0, $font_ttc, $text);
        $left = ceil(($width - $box[2] - $box[0]) / 2);
        $top  = floor(($height - ($box[1] - $box[7])) / 2);

        // set image
        try {
            $editor = Grafika::createEditor();
            $image = Grafika::createBlankImage($width, $height);
            $editor->draw(
                $image,
                Grafika::createDrawingObject(
                    'Rectangle',
                    $width,
                    $height,
                    [0, 0],
                    null,
                    null,
                    new Color('#f0f0f0')
                )
            );

            $editor->text(
                $image,
                $text, // Text
                $font_size, // Font size
                $left, // Margin left
                $top, // Margin top
                new Color("#563d7c"), // font color
                $font_ttc, // Font path
                0
            );

            $paths = $this->getPath();
            $path =  $paths['target'] . '/' . $paths['name'];

            $editor->save(
                $image,
                storage_path('app' . $path)
            );

            return ['status' => true, 'msg' => 'success', 'path' => str_replace('public', 'storage', $path)];
        } catch (\Throwable $th) {
            return ['status' => false, 'msg' => 'Failed to create.'];
        }
    }

    /**
     * Get target & file anme.
     *
     * @param string $extension
     *
     * @return array
     */
    private function getPath($extension = 'png')
    {
        $targetPath = sprintf(
            '/public/images/%s/%s',
            date('Y'),
            date('m')
        );
        $fileName = time() . random_int(1000, 9999) . '.' . $extension;

        return ['target' => $targetPath, 'name' => $fileName];
    }
}
