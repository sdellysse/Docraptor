<?php
namespace Dellysse\Docraptor;

use Dellysse\Docraptor\Exception\Curl as CurlException;
use Dellysse\Docraptor\Exception\InvalidDocumentType as InvalidDocumentTypeException;
use Dellysse\Docraptor\Exception\MissingApiKey as MissingApiKeyException;

class DocRaptor {
    protected $documentTypes;
    public function __construct () {
        $this->documentTypes = array('pdf', 'xls');
    }


	protected $apiKey;
    public function getApiKey () {
        return $this->apiKey;
    }
	public function setApiKey($api_key){
        $this->apiKey = $api_key;
	}

	protected $documentContent;
    public function getDocumentContent () {
        return $this->documentContent;
    }
	public function setDocumentContent($document_content){
		$this->documentContent = $document_content;
	}

	protected $documentUrl;
    public function getDocumentUrl () {
        return $this->documentUrl;
    }
	public function setDocumentUrl($document_url){
		$this->documentUrl = $document_url;
	}


	protected $documentType;
    public function getDocumentType () {
        return $this->documentType;
    }
	public function setDocumentType ($documentType) {
        if (in_array($documentType, $this->documentTypes)) {
            $this->documentType = $documentType;
        } else {
            throw new InvalidDocumentTypeException($documentType);
        }
	}


	protected $name;
	public function setName($name){
		$this->name = $name;
	}
    public function getName () {
        return $this->name;
    }


	protected $test;
    public function isTest () {
        return $this->test;
    }
	public function setTest($test){
		$this->test = $test;
	}

	public function fetchDocument(){
        if (!$this->apiKey) {
            throw new MissingApiKeyException;
        }
        $url = "https://docraptor.com/docs?user_credentials={$this->getApiKey()}";
        $fields = array(
            'doc[document_type]'=>$this->getDocumentType(),
            'doc[name]'=>$this->getName(),
            'doc[test]'=>$this->isTest(),
        );
        if ($this->getDocumentContent()){
            $fields['doc[document_content]'] = urlencode($this->getDocumentContent());
        } else {
            $fields['doc[document_url]'] = urlencode($this->getDocumentUrl());
        }

        $encodedFields = array();
        foreach ($fields as $key=>$value) {
            $encodedFields []= "{$key}={$value}";
        }
        $fields_string = implode('&', $encodedFields);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if($result = curl_exec($ch)) {
            curl_close($ch);
            return $result;
        } else {
            throw new CurlException;
        }
	}
}

