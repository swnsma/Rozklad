<?php

abstract class Upload {
    protected $file = null,
        $error = null,
        $max_size = 4194304; // 4 mb

    function __construct($files) {
        $this->file = $files;
    }

    public function checkFileError() {
        try {
            switch ($this->file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('File wasn\'t sent. Please try again later');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('File is too big');
                default:
                    throw new RuntimeException('Something went wrong. Please try again later');
            }
            return true;
        } catch(RuntimeException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function checkSize() {
        return $this->file['size'] > $this->max_size;
    }

    public function getFile() {
        return $this->file;
    }

    public function getError() {
        return $this->error;
    }

    public function getMaxSize() {
        return $this->max_size;
    }

    public function setMaxSize($size) {
        $this->max_size = $size;
    }
}

?>