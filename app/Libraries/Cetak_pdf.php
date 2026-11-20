<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class Cetak_pdf extends Dompdf
{
    /**
     * PDF filename
     */
    public string $filename = '';

    public function __construct(?Options $options = null)
    {
        if ($options === null) {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
        }

        parent::__construct($options);
    }

    /**
     * Load a CodeIgniter 4 view into Dompdf and stream it to the browser.
     */
    public function load_view(string $view, array $data = [], bool $attachment = false): void
    {
        $html = view($view, $data);
        $this->loadHtml($html);
        $this->render();
        $this->stream($this->filename, ['Attachment' => $attachment]);
    }
}

