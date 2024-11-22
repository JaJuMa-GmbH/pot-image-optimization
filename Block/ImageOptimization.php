<?php

namespace Jajuma\PotImageOptimization\Block;

use Jajuma\PotImageOptimization\ViewModel\ImageOptimization as ImageOptimizationViewModel;
use Jajuma\PowerToys\Block\PowerToys\Dashboard;
use Jajuma\PowerToys\Helper\Data;
use Magento\Framework\Indexer\ConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Module\Manager;

/**
 * Class ImageOptimization
 * @package Jajuma\PotImageOptimization\Block
 */
class ImageOptimization extends Dashboard
{

    /**
     *
     */
    public const XML_PATH_ENABLE = 'power_toys/image_optimization/is_enabled';

    /**
     * @var Data
     */
    protected $powerToysHelper;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var ImageOptimizationViewModel
     */
    private $viewModel;

    protected $moduleManager;

    /**
     * Reindex constructor.
     * @param Template\Context $context
     * @param Data $powerToysHelper
     * @param ConfigInterface $config
     * @param ImageOptimizationViewModel $viewModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $powerToysHelper,
        ConfigInterface $config,
        ImageOptimizationViewModel $viewModel,
        Manager $moduleManager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->powerToysHelper = $powerToysHelper;
        $this->config = $config;
        $this->viewModel = $viewModel;
        $this->moduleManager = $moduleManager;
    }


    /**
     * @return bool
     */
    public function isParentEnable()
    {
        return $this->moduleManager->isEnabled('Jajuma_ImageOptimizerUltimate');
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->_scopeConfig->isSetFlag(self::XML_PATH_ENABLE);
    }

    /**
     * @return bool
     */
    public function isLazyload(): bool
    {
        return true;
    }

    /**
     * @return ImageOptimizationViewModel
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

}
