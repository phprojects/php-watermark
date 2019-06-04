<?php
/**
 * Criado por Maizer Aly de O. Gomes para php-watermark.
 * Email: maizer.gomes@gmail.com / maizer.gomes@ekutivasolutions / maizer.gomes@outlook.com
 * Usuário: maizerg
 * Data: 7/20/18
 * Hora: 12:01 PM
 */

namespace Ajaxray\PHPWatermark\Requirements;

use Ajaxray\PHPWatermark\Watermark;

class RequirementsChecker
{

    public function checkImagemagickInstallation()
    {
        exec(Watermark::$commandPrefix.'convert -version', $out, $rcode);

        if ($rcode) {
            throw new \BadFunctionCallException("ImageMagick not found in this system.");
        }

        return true;
    }
}