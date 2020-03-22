<?php

namespace Anomaly\Streams\Platform\Image;

use Anomaly\Streams\Platform\Image\Concerns\CanOutput;
use Anomaly\Streams\Platform\Image\Concerns\HasSource;
use Anomaly\Streams\Platform\Image\Concerns\CanPublish;
use Anomaly\Streams\Platform\Image\Concerns\HasVersion;
use Anomaly\Streams\Platform\Image\Concerns\HasFilename;
use Anomaly\Streams\Platform\Image\Concerns\HasExtension;
use Anomaly\Streams\Platform\Image\Concerns\HasAlterations;
use Anomaly\Streams\Platform\Image\Concerns\HasQuality;
use Anomaly\Streams\Platform\Ui\Traits\HasHtmlAttributes;
use Illuminate\Support\Traits\Macroable;

/**
 * Class Image
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
class Image
{
    use Macroable;

    use HasSource;
    use HasQuality;
    use HasVersion;
    use HasFilename;
    use HasExtension;
    use HasAlterations;
    use HasHtmlAttributes;
    
    use CanOutput;
    use CanPublish;

    /**
     * The image source.
     *
     * @var mixed
     */
    protected $source;
    
    /**
     * The version flag.
     *
     * @var null|boolean
     */
    protected $version;

    /**
     * The output quality.
     *
     * @var null|int
     */
    protected $quality;

    /**
     * The file extension.
     *
     * @var null|string
     */
    protected $extension;

    /**
     * The desired filename.
     *
     * @var null|string
     */
    protected $filename;

    /**
     * The original filename.
     *
     * @var null|string
     */
    protected $original;

    /**
     * Applied alterations.
     *
     * @var array
     */
    protected $alterations = [];

    /**
     * Create a new Image instance.
     *
     * @param mixed $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Map Intervention methods through alterations.
     *
     * @param string $method
     * @param array $parameters
     * @return $this
     */
    public function __call($method, array $parameters = [])
    {
        if ($this->isAlteration($method)) {
            return $this->addAlteration($method, $parameters);
        }

        if ($this->hasMacro(snake_case($method))) {
            
            $macro = static::$macros[$method];

            if ($macro instanceof \Closure) {
                return call_user_func_array($macro->bindTo($this, static::class), $parameters);
            }

            return $macro(...$parameters);
        }

        // if ($this->macros->isMacro($macro = snake_case($method))) {
        //     return $this->macro($macro);
        // }

        return $this->addAttribute($method, array_shift($parameters));
    }

    /**
     * Return string output.
     *
     * @return string
     */
    public function __toString() {
        return $this->img();
    }
}
