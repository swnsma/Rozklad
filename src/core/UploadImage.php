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

    private function compress($source, $type) {
        $image = null;
        if ($type == 'jpg')
            $image = imagecreatefromjpeg($source);
        elseif ($type == 'gif')
            $image = imagecreatefromgif($source);
        elseif ($type == 'png')
            $image = imagecreatefrompng($source);
        imagejpeg($image, $source, $this->quality);
    }

    private function crop($image, $r_image, $w, $h) {
        list($w_i, $h_i, $type) = getimagesize($image);
        $types = array('', 'gif', 'jpeg', 'png');
        $ext = $types[$type];
        if ($ext) {
            $func = 'imagecreatefrom'.$ext;
            $img_i = $func($image);
        } else {
            return false;
        }

        $img_o = imagecreatetruecolor($w, $h);

        if ($h_i > $h) {
            $y_o = ($h_i-$h)/2;
        } else {
            $y_o = 0;
        }

        if ($w_i > $w) {
            $x_o = ($w_i-$w)/2;
        } else {
            $x_o = 0;
        }

        imagecopy($img_o, $img_i, 0, 0, $x_o, $y_o, $w, $h);
        $func = 'image'.$ext;
        return $func($img_o, $r_image);
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

            if ($this->crop($tmp_name, $img_folder . 'small_' . $file, 100, 100)) {
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