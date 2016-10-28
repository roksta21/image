<?php
/**
 * @category PHP
 * @author   Samson Mbuthia <roksta21@gmail.com>
 */

namespace Packages\Image;

use InterventionImage;
use Auth;
use File;

class Processor
{
    private $path;

    public function init()
    {
        $this->path = $this->checkPath(Auth::id());
        $this->name = $this->currentFiles();
    }

    private function currentFiles()
    {
        return count(File::directories($this->path)) + 1;
    }

    public function forUser($id)
    {
        $this->path = $this->checkPath($id);
        $this->name = $this->currentFiles();

        return $this;
    }

    public function make($image)
    {
        $this->init();
        $this->main = InterventionImage::make($image);
        $this->copy = InterventionImage::make($image);

        return $this;
    }

    private function checkPath($id)
    {
        $path = "storage/users/".$id;
        if (! File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        return $path;
    }

    private function makePath()
    {
        $this->save_to = $this->path.'/'.$this->name;
        if (! File::exists($this->save_to)) {
            File::makeDirectory($this->save_to, 0775, true);
        }
    }

    private function saveAvatar()
    {
        $this->copy->resize(90, 90, function ($constraint) {
          $constraint->aspectRatio();
        });
        $this->copy->resizeCanvas(90, 90, 'center', false, array(255, 255, 255, 0));
        $this->copy->save($this->save_to.'/avatar.png');
    }

    private function saveDefault()
    {
        $this->main->resize(235, 160, function ($constraint) {
          $constraint->aspectRatio();
        });
        $this->main->resizeCanvas(235, 160, 'center', false, array(255, 255, 255, 0));
        $this->main->save($this->save_to.'/image.png');
    }

    private function saveFull()
    {
        $this->main->save($this->save_to.'/full_image.png');
    }

    public function save()
    {
        $this->makePath();
        $this->saveFull();
        $this->saveDefault();
        $this->saveAvatar();

        return $this->name;
    }

    public function fetchMyPhotos()
    {
        $this->init();
        return $this->name - 1;
    }

    public function fetchUserPhotos($id)
    {
        $this->init();
        $path = $this->checkPath($id);
        return count(File::directories($path));
    }

}