<?php

namespace App\Services;

class Html2pdf
{
    private $pdf;

    public function create($orientation = null, $format = null, $lang = null, $unicode = null, $encoding = null, $margin = null)
    {
        $this->pdf = new \Spipu\Html2Pdf\Html2Pdf(
            $orientation ? $orientation : $this->orientation,
            $format ? $format : $this->format,
            $lang ? $lang : $this->lang,
            $unicode ? $unicode : $this->unicode,
            $encoding ? $encoding : $this->encoding,
            $margin ? $margin : $this->margin,
        );
    }

    public function generatePdf($template, $name){
        $this->pdf->writeHTML($template);
        return $this->pdf->Output($name, "S");
    }

    public function downloadPdf($template, $name){
        $this->pdf->writeHTML($template);
        return $this->pdf->Output($name, "D");
    }
}