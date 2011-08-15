<?php
namespace Dellysse\Docraptor;

use Dellysse\Docraptor\Pdf;

class Pdf extends Docraptor {
    public function __construct () {
        parent::__construct();
        $this->setDocumentType('pdf');
    }
}
