<?php

namespace App\Services;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\Response;

class AmazonWS
{
    private $s3;     
    public function __construct(S3Client $S3Client) {
       $this->s3 = $S3Client;       
    }

    public function getPrivateFile(string $bucket, string $key){
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);
        
        //The period of availability
        $request = $this->s3->createPresignedRequest($cmd, '+10 minutes');
        
        //Get the pre-signed URL
        $signedUrl = (string) $request->getUri();
        return $signedUrl;
    }

    public function uploadPrivateFile(string $bucket, string $key, string $file, string $mimetype = null)
    {
        if( $mimetype = null){
            $params = [
                'Bucket' => $bucket,
                'Key'    => $key,
                'SourceFile' => $file
            ];
        } else {
            $params = [
                'Bucket' => $bucket,
                'Key'    => $key,
                'SourceFile' => $file,
                'ContentType' => $mimetype
            ];
        }
        
        // Using commands explicitly
        $command = $this->s3->getCommand('PutObject', $params);
        $result = $this->s3->execute($command);

        $url = $this->getPrivateFile($bucket, $key);

        return new response($url);
    }
    
    public function generatePrivateFile(string $bucket, string $key, string $body, string $mimetype)
    {
        $params = [
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $body,
            'ACL' => 'private',
            'ContentType' => $mimetype
        ];
    
        $command = $this->s3->getCommand('PutObject', $params);
        $result = $this->s3->execute($command);
        $url = $this->getPrivateFile($bucket, $key);
    
        return $url;  
    }

    public function deleteFile(string $bucket, string $key){
        $cmd = $this->s3->getCommand('DeleteObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);
        
        $result = $this->s3->execute($cmd);
        return $result;
    }

    public function getPublicFile(string $bucket, string $key){
        $url = $this->s3->getObjectUrl($bucket, $key);

        return $url;
    }
}