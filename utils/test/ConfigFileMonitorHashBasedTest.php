<?php
require_once '../ConfigFileMonitorHashBased.php';

class ConfigFileMonitorHashBasedTest extends PHPUnit_Framework_TestCase
{

    private $roundCubeConfigMonitoring;
    private $hashFile;
    private $configFile = "config.php";
    const CONTENT = "Test content";

    protected function setUp()
    {
        parent::setUp();
        $this->hashFile = "hash";
        $this->createConfigFile();
        $this->roundCubeConfigMonitoring = new ConfigFileMonitorHashBased($this->hashFile, $this->configFile);
    }

    protected function tearDown()
    {
        $this->unlinkConfigFile();
        $this->roundCubeConfigMonitoring = null;
        parent::tearDown();
    }

    private function createConfigFile() {
        $content = self::CONTENT;
        $rPointer = fopen($this->configFile, "w");
        fwrite($rPointer, $content);
        fclose($rPointer);
    }

    private function createHashFileWithDefaultContent() {
        $content = md5(self::CONTENT);
        $rPointer = fopen($this->hashFile, "w");
        fwrite($rPointer, $content);
        fclose($rPointer);
    }

    private function createHashFileWithDifferrentContent() {
        $content = md5("Different content");
        $rPointer = fopen($this->hashFile, "w");
        fwrite($rPointer, $content);
        fclose($rPointer);
    }

    private function unlinkHashFile() {
        unlink($this->hashFile);
    }

    private function unlinkConfigFile() {
        unlink($this->configFile);
    }

    public function test__construct()
    {
        $this->roundCubeConfigMonitoring->__construct($this->hashFile, $this->configFile);
        $this->assertInstanceOf(ConfigFileMonitorHashBased::class, $this->roundCubeConfigMonitoring);
    }

    public function testCheckConfigFileExist()
    {
        $this->createConfigFile();
        $this->assertTrue($this->roundCubeConfigMonitoring->existsConfigFile());
    }

    public function testCheckConfigFileNotExist()
    {
        $this->unlinkConfigFile();
        $this->assertFalse($this->roundCubeConfigMonitoring->existsConfigFile());
        $this->createConfigFile();
    }

    public function testCompareFileEqual()
    {
        $this->createHashFileWithDefaultContent();
        $this->assertFalse($this->roundCubeConfigMonitoring->isConfigFileModified());
    }

    public function testCompareFileDifferent()
    {
        $this->createHashFileWithDifferrentContent();
        $this->assertTrue($this->roundCubeConfigMonitoring->isConfigFileModified());
    }

    public function testExistsHashFile() {
        $this->createHashFileWithDefaultContent();
        $this->assertTrue($this->roundCubeConfigMonitoring->existsHashFile());
        $this->unlinkHashFile();
    }

    public function testNotExistsHashFile() {
        if (file_exists($this->hashFile))
            $this->unlinkHashFile();
        $this->assertFalse($this->roundCubeConfigMonitoring->existsHashFile());
    }

}

