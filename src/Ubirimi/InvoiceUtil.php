<?php

namespace Ubirimi;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Invoice;

class InvoiceUtil
{
    public function generate($paymentId)
    {
        $invoice = Invoice::getByPaymentId($paymentId);

        $invoiceNumber = $invoice['number'];
        $invoiceDate = $invoice['date_created'];
        $invoiceAmount = $invoice['amount'];
        $customerId = $invoice['client_id'];

        $client = Client::getById($customerId);
        $clientCountry = Client::getCountryById($client['sys_country_id']);

        $clientAdministrators = Client::getAdministrators($customerId);
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
        require_once __DIR__ . '/FrontendCOM/Resources/views/_invoice.php';
        $invoiceContent = ob_get_contents();
        ob_end_clean();

        $pdf->writeHTML($invoiceContent, true, false, false, false, '');

        $pdf->Output(UbirimiContainer::get()['invoice.path'] . '/' . sprintf('Ubirimi_%d.pdf', $invoiceNumber), 'F');
    }
}