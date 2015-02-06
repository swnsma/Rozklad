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

    private function compress($source, $result, $type) {
        $image = null;
        if ($type == 'jpg')
            $image = imagecreatefromjpeg($source);
        elseif ($type == 'gif')
            $image = imagecreatefromgif($source);
        elseif ($type == 'png')
            $image = imagecreatefrompng($source);
        imagejpeg($image, $result, $this->quality);
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

            $file = uniqid() . '.' . $ext;

            $this->compress($tmp_name, $tmp_name, $ext);

            if (!move_uploaded_file($tmp_name, IMAGES_FOLDER . 'groups_photo/' . $file)) {
                throw new RuntimeException('failed to move uploaded file');
            }

            $this->upload_file_name = $file;
            return true;
        } catch(RuntimeException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getUploadFileName() {
        return $this->upload_file_name;
    }
}

?>