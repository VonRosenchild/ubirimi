<?php
use Ubirimi\SystemProduct;

$urlIssuePrefix = '/yongo/issue/';
$selectedProductId = SystemProduct::SYS_PRODUCT_YONGO;
$columns = $this->columns;
$issues = $this->issues;
$issuesCount = $issues->num_rows;
$clientSettings = $this->clientSettings;
$clientId = $this->clientId;
$cliMode = $this->cliMode;
$getSearchParameters = $this->searchParameters;
require __DIR__ . '/../issue/search/_listResult.php';