<!DOCTYPE html>
<html lang="en">

    <head>
        <title> @yield('title') </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            /*@page { margin-top:80px;margin-bottom:20px;}*/
            .custom {
                
                font-size: 12px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                page-break-inside: avoid;
            }

            table, th, td {
                border: 1px solid black;
                font-size: 12px;
            }
            /* .no-border-tbl th, td {
                border: 0px solid black;
            } */
            th{
                text-align: center;
            }
            tr{
                page-break-inside:avoid; page-break-after:none;

            }
            span.tab1{
                padding-right: 20px;
            }

            thead:before, thead:after { display: none; }
            tbody:before, tbody:after { display: none; }

            .bold-text
            {
                font-weight: bold;
            }

            .italic-text
            {
                font-style: italic;
            }

            .red
            {
                color: red;
            }

            .underline{
                text-decoration: underline;
            }

            .padd-table-all
            {
                padding:5px;
            }

            .padd-table
            {
                padding-top:3px;
                padding-bottom:3px;
            }

            .padd-left-table
            {
                padding-left:3px;
            }

            .padd-tab
            {
                padding-left:10px;
            }
            .padd-tab-1
            {
                padding-left:20px;
            }
            .padd-tab-2
            {
                padding-left:30px;
            }
            .padd-tab-3
            {
                padding-left:40px;
            }
            .padd-tab-4
            {
                padding-left:50px;
            }
            .padd-tab-5
            {
                padding-left:60px;
            }

            .padd-tab-6
            {
                padding-left:70px;
            }

            .padd-tab-7
            {
                padding-left:80px;
            }

            .center
            {
                text-align: center;
            }

            .no-border
            {
                border:0px solid white;
            }

            .border
            {
                border:1px solid black;
            }

            .border-dash
            {
                border-bottom:1px black;
                border-top:1px black;
                border-style: dashed;
            }

            .border-right
            {
                border-right:1px solid black;
            }

            .border-bottom
            {
                border-bottom: 1px solid black;
            }

            .page-break {
                page-break-after: always;
            }

            /*Class Div Table*/
            .table {
                display:table;
                margin: 2px;
                /*width: 100%;*/
            }
            .header {
                display:table-header-group;
                font-weight:bold;
            }
            .row {
                display:table-row;
            }
            .rowGroup {
                display:table-row-group;
            }
            .cell {
                display:table-cell;
                /*width:25%;*/
            }

            @font-face {
                font-family: Tahoma, Verdana, Segoe, sans-serif;
            }

            body {
                font-family: Tahoma, Verdana, Segoe, sans-serif;
                /*border-left:1px solid black;
                border-right:1px solid black;*/
                /*margin-left:-6px;
                margin-right:6px;*/
            }
            footer {
            font-size: 15px;
            }
            /*footer {
                position: fixed; bottom: -38px;
                background-color: white;
                text-align: right;
                height: 60px;
                border-top:1px solid black;
                font-size: 11px;
                margin-top:3px;
            }

            .pagenum:after { content:' ' counter(page); }*/

            .footPage:after{
                content:' '  counter(page);
            }

            @page{
                counter-increment: page;
            }

        </style>
        @stack('styles')
    </head>
    <body>

    @yield('content')

    </body>
</html>
