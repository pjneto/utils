<?php

class ConfigFileMonitorHashBased {

    private $sConfigFile;
    private $sHashFile;

    public function __construct($sHashFile, $sConfigFile) {
        $this->sHashFile = $sHashFile;
        $this->sConfigFile = $sConfigFile;
        if (!$this->existsConfigFile())
            throw new InvalidArgumentException("Configuration file not exists!");
    }

    public function existsConfigFile() {
        return file_exists($this->sConfigFile);
    }

    public function isConfigFileModified() {
        return $this->existsConfigFile() && md5_file($this->sConfigFile) != file_get_contents($this->sHashFile);
    }

    public function existsHashFile() {
        return file_exists($this->sHashFile);
    }

    public function createHashFile() {
        $rPointer = fopen($this->sHashFile, "w+");
        fclose($rPointer);
    }

    public function writeHashOnHashFile() {
        if (file_exists($this->sHashFile)){
            unlink($this->sHashFile);
        }
        $rPointer = fopen($this->sHashFile, "w+");
        fwrite($rPointer, md5_file($this->sConfigFile));
        fclose($rPointer);
    }

}
