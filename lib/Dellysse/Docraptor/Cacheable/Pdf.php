<?php
namespace Dellysse\Docraptor\Cacheable;

use Dellysse\Docraptor\Pdf as DocraptorPdf;

abstract class Pdf extends DocraptorPdf {
    protected $cacheDirectory;
    public function getCacheDirectory () {
        return $this->cacheDirectory;
    }
    public function setCacheDirectory ($cacheDirectory) {
        $this->cacheDirectory = $cacheDirectory;
    }

    public function fetchDocument () {
        $content = print_r(array(
            'apiKey' => $this->getApiKey(),
            'documentContent' => $this->getDocumentContent(),
            'documentUrl' => $this->getDocumentUrl(),
            'documentType' => $this->getDocumentType(),
            'name' => $this->getName(),
            'test' => $this->isTest(),
        ), true);

        $hash = sha1($content);
        $filepath = "{$this->getCacheDirectory()}/{$hash}.pdf";
        if (file_exists($filepath)) {
            return file_get_contents($filepath);
        } else {
            $pdf = parent::fetchDocument();
            file_put_contents($filepath, $pdf);
            return $pdf;
        }
    }
}
