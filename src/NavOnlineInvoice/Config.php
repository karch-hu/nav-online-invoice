<?php

namespace NavOnlineInvoice;
use Exception;


class Config {

    public $user;
    public $software;

    public $baseUrl;
    public $verifySLL = false;

    public $validateApiSchema = false;
    public $apiSchemaFilename;


    /**
     * NavOnlineInvoice Reporter osztály számára szükséges konfigurációs objektum készítése
     *
     * @param String       $baseUrl     NAV API URL
     * @param Array|String $user        User data array vagy json fájlnév
     * @param Array|String $software    Software data array vagy json fájlnév
     */
    function __construct($baseUrl, $user, $software = null) {

        $this->apiSchemaFilename = __DIR__ . "/xsd/invoiceApi.xsd";

        if (!$baseUrl) {
            throw new Exception("A baseUrl paraméter megadása kötelező!");
        }

        $this->setBaseUrl($baseUrl);

        if (!$user) {
            throw new Exception("A user paraméter megadása kötelező!");
        }

        if (is_string($user)) {
            $this->loadUser($user);
        } else {
            $this->setUser($user);
        }

        if ($software) {
            if (is_string($software)) {
                $this->loadSoftware($software);
            } else {
                $this->setSoftware($software);
            }
        }
    }


    /**
     * NAV online számla API eléréséhez használt URL
     *
     * Teszt: https://api-test.onlineszamla.nav.gov.hu/invoiceService
     * Éles: https://api.onlineszamla.nav.gov.hu/invoiceService
     *
     * @param String $baseUrl  NAV eléréséhez használt környezet
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }


    /**
     * NAV szerverrel való kommunikáció előtt ellenőrizze az XML adatot az API sémával szemben
     *
     * @param  boolean $flag
     */
    public function useApiSchemaValidation($flag = true) {
        $this->validateApiSchema = $flag;
    }


    /**
     *
     * @param Array $data
     */
    public function setSoftware($data) {
        $this->software = $data;
    }


    /**
     *
     * @param  String $jsonFile JSON file name
     */
    public function loadSoftware($jsonFile) {
        $data = $this->loadJsonFile($jsonFile);
        $this->setSoftware($data);
    }


    /**
     *
     * @param Array $data
     */
    public function setUser($data) {
        $this->user = $data;
    }


    /**
     *
     * @param  String $jsonFile JSON file name
     */
    public function loadUser($jsonFile) {
        $data = $this->loadJsonFile($jsonFile);
        $this->setUser($data);
    }


    /**
     * JSON fájl betöltése
     *
     * @param  String $jsonFile
     * @return Array
     */
    protected function loadJsonFile($jsonFile) {
        if (!file_exists($jsonFile)) {
            throw new Exception("A megadott json fájl nem létezik: $jsonFile");
        }

        $content = file_get_contents($jsonFile);
        $data = json_decode($content, true);

        if ($data === null) {
            throw new Exception("A megadott json fájlt nem sikerült dekódolni!");
        }

        return $data;
    }

}
