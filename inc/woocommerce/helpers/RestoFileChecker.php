<?php

/**
 * Check files before upload
 */
final class RestoFileChecker{
    
    private $error = false;
    
    public function check($file_key){
        if( !isset($_FILES[$file_key]) ){
            $this->error = 'File not uploaded';
            return false;
        }

        $file = $_FILES[$file_key];

        // if file size > 10Mb or upload error exists
        if( $file['size'] > 10485760 || $file['error'] > 0){
            $this->error = 'File size must be less than 10Mb';
            return false;
        }

        if( $file['error'] > 0 ){
            $this->error = "File upload error code: {$file['error']}";
            return false;
        }
        
        $allowed_extensions = array(
            'jpeg',
            'jpg',
            'png'
        );
        $allowed_mime_types = array(
            'image/jpeg',
            'image/jpg',
            'application/jpg',
            'image/png',
            'application/png',
            'application/x-png'
        );

        $file_ext  = pathinfo( $file['name'], PATHINFO_EXTENSION );
        $file_type = $file['type'];

        if( !in_array($file_type, $allowed_mime_types) || !in_array(strtolower($file_ext), $allowed_extensions) ) {
            $this->error = "File type not allowed";
            return false;
        }

        // Check to see if any PHP files are trying to be uploaded
        $file_content = file_get_contents($_FILES[$file_key]['tmp_name']);
        if (preg_match('/\<\?php/i', $file_content)) {
            $this->error = "File type not allowed";
            return false;
        }
        
        return true;
    }
    
    public function getError(){
        return $this->error;
    }
}
