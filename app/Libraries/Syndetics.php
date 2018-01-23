<?php

namespace Couchcat\Libraries;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use IsoCodes\Isbn;

class Syndetics
{

    public function __construct($client_id)
    {
        $this->client_id = $client_id;
        $this->base_uri = 'http://www.syndetics.com/index.aspx';
        $this->guzzle = new Client(['base_uri' => $this->base_uri]);
        $this->isbn = '';
        $this->upc = '';
        $this->oclc = '';
    }

    public function getIsbn($isbn)
    {
        if (!Isbn::validate($isbn)) {
            return false;
        }
        $this->isbn = $isbn;
        $get_vars = 'isbn=' . $isbn . '/index.xml&client='.$this->client_id.'&type=xw10';
        $this->syndetics_links = $this->parseSyndeticsXml($get_vars);
        return $this;
    }

    public function getUpc($upc)
    {
        $this->upc = $upc;
        $get_vars = 'isbn=/index.xml&client='.$this->client_id.'&type=xw10&upc='.$upc;
        $this->syndetics_links = $this->parseSyndeticsXml($get_vars);
        return $this;
    }

    public function getOclc($oclc)
    {
        $this->oclc = $oclc;
        $get_vars = 'isbn=/index.xml&client='.$this->client_id.'&type=xw10&oclc='.$oclc;
        $this->syndetics_links = $this->parseSyndeticsXml($get_vars);
        return $this;
    }

    private function parseSyndeticsXml($get_vars)
    {
        $links = [];
        $response = $this->guzzle->request('GET', '?' . $get_vars);
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        $syndetics_xml = (array) simplexml_load_string($response->getBody());
        foreach ($syndetics_xml as $xkey => $xval) {
                $links[] = $xkey;
        }

        return $links;
    }

    public function getCoverUrl()
    {
        if (in_array('LC', $this->syndetics_links)) {
            return $this->base_uri.'?isbn='.$this->isbn.'/LC.JPG&client='.$this->client_id.'&type=xw10&upc='.$this->upc.'&oclc='.$this->oclc;
        }
        return false;
    }
}
