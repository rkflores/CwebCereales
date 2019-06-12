<?php

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "core/app/model/BoxData.php";
include "fpdf/fpdf.php";
session_start();
$symbol = ConfigurationData::getByPreffix("currency")->val;
$stock = StockData::getPrincipal();
$box = BoxData::getById($_GET["id"]);
$products = SellData::getByBoxId($_GET["id"]);
$sells = SellData::getByBoxId($box->id);
$total=0;
		


$pdf = new FPDF($orientation='P',$unit='mm', array(45,350));
$pdf->AddPage();
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->setXY(5,0);
$pdf->setY(2);
$pdf->setX(2);
$pdf->Image('logo1.png', 12.5, $pdf->GetY(), 20);
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX(2);
$pdf->setX(2);
$pdf->Cell(5,48,"                         Corte de caja #$box->id");
$pdf->setX(2);
$pdf->Cell(5,50,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,56,"FECHA                                    TOTAL DE VENTA");

$total =0;
$off = 62;
foreach($products as $sell){
$pdf->setX(2);
$pdf->Cell(5,$off,"$sell->created_at");
$pdf->setX(6);
$pdf->setX(20);
$pdf->setX(32);
$total_total += $sell->total-$sell->discount;
$pdf->Cell(11,$off,  "$symbol ".number_format($sell->total-$sell->discount,2,".",",") ,0,0,"R");

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->price_out;
$off+=6;
}

$pdf->setX(2);
$pdf->Cell(5,$off+5+18,"TOTAL DE CORTE: " );
$pdf->setX(38);
$pdf->Cell(5,$off+5+18,"$symbol ".number_format($total_total,2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+5+36,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,$off+5+42,"SUCURSAL: ".strtoupper($stock->name));
$pdf->setX(2);
$pdf->Cell(5,$off+5+48,"FOLIO: ".$box->id.'   -   FECHA: '.$box->created_at);


$pdf->output();
?>