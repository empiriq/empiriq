<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Helpers;

use Empiriq\BinanceBackTradeBundle\Common\Interfaces\CsvLoaderInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use ZipArchive;

/**
 * @internal
 */
class CsvLoader implements CsvLoaderInterface
{
    private string $workingDir;
    private ClientInterface $client;
    private ZipArchive $archive;

    public function __construct(
        ?string $workingDir = null,
        ?ClientInterface $client = null,
        ?ZipArchive $archive = null
    ) {
        $this->workingDir = $workingDir ?? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'binifit-history-env';
        $this->client = $client ?? new Client();
        $this->archive = $archive ?? new ZipArchive();
    }

    public function load(string $uri): string
    {
        if (!file_exists($this->getCsvFile($uri))) {
            $directory = dirname($this->getZipFile($uri));
            if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new Exception("Failed to create directory: {$directory}");
            }
            $this->client->request('GET', $uri, ['sink' => $this->getZipFile($uri)]);
            if (!$this->archive->open($this->getZipFile($uri))) {
                throw new Exception("Failed to open zip file: {$this->getZipFile($uri)}");
            }
            if (!$this->archive->extractTo($directory)) {
                throw new Exception("Failed to extract csv file: {$this->getCsvFile($uri)}");
            }
            $this->archive->close();
            unlink($this->getZipFile($uri));
        }

        return $this->getCsvFile($uri);
    }

    private function getZipFile(string $uri): string
    {
        return $this->workingDir . parse_url($uri, PHP_URL_PATH);
    }

    private function getCsvFile($uri): string
    {
        return str_replace('.zip', '.csv', $this->getZipFile($uri));
    }
}
