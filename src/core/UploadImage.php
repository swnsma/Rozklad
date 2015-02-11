<?php

require_once DOC_ROOT . 'core/include/upload.php';

class UploadImage extends Upload {
    private $upload_file_name = null,
        $quality = 80,
        $mime_types = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        );

    function __construct($files) {
        parent::__construct($files);
    }

    private function compress($source, $type)
    {
        $image = null;
        if ($type == 'jpg')
            $image = imagecreatefromjpeg($source);
        elseif ($type == 'gif')
            $image = imagecreatefromgif($source);
        elseif ($type == 'png')
            $image = imagecreatefrompng($source);
        imagejpeg($image, $source, $this->quality);
    }

    private function crop($image, $r_image) {
        list($w_i, $h_i, $type) = getimagesize($image);
        $types = array('', 'gif', 'jpeg', 'png');
        $ext = $types[$type];
        if ($ext) {
            $func = 'imagecreatefrom'.$ext;
            $img_i = $func($image);
        } else {
            return false;
        }

        $min = $w_i < $h_i ? $w_i : $h_i;

        $img_o = imagecreatetruecolor($min, $min);

        imagecopy($img_o, $img_i, 0, 0, ($w_i-$min)/2, ($h_i-$min)/2, $min, $min);
        $func = 'image'.$ext;
        return $func($img_o, $r_image);
    }

    function resize($image, $w_o, $h_o) {
        list($w_i, $h_i, $type) = getimagesize($image);
        if (!$w_i || !$h_i) {
            return false;
        }
        $types = array('', 'gif', 'jpeg', 'png');
        $ext = $types[$type];

        if ($ext) {
            $func = 'imagecreatefrom' . $ext;
            $img = $func($image);
        } else {
            return false;
        }

        if (!$h_o) $h_o = $w_o/($w_i/$h_i);
        if (!$w_o) $w_o = $h_o/($h_i/$w_i);

        $img_o = imagecreatetruecolor($w_o, $h_o);

        imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);

        if ($type == 2) {
            return imagejpeg($img_o, $image, 100);
        } else {
            $func = 'image'.$ext;
            return $func($img_o, $image);
        }
    }


    public function getQuality() {
        return $this->quality;
    }

    public function setQuality($size) {
        $this->quality = $size;
    }

    public function getMimeTypes() {
        return $this->mime_types;
    }

    public function upload() {
        try {
            if ($this->checkSize()) {
                throw new RuntimeException('File is too big');
            }

            $tmp_name = $this->file['tmp_name'];
            $info = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search($info->file($tmp_name), $this->mime_types, true)) {
                throw new RuntimeException('invalid file format');
            }

            $this->compress($tmp_name, $ext);

            $file = uniqid() . '.' . $ext;
            $img_folder = IMAGES_FOLDER . 'groups_photo/';
            $small_img = $img_folder . 'small_' . $file;

            if ($this->crop($tmp_name, $small_img)
                && $this->resize($small_img, 50, 50)) {
                if (!move_uploaded_file($tmp_name, $img_folder . $file)) {
                    throw new RuntimeException('failed to move uploaded file');
                }

                $this->upload_file_name = $file;
                return true;
            }

        } catch(RuntimeException $e) {}
        return false;
    }

    public function getUploadFileName() {
        return $this->upload_file_name;
    }
}

?>