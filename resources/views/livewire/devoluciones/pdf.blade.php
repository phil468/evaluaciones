<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style type="text/css">
		html { font-family:Calibri, Arial, Helvetica, sans-serif; font-size:11pt; background-color:white }
		a.comment-indicator:hover + div.comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em }
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em }
		div.comment { display:none }
		table { border-collapse:collapse; page-break-after:always }
		.gridlines td { border:1px dotted black }
		.gridlines th { border:1px dotted black }
		.b { text-align:center }
		.e { text-align:center }
		.f { text-align:right }
		.inlineStr { text-align:left }
		.n { text-align:right }
		.s { text-align:left }
		td.style0 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style0 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style1 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style1 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style2 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style2 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style3 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style3 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style4 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style4 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style5 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style5 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style6 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style6 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style7 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style7 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style8 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style8 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style9 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style9 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style10 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style10 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style11 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style11 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:1px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style12 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style12 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style13 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style13 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style14 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style14 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style15 { vertical-align:top; text-align:right; padding-right:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style15 { vertical-align:top; text-align:right; padding-right:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style16 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style16 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style17 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style17 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style18 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style18 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style19 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style19 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style20 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style20 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style21 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style21 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style22 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		th.style22 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		td.style23 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style23 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style24 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style24 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style25 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style25 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style26 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style26 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style27 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style27 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style28 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style28 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style29 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style29 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style30 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style30 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style31 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style31 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style32 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style32 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style33 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		th.style33 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:1px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#D9D9D9 }
		td.style34 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style34 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style35 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style35 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style36 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style36 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:1px solid #000000 !important; border-top:1px solid #000000 !important; border-left:1px solid #000000 !important; border-right:1px solid #000000 !important; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style37 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; font-style:italic; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style37 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; font-style:italic; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style38 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		th.style38 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:white }
		td.style39 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		th.style39 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
		td.style40 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style40 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style41 { vertical-align:top; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style41 { vertical-align:top; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style42 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style42 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style43 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style43 { vertical-align:top; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style44 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:#FFFFFF }
		th.style44 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Times New Roman'; font-size:11pt; background-color:#FFFFFF }
		td.style45 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style45 { vertical-align:top; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style46 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style46 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		td.style47 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		th.style47 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:#FFFFFF }
		table.sheet0 col.col0 { width:84.04444348pt }
		table.sheet0 col.col1 { width:62.35555484pt }
		table.sheet0 col.col2 { width:69.13333254pt }
		table.sheet0 col.col3 { width:31.85555519pt }
		table.sheet0 col.col4 { width:38.63333289pt }
		table.sheet0 col.col5 { width:45pt }
		table.sheet0 col.col6 { width:110pt }
		table.sheet0 tr { height:15pt }
		table.sheet0 tr.row1 { height:30pt }
		table.sheet0 tr.row17 { height:48.75pt }
		table.sheet0 tr.row18 { height:15pt }
		table.sheet0 tr.row19 { height:15pt }
		table.sheet0 tr.row27 { height:83.25pt }
		table.sheet0 tr.row36 { height:32.25pt }
		table.sheet0 tr.row37 { height:36.75pt }
		#canvas {
			border: 1px solid black;
		}
		#firma_responsable {
			border: 1px solid black;
		}
		#firma_personal {
			border: 1px solid black;
		}
		#firma {
			border: 1px solid black;
		}
	  </style>
		
	
</head>
<body>
    
<table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines">
    <col class="col0">
    <col class="col1">
    <col class="col2">
    <col class="col3">
    <col class="col4">
    <col class="col5">
    <col class="col6">
    <tbody>
      <tr class="row0">
        <td class="" rowspan="5" style="border: 1px solid black;" align="center">
            <img src="{{ asset('img/icon/VanguardPeru-Sp-2cWeb-xsm.png') }}" alt="logo_vanguard_peru" />
        </td>
        <td class="column1 style2 s style2" colspan="6">ACTA DE DEVOLUCION DE ACTIVOS DE TI</td>
      </tr>
      <tr class="row1">
        <td class="column1 style4 s style7" colspan="5"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Documento: </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">Registro</span></td>
        <td class="column6 style8 s"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Código: </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">LOV-TI-RE-01</span></td>
      </tr>
      <tr class="row2">
        <td class="column1 style4 s style7" colspan="5"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Elaborado por:  </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">Administrador de Infraestructura de TI</span></td>
        <td class="column6 style9 s"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Versión: </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">01</span></td>
      </tr>
      <tr class="row3">
        <td class="column1 style4 s style7" colspan="5"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Revisado por: </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">Jefatura de TI</span></td>
        <td class="column6 style9 s"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Fecha: </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">04/07/2022</span></td>
      </tr>
      <tr class="row4">
        <td class="column1 style11 s style13" colspan="5"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Aprobado por: </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">Gerente Corporativo de Operaciones I.</span></td>
        <td class="column6 style14 s"><span style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Página </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">1 de 1</span></td>
      </tr>
      <tr class="row5">
        <td class="column0 style15 null"></td>
        <td class="column1 style15 null"></td>
        <td class="column2 style15 null"></td>
        <td class="column3 style15 null"></td>
        <td class="column4 style15 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr>
      <tr class="row6">
        <td class="column0 style15 null"></td>
        <td class="column1 style15 null"></td>
        <td class="column2 style15 null"></td>
        <td class="column3 style15 null"></td>
        <td class="column4 style15 null"></td>
        <td class="column5 style15 s">FECHA</td>
        <td class="column6 style17 f">{{ date("d-m-Y", strtotime($devolucion_guardada['fecha'])) }}</td>
      </tr>
      <tr class="row7">
        <td class="column0 style18 s">EMPRESA</td>
        <td class="column1 style19 s style19" colspan="3">{{$devolucion_guardada['empresa']['name']}}</td>
        <td class="column4 style20 null"></td>
        <td class="column5 style15 null"></td>
        <td class="column6 style15 null"></td>
      </tr>
      <tr class="row8">
        <td class="column0 style18 s">SEDE DE TRABAJO</td>
        <td class="column1 style19 s style19" colspan="3">{{$devolucion_guardada['sede']['name']}}</td>
        <td class="column4 style20 null"></td>
        <td class="column5 style15 null"></td>
        <td class="column6 style15 null"></td>
      </tr>
      <tr class="row9">
        <td class="column0 style18 null"></td>
        <td class="column1 style20 null"></td>
        <td class="column2 style20 null"></td>
        <td class="column3 style20 null"></td>
        <td class="column4 style20 null"></td>
        <td class="column5 style15 null"></td>
        <td class="column6 style15 null"></td>
      </tr>
      <tr class="row10">
        <td class="column0 style18 s">USUARIO</td>
        <td class="column1 style19 s style19" colspan="6">{{$devolucion_guardada['personal']['name']}}</td>
      </tr>
      <tr class="row11">
        <td class="column0 style18 s">AREA</td>
        <td class="column1 style19 s style19" colspan="6">{{$devolucion_guardada['area']['name']}}</td>
      </tr>
      <tr class="row12">
        <td class="column0 style18 s">PUESTO </td>
        <td class="column1 style21 s style21" colspan="6">{{$devolucion_guardada['cargo']['name']}}</td>
      </tr>
      <tr class="row13">
        <td class="column0 style18 null"></td>
        <td class="column1 style20 null"></td>
        <td class="column2 style20 null"></td>
        <td class="column3 style20 null"></td>
        <td class="column4 style20 null"></td>
        <td class="column5 style18 null"></td>
        <td class="column6 style18 null"></td>
      </tr>
      <tr class="row14">
        <td class="column0 style18 s">RESPONSABLE</td>
        <td class="column1 style19 s style19" colspan="6">{{$devolucion_guardada['responsable']['name']}}</td>
      </tr>
      <tr class="row15">
        <td class="column0 style18 s">AREA</td>
        <td class="column1 style19 s style19" colspan="6">{{$devolucion_guardada['responsable_area']['name']}}</td>
      </tr>
      <tr class="row16">
        <td class="column0 style22 null"></td>
        <td class="column1 style22 null"></td>
        <td class="column2 style22 null"></td>
        <td class="column3 style22 null"></td>
        <td class="column4 style22 null"></td>
        <td class="column5 style23 null"></td>
        <td class="column6 style23 null"></td>
      </tr>
      <tr class="row17">
        <td class="column0 style24 s style25" colspan="7">Por medio de la presente acta,
          el USUARIO hace entrega de los EQUIPOS con sus
          respectivos accesorios detallados líneas abajo a la
          EMPRESA, por intermedio de su GESTOR. </td>      
        </tr>
      <tr class="row18">
        <td class="column0 style26 null"></td>
        <td class="column1 style27 null"></td>
        <td class="column2 style27 null"></td>
        <td class="column3 style27 null"></td>
        <td class="column4 style27 null"></td>
        <td class="column5 style27 null"></td>
        <td class="column6 style27 null"></td>
      </tr>
      <tr class="row19">
        <td class="column0 style28 s style28" colspan="2">Los Equipos Incluyen:</td>
        <td class="column2 style29 null"></td>
        <td class="column3 style16 null"></td>
        <td class="column4 style16 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr>
      <tr class="row20">
        <td class="column0 style30 s style32" colspan="2">EQUIPO(S)</td>
        <td class="column2 style30 s style32" colspan="2">CONDICION</td>
        <td class="column1 style30 s style32" colspan="2">ACCESORIOS</td>
        <td class="column5 style33 s">OBSERVACION</td>
      </tr>
        @foreach ($devolucion_guardada['activos_devueltos'] as $activo)

        <tr class="row21">
            <td class="column0 style34 null style34" colspan="2">{!!$activo['activo']['descripcion']!!}</td>
            <td class="column2 style34 null style34" colspan="2">{{ $activo['performance']['name'] }}</td>
            <td class="column1 style34 null style34" colspan="2">              
              @if (!empty($activo['accesorios']))
                {{ implode(', ', array_column($activo['accesorios'],'name')) }}
              @endif
            </td>
            <td class="column5 style34 null style34">{{ $activo['observaciones'] }}</td>
        </tr>
        @endforeach

      <tr class="row26">
        <td class="column0 style23 null"></td>
        <td class="column1 style23 null"></td>
        <td class="column2 style23 null"></td>
        <td class="column3 style23 null"></td>
        <td class="column4 style23 null"></td>
        <td class="column5 style23 null"></td>
        <td class="column6 style23 null"></td>
      </tr>
      <tr class="row27">
        <td class="column0 style37 s style38" colspan="7">Las computadoras portátiles son propiedad de la empresa.El monto por reposición para el EQUIPO se consultará al área de TI, el mismo que será asumido en su totalidad por el USUARIO  en  caso  de  daño,  pérdida  o  robo  en  cualquiera  de  sus  modalidades  previstas  dentro  o  fuera  de  las instalaciones de la EMPRESA de acuerdo con lo mencionado en las políticas de gestión de activos de la empresa.</td>
      </tr>
      <tr class="row28">
        <td class="column0 style37 s style38" colspan="7">Por tanto, EL USUARIO se obliga a usar de manera responsable las condiciones de trabajo que le está entregando en este acto. De comprobarse el uso de estos sin sustento debido, diligencia debida y/o con mala fe por parte de EL USUARIO, entonces AUTORIZA a LA EMPRESA a realizar los descuentos pertinentes sobre sus remuneraciones, beneficios sociales y liquidación al cese hasta reponer el valor total de la condición y/o condiciones de trabajo que se le hace entrega en este acto.</td>
      </tr>
      <tr class="row29" rowspan="4">
        <td class="column0 style39 null" colspan="3" rowspan="4">
            {{-- <canvas id="firma_responsable"></canvas> --}}
            <img 
            {{-- style="border: 0;"  --}}
            style="border: 0; max-width: 100%; height: auto;" 
            src="{{  $firma_responsable }}" 
            alt="Firma del responsable" id="firma_responsable" 
            >
            {{-- <a class="btn btn-sm btn-default" id="btnGenerarDocumento">OK</a> --}}
            <br>
            <br>
        </td>
        <td class="column3 style39 null" rowspan="4"></td>
        <td class="column4 style39 null" colspan="3" rowspan="4"  >
            {{-- <canvas id="firma_personal"></canvas>   --}}
            <img 
            {{-- style="border: 0;"  --}}
            style="border: 0; max-width: 100%; height: auto;" 
            src="{{ $firma_personal }}" 
            alt="Firma del usuario" id="firma_personal" 
            style="{ max-width: 25%; height: auto; }"
            >
            {{-- <a class="btn btn-sm btn-default" id="btnLimpiarFirmaPersonal">Limpiar</a> --}}
            {{-- <a class="btn btn-sm btn-default" id="btnGenerarDocumento">OK</a> --}}
            <br>
            <br>
        </td>
      </tr>
      <tr class="row30">
        <td class="column0 style16 null"></td>
        <td class="column1 style16 null"></td>
        <td class="column2 style16 null"></td>
        <td class="column3 style16 null"></td>
        <td class="column4 style16 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr>
      <tr class="row31">
        <td class="column0 style16 null"></td>
        <td class="column1 style16 null"></td>
        <td class="column2 style16 null"></td>
        <td class="column3 style16 null"></td>
        <td class="column4 style16 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr>
      <tr class="row32">
        <td class="column0 style16 null"></td>
        <td class="column1 style16 null"></td>
        <td class="column2 style39 null"></td>
        <td class="column3 style16 null"></td>
        <td class="column4 style16 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr>
      <tr class="row33">
        <td class="column0 style40 s style40" colspan="3">{{ $devolucion_guardada['responsable']['name'] }}</td>
        <td class="column3 style41 null"></td>
        <td class="column4 style42 f style42" colspan="3">{{ $devolucion_guardada['personal']['name'] }}</td>
      </tr>
      <tr class="row34">
        <td class="column0 style43 s style43" colspan="3">{{ $devolucion_guardada['responsable_cargo']['name'] }}</td>
        <td class="column3 style16 null"></td>
        <td class="column4 style18 s">DNI:</td>
        <td class="column5 style44 n style45" colspan="2">{{ $devolucion_guardada['personal']['dni'] }}</td>
      </tr>
      <tr class="row35">
        <td class="column0 style16 null"></td>
        <td class="column1 style16 null"></td>
        <td class="column2 style16 null"></td>
        <td class="column3 style16 null"></td>
        <td class="column4 style16 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr>
      <tr class="row36">
        <td class="column0 style46 s style47" colspan="7">Prohibida su copia total o parcial de este documento sin la autorización de la Gerencia de Los Olivos de Villacurí S.A.C</td>
      </tr>
      {{-- <tr class="row37">
        <td class="column0 style16 null"></td>
        <td class="column1 style16 null"></td>
        <td class="column2 style16 null"></td>
        <td class="column3 style16 null"></td>
        <td class="column4 style16 null"></td>
        <td class="column5 style16 null"></td>
        <td class="column6 style16 null"></td>
      </tr> --}}
    </tbody>
</table>
</body>
</html>


