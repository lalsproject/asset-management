
<html>
<head>
    <style>

        @page {
            size: 85mm 23mm landscape ;
            height: 23mm;
            width: 85mm;
            margin: 0;
        }
        html {
            width: 85mm landscape ;
            height: 23mm;
            margin: 0;
        }

        @media print {
            .page {
                size: 85mm 23mm landscape ;
                height: 23mm;
                width: 85mm;
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            .page-break {
                page-break-after: always;
            }
        }
        body{
        -webkit-print-color-adjust:exact !important;
        print-color-adjust:exact !important;
        }

    </style>


</head>
<body   onload="fLoad();"  >

<div class="page">
    <label> <<< TEST CETAK >>></label>
</div> <!-- div page -->

<div class="page">
    <label> <<< TEST DUA >>></label>
</div> <!-- div page -->

</boby>
</html>

<script type='text/javascript'>
    function fLoad() {
       window.print();
    }

</script>


