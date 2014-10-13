<?php

namespace Ubirimi;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Invoice;

class InvoiceUtil
{

    public function getLastByClientIdAndMonthAndYear($clientId, $month, $year) {
        $query = 'SELECT * ' .
            'FROM general_invoice ' .
            "WHERE client_id = ? and MONTH(date_created) = ? and YEAR(date_created) = ? " .
            "order by id desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("isi", $clientId, $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public function generate($clientId, $invoiceAmount, $invoiceNumber, $invoiceDate)
    {
        $customerId = $clientId;

        $client = $this->getRepository('ubirimi.general.client')->getById($customerId);
        $clientCountry = $this->getRepository('ubirimi.general.client')->getCountryById($client['sys_country_id']);

        $clientAdministrators = $this->getRepository('ubirimi.general.client')->getAdministrators($customerId);
        $firstClientAdministrator = $clientAdministrators->fetch_array(MYSQLI_ASSOC);

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('SC Ubirimi 137 SRL');
        $pdf->SetAuthor('SC Ubirimi 137 SRL');
        $pdf->SetTitle('Invoice ' . $invoiceNumber);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->setPrintHeader(false);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);

        ob_start();
        require __DIR__ . '/FrontendCOM/Resources/views/_invoice.php';
        $invoiceContent = ob_get_contents();
        ob_end_clean();

        $pdf->writeHTML($invoiceContent, true, false, false, false, '');

        $pdf->Output(UbirimiContainer::get()['invoice.path'] . '/' . sprintf('Ubirimi_%d.pdf', $invoiceNumber), 'F');

        $invoice = new Invoice();
        $invoice->save($clientId, $invoiceAmount, $invoiceNumber, $invoiceDate);
    }

    public function getLast() {
        $query = 'SELECT * ' .
            'FROM general_invoice ' .
            "order by id desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->fetch_array(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }
}