<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 */
abstract class BaseController extends Controller
{
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form'];

    protected Session $session;
    protected BaseConnection $db;

    /**
     * @param CLIRequest|IncomingRequest $request
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = service('session');
        $this->db      = \Config\Database::connect();
    }

    /**
     * Bersihkan input nominal uang berformat ribuan/desimal Indonesia menjadi angka murni untuk DB.
     */
    protected function parseNominal($value): string
    {
        $raw = trim((string) ($value ?? ''));
        if ($raw === '') {
            return '';
        }

        // Hapus spasi dan simbol non-angka selain titik, koma, dan minus
        $clean = preg_replace('/[^\d.,-]/', '', $raw);

        if (str_contains($clean, ',') && str_contains($clean, '.')) {
            // Format Indonesia: 1.000.000,50 -> 1000000.50
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } elseif (str_contains($clean, ',')) {
            // Format koma desimal: 10000,50 -> 10000.50
            $clean = str_replace(',', '.', $clean);
        } elseif (preg_match('/^-?\d{1,3}(\.\d{3})+$/', $clean)) {
            // Format ribuan bertitik tanpa desimal: 10.000 atau 1.000.000 -> 10000 / 1000000
            $clean = str_replace('.', '', $clean);
        }

        return $clean;
    }
}
