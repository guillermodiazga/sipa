<!DOCTYPE html>
<html>
    <head>
    <style type="text/css">
    body{
        background-color: #ffffff;
    }
    </style>
        <script Language="javascript">

            function exportToExcel() {
                var oExcel = new ActiveXObject("Excel.Application");
                var oBook = oExcel.Workbooks.Add;
                var oSheet = oBook.Worksheets(1);

                if(!oExcel){
                    alert("No esta instalado Excel");
                    return;
                }

                if(!tablaHTML){
                    alert("No esta definida la tabla");
                    return;
                }

                for (var y = 0; y < tablaHTML.rows.length; y++)
                // tablaHTML is the table where the content to be exported is
                {
                    for (var x = 0; x < tablaHTML.rows(y).cells.length; x++) {
                        oSheet.Cells(y + 1, x + 1) = tablaHTML.rows(y).cells(x).innerText;
                    }
                }
                oExcel.Visible = true;
                oExcel.UserControl = true;
            }

            
        </script>

        <title>SIPA: Abrir Excel</title>
    </head>
    <body>
        <font face="verdana"><img src="images/cargando.gif">Abriendo Excel...</font><br><br><br><br><br><br><br>
    <div id="tabla">
        
    </div>

    </body>
</html>