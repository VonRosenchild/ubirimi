<table>
    <tr>
        <td align="right" style="height: 50px;">
            <img src="/img/logo_text_big.png">
        </td>
    </tr>
</table>

<br />
<br />
<table style="height: 550px;">
    <tr>
        <td width="75%" align="left">
SC Ubirimi 137 SRL <br />
Mihail Eminescu, no. 97, zip code 307160 <br />
Dumbravita, Timis <br />
Romania <br />
CUI RO32759913<br />
<br />
ING Bank Romania <br />
IBAN RO29INGB0000999904159749
        </td>
        <td width="25%" align="right">
Tel. +40 747 027 090 <br />
Tel. +40 752 170 174<br />
contact@ubirimi.com<br />
www.ubirimi.com
        </td>
    </tr>
</table>

<br />
<br />
<br />
<br />
<h3>Client data</h3>

<table style="height: 350px;">
    <tr>
        <td width="50%" align="left">
<?php echo $client['company_name'] ?><br />
            <?php echo $firstClientAdministrator['first_name'] . ' ' . $firstClientAdministrator['last_name'] ?><br />

            <?php if ($client['address_1']): ?>
                <?php echo $client['address_1'] ?> <br />
            <?php endif ?>

            <?php if ($client['address_2']): ?>
                <?php echo $client['address_2'] ?> <br />
            <?php endif ?>
            <?php if ($client['city'] || $client['district']): ?>
                <?php
                    $data = array();
                    if ($client['city']) $data[] = $client['city'];
                    if ($client['district']) $data[] = $client['district'];

                    echo implode(', ', $data);
                ?>
            <?php endif ?>
            <br />
            <?php echo $clientCountry['name'] ?>
        </td>

        <td align="right">
Customer ID: <?php echo $customerId ?> <br />
Invoice no.: UBR<?php echo $invoiceNumber ?> <br />
Invoice date: <?php echo substr($invoiceDate, 0, 10) ?>
        </td>
    </tr>
</table>

<br />
<br />
<h3>Invoice UBR <?php echo $invoiceNumber ?></h3>
<div></div>

<table width="100%" border="1px" cellpadding="4px">
    <tr>
        <td align="right" bgcolor="#d3d3d3">Pos</td>
        <td align="right" bgcolor="#d3d3d3">Product</td>
        <td align="right" bgcolor="#d3d3d3">Description</td>
        <td align="right" bgcolor="#d3d3d3"><b>Quantity</b></td>
        <td align="right" bgcolor="#d3d3d3">Unit Price</td>
    </tr>
    <tr>
        <td align="right">1</td>
        <td align="right">Ubrimi Product Suite</td>
        <td align="right">Yongo, Agile, Documentador, SVN Hosting, Calendar</td>
        <td align="right">1</td>
        <td align="right"><?php echo $invoiceAmount ?> $</td>
    </tr>
    <tr>
        <td colspan="4" align="right">Total</td>
        <td align="right"><?php echo $invoiceAmount ?> $</td>
    </tr>
</table>
<div></div>
<div></div>
<div>Payment method: Credit card</div>
<div>The invoice amount will soon be debited from your credit card.</div>

</table>