<?php

declare(strict_types=1);

namespace Jajuma\PotImageOptimization\Magewire;

use Magewirephp\Magewire\Component;

/**
 * Class ImageOptimization
 * @package Jajuma\PotImageOptimization\Magewire
 */
class ImageOptimization extends Component
{
    /**
     * @var bool
     */
    protected $loader = false;

    /**
     * @var string[]
     */
    protected $listeners = ['loadGrid'];

    /**
     * ImageOptimization constructor.
     */
    public function __construct()
    {
    }

}
