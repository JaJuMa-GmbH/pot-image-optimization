<?php

namespace Jajuma\PotImageOptimization\ViewModel;

use Jajuma\ImageOptimizerUltimate\Model\ResourceModel\Image\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class ImageOptimization
 * @package Jajuma\PotImageOptimization\ViewModel
 */
class ImageOptimization
{

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var array
     */
    private $totalImages = [];

    /**
     * ImageOptimization constructor.
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
        $this->connection = $this->resource->getConnection();
    }

    /**
     * @param $status
     *
     * @return string|null
     */
    public function getProgressBarContent($status)
    {
        if ($this->getTotalByStatus($status) === 0) {
            return null;
        }

        return $this->getProgressWidthByStatus($status) . ' '
            . $status . ' (' . $this->getTotalByStatus($status)
            . '/' . $this->getTotalImage() . ')';
    }

    /**
     * @param $status
     *
     * @return int
     */
    public function getTotalByStatus($status)
    {
        $total = $this->connection->fetchCol(
            $this->connection->select()->from('jajuma_image_optimizer', 'image_id')
                ->where('status = ?', $status)
        );

        return count($total);
    }

    /**
     * @param $status
     *
     * @return string
     */
    public function getProgressWidthByStatus($status)
    {
        if ($this->getTotalImage() === 0 || $this->getTotalByStatus($status) === 0) {
            return '0%';
        }
        $width = $this->getTotalByStatus($status) / $this->getTotalImage();

        return round($width * 100, 3) . '%';
    }

    /**
     * @return int
     */
    public function getTotalImage()
    {
        if (!count($this->totalImages)) {
            $this->totalImages = $this->connection->fetchCol(
                $this->connection->select()->from('jajuma_image_optimizer', 'image_id')
            );
        }
        return count($this->totalImages);
    }

    /**
     * @return array
     */
    public function getFileSizeSavingsMb()
    {
        $data = [];
        $query = 'SELECT COUNT(jio.image_id) as `number`, SUM(jio.percent) as `webp_percent`, SUM(jio.avif_percent) as `avif_percent`,  SUM(jio.origin_size) as `origin_size`, SUM(jio.optimize_size) as `optimize_size`, SUM(jio.avif_optimize_size) as `avif_optimize_size`
                        FROM jajuma_image_optimizer jio where jio.status = "success";
                    ';
        $savedData = $this->connection->fetchRow($query);
        if (count($savedData)) {
            $data = $savedData;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getAllOfScannedImageTypes()
    {
        $types = $this->connection->fetchCol(
            '
                SELECT DISTINCT 
                CASE
                    WHEN LOCATE(\'.jpg\', jio.`path`) > 0 THEN ".jpg"
                    WHEN LOCATE(\'.jpeg\', jio.`path`) > 0 THEN ".jpg"
                    WHEN LOCATE(\'.png\', jio.`path`) > 0 THEN ".png"
                    WHEN LOCATE(\'.gif\', jio.`path`) > 0 THEN ".gif"
                END as `mine`
                FROM jajuma_image_optimizer jio;
            '
        );

        return $types;
    }

    /**
     * @param $size
     * @return string
     */
    public function convertToReadableSize($size)
    {
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[(int)$f_base];
    }
}