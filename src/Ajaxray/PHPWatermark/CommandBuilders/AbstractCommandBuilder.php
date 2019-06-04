<?php
/**
 * Created by PhpStorm.
 * User: Anis Ahmad <anis.programmer@gmail.com>
 * Date: 3/5/17
 * Time: 11:21 PM
 */

namespace Ajaxray\PHPWatermark\CommandBuilders;

use Ajaxray\PHPWatermark\Watermark;
use Ajaxray\PHPWatermark\Requirements\RequirementsChecker;

abstract class AbstractCommandBuilder
{
    protected $options;

    /**
     * @var string Source file path
     */
    protected $source;

    /**
     * AbstractCommandBuilder constructor.
     *
     * @param string $source The source file to watermark on
     */
    public function __construct($source)
    {
        $this->source = $source;

        (new RequirementsChecker())->checkImagemagickInstallation();
    }


    /**
     * Build the imagemagick shell command for watermarking with Image
     *
     * @param string $markerImage The image file to watermark with
     * @param string $output The watermarked output file
     * @param array $options
     * @return string
     */
    abstract public function getImageMarkCommand($markerImage, $output, array $options);

    /**
     * Build the imagemagick shell command for watermarking with Text
     *
     * @param string $text The text content to watermark with
     * @param string $output The watermarked output file
     * @param array $options
     * @return string
     */
    abstract public function getTextMarkCommand($text, $output, array $options);

    /**
     * @return string
     */
    protected function getSource()
    {
        return Watermark::escapeShellArg($this->source);
    }

    /**
     * @param $output
     * @param array $options
     * @return array
     */
    protected function prepareContext($output, array $options)
    {
        $this->options = $options;
        return array($this->getSource(), Watermark::escapeShellArg($output));
    }

    protected function getAnchor()
    {
        return 'gravity '. $this->options['position'];
    }

    /**
     * @return array
     */
    protected function getOffset()
    {
        return [$this->options['offsetX'], $this->options['offsetY']];
    }

    /**
     * @return int
     */
    protected function getStyle()
    {
        return $this->options['style'];
    }

    /**
     * @return bool
     */
    protected function isTiled()
    {
        return $this->options['tiled'];
    }

    /**
     * @return string
     */
    protected function getTextTileSize()
    {
        return "-size ".implode('x', $this->options['tileSize']);
    }

    /**
     * @return string
     */
    protected function getFont()
    {
        return '-pointsize '.intval($this->options['fontSize']).
            ' -font '.Watermark::escapeShellArg($this->options['font']);
    }

    /**
     * @return string
     */
    protected function getEncoding()
    {
        if(empty($this->options['encoding'])) return '';
        return '-encoding '.Watermark::escapeShellArg($this->options['encoding']);
    }

    protected function getDuelTextOffset()
    {
        $offset = $this->getOffset();
        return [
            "{$offset[0]},{$offset[1]}",
            ($offset[0] + 1) .','. ($offset[1] + 1),
        ];
    }

    protected function getImageOffset()
    {
        $offsetArr = $this->getOffset();
        return "geometry +{$offsetArr[0]}+{$offsetArr[1]}";
    }

    /**
     * @return float
     */
    protected function getOpacity()
    {
        return $this->options['opacity'];
    }

    /**
     * @return string
     */
    protected function getTile()
    {
        return empty($this->isTiled()) ? '' : '-tile';
    }

    
    protected function getDuelTextColor(array $options=[])
    {
        $textShadowRGBA=[255,255,255,$this->getOpacity()];//白色
        $textColorRGBA=[0,0,0,$this->getOpacity()];//黑色
        if(isset($options['textShadowRGBA'])){
            if(!is_array($options['textShadowRGBA'])) $options['textShadowRGBA']=explode(',',$options['textShadowRGBA'],4);
            for($i=0;$i<4;$i++){
                if(!isset($options['textShadowRGBA'][$i])) break;
                $textShadowRGBA[$i]=$options['textShadowRGBA'][$i];
            }
        }
        if(isset($options['textColorRGBA'])){
            if(!is_array($options['textColorRGBA'])) $options['textColorRGBA']=explode(',',$options['textColorRGBA'],4);
            for($i=0;$i<4;$i++){
                if(!isset($options['textColorRGBA'][$i])) break;
                $textColorRGBA[$i]=$options['textColorRGBA'][$i];
            }
        }
        $textShadow="rgba({$textShadowRGBA[0]},{$textShadowRGBA[1]},{$textShadowRGBA[2]},{$textShadowRGBA[3]})";
        $textColor="rgba({$textColorRGBA[0]},{$textColorRGBA[1]},{$textColorRGBA[2]},{$textColorRGBA[3]})";
        return [
            "fill '$textShadow'",//text shadow
            "fill '$textColor'",//text color
        ];
    }
}
