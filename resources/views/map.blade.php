<!DOCTYPE html>
<html>
<head>
    <title>JS CSV</title>
</head>
<body>
<button id="b">export to CSV</button>
<script type="text/javascript">
    function exportToCsv() {
        var myCsv = "Col1,Col2,Col3\nval1,val2,val3";

        window.open('data:text/csv;charset=utf-8,' + escape(myCsv));
    }

    var button = document.getElementById('b');
    button.addEventListener('click', exportToCsv);
</script>
</body>
</html>
