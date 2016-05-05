<?php
/*
 * Seccion calculadora de matrices
 */

require('codigo/base.php');

if (!$modoXuletas) {
    header('Location: /');
    exit;
}
$cabeceraHtml->Titulo = 'Aplicación online para calcular determinantes, rangos o soluciones de Cramer';
$cabeceraHtml->Descripcion = 'Calculadora de matrices, determinantes, rangos y soluciones de cramer';
$cabeceraHtml->PalabrasClave[] = 'calculadora de matrices online, resolver matrices paso a paso, calcular cramer, calcular inversa de matriz, matriz transpuesta, determinantes';
$cabeceraHtml->UrlCanonica = Url('matrices/');

include('plantillas/cabecera.php');

if (isset($_REQUEST['filas']) && is_numeric($_REQUEST['filas']))
    $filas = $_REQUEST['filas'];
else
    $filas = 3;

if (isset($_REQUEST['columnas']) && is_numeric($_REQUEST['columnas']))
    $columnas = $_REQUEST['columnas'];
else
    $columnas = 3;

if (isset($_REQUEST['ayuda'])) {
    ?>
Establece el tama&ntilde;o y los datos de la matriz y pulsa analizar para obtener los resultados. Según el tama&ntilde;o de la matriz, te devolverá unos datos u otros:
    <br>
    <ul>
        <li>Si la matriz es cuadrada (mismas filas y columnas), te devolverá el determinante, el rango, la inversa y la
            traspuesta.
        </li>
        <li>Si la matriz tiene una columna mas que filas, se entenderá que la matriz es la matriz ampliada de un sistema
            de ecuaciones, y te devolverá el resultado de aplicar la regla de Cramer a esa matriz, el rango y la
            traspuesta.
        </li>
        <li>Si la matriz no cumple ninguna de las dos, te devolverá el rango y la traspuesta.</li>
    </ul>

    Acepta el empleo de matrices con incógnitas y paramétros para algunos de los análisis, como los determinantes o Cramer.
    <br><br>
    <h2>Algunos ejemplos</h2>

    <a href="/matrices.php?filas=3&columnas=4&0,0=3&0,1=5&0,2=2&0,3=1&1,0=1&1,1=-1&1,2=-1&1,3=0&2,0=2&2,1=3&2,2=4&2,3=2">Matriz
        de 3x4 resoluble por Cramer</a><br>
    <a href="/matrices.php?filas=4&columnas=4&0,0=-2&0,1=0&0,2=1&0,3=2&1,0=1&1,1=2&1,2=1&1,3=1&2,0=3&2,1=1&2,2=2&2,3=0&3,0=-5&3,1=-1&3,2=3&3,3=4">Matriz
        de 4x4 que permite calcular determinante, rango e inversa.</a>
    <?php
    exit;
}
if (isset($_REQUEST['descargar'])) {
    highlight_file(__FILE__);
    exit;
}
echo Publicidad::Popup(); //Mostrar Popup
?>
<h1>Cálculo de matrices paso a paso</h1>
<p>¿Necesitas ayuda? Pulsa <a href="/matrices.php?ayuda" onclick="return ! window.open(this.href);">aquí</a></p>
<p><a href="/matrices.php?descargar" rel="nofollow">Descarga el codigo fuente</a></p>
<form method="POST" action="/matrices.php">
    Tama&ntilde;o de la matriz:<br>
    <label>Filas:&nbsp;<input type="text" name="filas" value="<?php echo $filas?>"></label>
    <label>Columnas:&nbsp;<input type="text" name="columnas" value="<?php echo $columnas?>"></label>
    <br><br>
    <table>
    <?php
            for ($fila = 0; $fila < $filas; $fila++) {
        echo '<tr>';
        for ($columna = 0; $columna < $columnas; $columna++) {
            $nombre = "$fila,$columna";
            if (isset($_REQUEST[$nombre]))
                $valor = $_REQUEST[$nombre];
            else $valor = '';
            echo "<td><input type='text' size='3' name='$fila,$columna' value='$valor'></td>";
        }
        echo '</tr>';
    }
    ?>
        </table>
    <br>
    <input type="submit" value="Analizar">

</form>
</body></html>

<?php

if (!empty($_REQUEST)) //Procesar datos
{
    $array = array();
    for ($fila = 0; $fila < $filas; $fila++) {
        for ($columna = 0; $columna < $columnas; $columna++) {
            $nombre = "$fila,$columna";

            if (isset($_REQUEST[$nombre]) && strlen($_REQUEST[$nombre]) > 0)
                $array[$fila][$columna] = new Monomio($_REQUEST[$nombre]);
            else
                $array[$fila][$columna] = null;
        }
    }

    $matriz = new Matriz($array);
    if ($matriz->Filas == 0) {
        include('plantillas/pie.php');
        exit;
    }

    echo "<h2>Análisis de la matriz</h2>" . $matriz->Dibujar() . "<br>";

    if ($matriz->EsCuadradada()) {
        $determinante = $matriz->Determinante();
        echo "Matriz cuadradada de <strong>{$matriz->Filas}x{$matriz->Columnas}</strong> ó <strong>Matriz de orden {$matriz->Filas}</strong><br>";
        echo "Determinante: <strong>" . $determinante->Mostrar(false) . "</strong>";
    }
    else
        echo "Matriz de <strong>{$matriz->Filas}x{$matriz->Columnas}</strong><br>";

    if ($matriz->EsCuadradada())
        $matriz->DesarrolloDeterminante();

    $matriz->DesarrolloCramer();
    $matriz->DesarrolloRango();
    $matriz->DesarrolloInversa();

    $traspuesta = $matriz->Traspuesta();
    echo "<h2>Matriz traspuesta</h2>Se obtiene al intercambiar las filas de la matriz por columnas. El resultado es:<br><br>" . $traspuesta->Dibujar();


    include('plantillas/pie.php');
    exit;
}
else {
    include('plantillas/pie.php');
    exit;
}

class Matriz {
    public $Array;
    public $Filas;
    public $Columnas;

    function Matriz($array, $ajustar = true) {
        $this->Array = $array;
        $this->Filas = count($array);

        $keys = array_keys($array);
        $this->Columnas = count($array[$keys[0]]);

        if ($ajustar)
            $this->Ajustar();
    }

    function EsCuadradada() {
        return $this->Filas == $this->Columnas;
    }

    /**
     * Desarrolla el calculo de un determinante
     *
     * @return Polinomio
     */
    function DesarrolloDeterminante() {
        if ($this->EsCuadradada()) {
            $desarollo = '<h2>Cálculo del determinante</h2>';


            $resultado = $this->Determinante();
            if ($this->Filas == 1) {
                $desarollo .= 'Al ser una matriz de 1x1, el determinante es el único valor de la matriz.';
            }
            elseif ($this->Filas == 2) {
                $desarollo .= 'Al ser una matriz de 2x2, el determinante que se obtiene es:<br><br>';
                $this->ModoMultiplicacion = true;
                $desarollo .= sprintf("%s · %s - %s · %s", $this->MostrarNumero(0, 0), $this->MostrarNumero(1, 1), $this->MostrarNumero(0, 1), $this->MostrarNumero(1, 0));

                $this->ModoMultiplicacion = false;
            }
            elseif ($this->Filas == 3) {
                $desarollo .= 'Al ser una matriz de 3x3, el determinante se obtiene mediante la regla de Sarrus:<br><br>';

                $this->ModoMultiplicacion = true;
                $desarollo .= "[" . $this->MostrarNumero(0, 0) . "·" . $this->MostrarNumero(1, 1) . "·" . $this->MostrarNumero(2, 2) . " + " . $this->MostrarNumero(1, 0) . "·" . $this->MostrarNumero(2, 1) . "·" . $this->MostrarNumero(0, 2) . " + " . $this->MostrarNumero(2, 0) . "·" . $this->MostrarNumero(0, 1) . "·" . $this->MostrarNumero(1, 2) . "]
            - [" . $this->MostrarNumero(0, 2) . "·" . $this->MostrarNumero(1, 1) . "·" . $this->MostrarNumero(2, 0) . " - " . $this->MostrarNumero(1, 2) . "·" . $this->MostrarNumero(2, 1) . "·" . $this->MostrarNumero(0, 0) . " - " . $this->MostrarNumero(2, 2) . "·" . $this->MostrarNumero(0, 1) . "·" . $this->MostrarNumero(1, 0) . "]=<br>";
                $this->ModoMultiplicacion = false;

                $primer = Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[0][0], $this->Array[1][1]), $this->Array[2][2]);
                $segundo = Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[1][0], $this->Array[2][1]), $this->Array[0][2]);
                $tercero = Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[2][0], $this->Array[0][1]), $this->Array[1][2]);

                $cuarto = Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[0][2], $this->Array[1][1]), $this->Array[2][0]);
                $quinto = Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[1][2], $this->Array[2][1]), $this->Array[0][0]);
                $sexto = Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[2][2], $this->Array[0][1]), $this->Array[1][0]);

                $sumas = Polinomio::Sumar(Polinomio::Sumar($primer, $segundo), $tercero);
                $restas = Polinomio::Sumar(Polinomio::Sumar($cuarto, $quinto), $sexto);

                $desarollo .= sprintf('[%s %s %s] - [%s %s %s] = ', $this->MostrarNumero($primer), $this->MostrarNumero($segundo), $this->MostrarNumero($tercero), $this->MostrarNumero($cuarto), $this->MostrarNumero($quinto), $this->MostrarNumero($sexto));
                $desarollo .= $this->MostrarNumero($sumas) . " " . $this->MostrarNumero($restas) . " = ";

                $desarollo .= $resultado->Mostrar();
            }
            else {
                $desarollo .= 'Se calcula el determinante por recurrencia a través de sus menores y adjuntos (por la primera columna):<br>';

                $desarollo .= '<table><tr>';
                $desarrolloAdjunto = '';
                for ($fila = 0; $fila < $this->Filas; $fila++) {
                    $menor = array();
                    for ($fila2 = 0; $fila2 < $this->Filas; $fila2++) {
                        if ($fila == $fila2)
                            continue;
                        else
                            $menor[] = array_slice($this->Array[$fila2], 1);
                    }
                    $matrixMenor = new Matriz($menor, false);


                    $multiplicador = Polinomio::Multiplicar(new Monomio(pow(-1, $fila)), $this->Array[$fila][0]);
                    if ($multiplicador->Numero() && $multiplicador->Valor() == 0)
                        continue;
                    $determinanteMenor = $matrixMenor->Determinante();

                    $desarollo .= "<td>" . $multiplicador->Mostrar() . " · </td><td>" . $matrixMenor->Dibujar() . "</td>";

                    $desarrolloAdjunto .= "+ " . $multiplicador->Mostrar(false, true) . ' · ' . $determinanteMenor->Mostrar(false, true);
                }
                $desarollo .= '<td>=</td></tr></table><br>';
                $desarollo .= $desarrolloAdjunto . ' = ' . $resultado->Mostrar();
            }

            $desarollo .= "<h3>Resultado: " . $resultado->Mostrar(false) . "</h3>";

            echo $desarollo;

            return $resultado;
        }
        return null;
    }

    /**
     * Desarrolla el calculo del rango de la matriz
     *
     * @return int
     */
    function DesarrolloRango() {
        echo '<h2>Cálculo del rango por determinantes</h2>';

        $rango = 0;
        echo 'Se van buscando menores que sean iguales a 0 para hallar el rango:<br><br>';
        for ($contador = 0; $contador < min($this->Filas, $this->Columnas); $contador++) {
            //Hallar un menor de determinante distinto de cero

            $menor = array();
            for ($fila = 0; $fila < $this->Filas; $fila++) {
                $distintoCero = false;
                for ($columna = 0; $columna < $this->Columnas; $columna++) {
                    if ($fila + $contador + 1 <= $this->Filas && $columna + $contador + 1 <= $this->Columnas) {

                        $menor = $this->ObtenerMenor($fila, $columna, $contador + 1);
                        $determinante = $menor->Determinante();

                        $nulo = ($determinante->Numero() && $determinante->Valor() == 0);
                        echo "<table><tr><td>Menor de orden " . ($contador + 1) . "</td><td>" . $menor->Dibujar() . "</td><td> Determinante = " . $determinante->Mostrar(false) .
                                ($nulo ? ' = 0 &rarr; Buscar menores distintos de cero, si no hay, el rango es ' . $rango : ' != 0 &rarr; Rango &ge; ' . ($contador + 1)) . "</td></tr></table>";

                        if ($nulo) {
                            $distintoCero = false;
                        }
                        else //Pasar a un orden mayor
                        {
                            $rango = $contador + 1;
                            $distintoCero = true;
                            break;
                        }

                    }
                }

                if ($distintoCero)
                    break;
            }
        }

        echo "<br>Por tanto, el rango de la matriz es: <strong>$rango</strong>";

        return $rango;
    }


    /**
     * Obtiene un menor de orden $orden empezando desde una fila y columna especificadas
     *
     * @return Matriz
     */
    function ObtenerMenor($filaInicio, $columnaInicio, $orden) {
        $menor = array();
        $contadorFila = 0;
        for ($fila = $filaInicio; $fila < $this->Filas; $fila++) {
            $contadorColumna = 0;
            for ($columna = $columnaInicio; $columna < $this->Columnas; $columna++) {
                $menor[$contadorFila][$contadorColumna] = $this->Array[$fila][$columna];

                if ($contadorColumna + 1 >= $orden)
                    break;
                else
                    $contadorColumna++;
            }

            if ($contadorFila + 1 >= $orden)
                break;
            else
                $contadorFila++;
        }
        return new Matriz($menor, false);
    }

    /**
     * Desarrolla el calculo de la regla de cramer en la matriz actual
     *
     * @return Polinomio
     */
    function DesarrolloCramer() {
        if ($this->Filas + 1 == $this->Columnas) {
            echo '<h2>Regla de Cramer</h2>';

            //Obtener la matriz del sistema
            $array = array();
            for ($fila = 0; $fila < $this->Filas; $fila++) {
                $array[$fila] = array_slice($this->Array[$fila], 0, $this->Columnas - 1);
            }
            $matrizSistema = new Matriz($array, false);
            $determinanteSistema = $matrizSistema->Determinante();
            if ($determinanteSistema->Numero() && $determinanteSistema->Valor() == 0) {
                echo "El determinante del sistema es 0, por tanto, no existe solución para la ecuación";
                return;
            }
            echo "La matriz asociada al sistema es: " . $matrizSistema->Dibujar() . "<br>Y su determinante: " . $determinanteSistema->Mostrar(false) . "<br><br>
            Ahora, para cada incógnita, se crea una matriz obtenida al cambiar en la matriz del sistema la columna de esa incógnita por la de términos independientes y se divide el determinante de esa matriz por el determinante de la matriz del sistema:<br><br>";


            for ($incognita = 0; $incognita < $this->Columnas - 1; $incognita++) {
                //Obtener la matriz con la columna actual cambiada por la columna final de la matriz
                $array = array();
                for ($fila = 0; $fila < $matrizSistema->Filas; $fila++) {
                    for ($columna = 0; $columna < $matrizSistema->Columnas; $columna++) {
                        if ($columna == $incognita) //Cambiar esta columna por la ultima
                            $array[$fila][$columna] = $this->Array[$fila][$this->Columnas - 1];
                        else
                            $array[$fila][$columna] = $matrizSistema->Array[$fila][$columna];
                    }
                }

                $matrizIncognita = new Matriz($array, false);
                $determinanteIncognita = $matrizIncognita->Determinante();

                if ($determinanteSistema->Numero() && $determinanteIncognita->Numero())
                    $resultado = $determinanteIncognita->Valor() / $determinanteSistema->Valor();

                echo '<table>
  <tr>
  <td rowspan="2"><strong>Incógnita ' . ($incognita + 1) . '</strong>: </td>
    <td>1 / ' . $determinanteSistema->Mostrar(true, true) . ' · </td>
     <td rowspan="2">' . $matrizIncognita->Dibujar() . '</td>
       <td> = ' . $determinanteIncognita->Mostrar(true, true) . ' / ' . $determinanteSistema->Mostrar(true, true) . (isset($resultado) ? ' = <strong>' . $resultado . '</strong>' : '') . '</td>
  </tr>
</table>';
            }
        }
    }

    /**
     * Calcula el determinante de la matriz
     *
     * @return Polinomio
     */
    function Determinante() {
        $cuadrada = $this->EsCuadradada();
        if ($cuadrada) {
            if ($this->Filas == 1) {
                $res = new Polinomio();
                $res->Monomios[] = $this->Array[0][0];
                return $res;
            }
            elseif ($this->Filas == 2) {
                return Polinomio::Restar(Polinomio::Multiplicar($this->Array[0][0], $this->Array[1][1]), Polinomio::Multiplicar($this->Array[0][1], $this->Array[1][0]));
            }
            elseif ($this->Filas == 3) {
                $sumas = Polinomio::Sumar(Polinomio::Sumar(Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[0][0], $this->Array[1][1]), $this->Array[2][2]),
                    Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[1][0], $this->Array[2][1]), $this->Array[0][2])),
                    Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[2][0], $this->Array[0][1]), $this->Array[1][2]));

                $restas = Polinomio::Sumar(Polinomio::Sumar(Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[0][2], $this->Array[1][1]), $this->Array[2][0]),
                    Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[1][2], $this->Array[2][1]), $this->Array[0][0])),
                    Polinomio::Multiplicar(Polinomio::Multiplicar($this->Array[2][2], $this->Array[0][1]), $this->Array[1][0]));

                return Polinomio::Restar($sumas, $restas);
            }
            else {
                $resultado = null;
                for ($fila = 0; $fila < $this->Filas; $fila++) {
                    $menor = array();
                    for ($fila2 = 0; $fila2 < $this->Filas; $fila2++) {
                        if ($fila == $fila2)
                            continue;
                        else
                            $menor[] = array_slice($this->Array[$fila2], 1);
                    }
                    $matrixMenor = new Matriz($menor);

                    if (isset($resultado)) {
                        $adjunto = Polinomio::Multiplicar(Polinomio::Multiplicar($matrixMenor->Determinante(), $this->Array[$fila][0]),
                        new Monomio(pow(-1, $fila)));
                        $resultado = Polinomio::Sumar($resultado, $adjunto);
                    }
                    else {
                        $resultado = Polinomio::Multiplicar(Polinomio::Multiplicar($matrixMenor->Determinante(), $this->Array[$fila][0]),
                        new Monomio(pow(-1, $fila)));
                    }
                }
                return $resultado;
            }
        }
        return null;
    }

    /**
     * Obtiene la traspuesta de la matriz actual
     *
     * @return Matriz
     *
     */
    function Traspuesta() {
        $array = array();
        for ($fila = 0; $fila < $this->Filas; $fila++) {
            for ($columna = 0; $columna < $this->Columnas; $columna++) {
                $array[$columna][$fila] = $this->Array[$fila][$columna];
            }
        }

        return new Matriz($array, false);
    }

    /**
     * Desarrolla el calculo de la matriz inversa de la matriz actual
     *
     * @return Matriz
     *
     */
    function DesarrolloInversa() {
        if ($this->EsCuadradada()) {
            $traspuesta = $this->Traspuesta();

            echo "<h2>Matriz inversa</h2>Primero se obtiene la matriz traspuesta: " . $traspuesta->Dibujar() . "<br>
        Y se crea la matriz con los adjuntos de la traspuesta:";

            //Obtener la matriz de adjuntos
            $arrayAdjuntos = array();
            echo '<table>';
            for ($fila = 0; $fila < $this->Filas; $fila++) {
                echo '<tr>';
                for ($columna = 0; $columna < $this->Columnas; $columna++) {
                    $menor = $traspuesta->Menor($fila, $columna);
                    echo '<td>' . $menor->Dibujar() . '</td>';

                    $determinanteMenor = $menor->Determinante();
                    if ($determinanteMenor != null) {
                        echo '<td> = ' . $determinanteMenor->Mostrar() . '</td>';
                    }

                    $arrayAdjuntos[$fila][$columna] = Polinomio::Multiplicar($determinanteMenor, new Monomio(pow(-1, $fila + $columna)));
                }
                echo '</tr>';
            }
            echo '</table>';

            $matrizAdjuntos = new Matriz($arrayAdjuntos, false);

            $determinante = $this->Determinante();
            if ($determinante->Numero() && $determinante->Valor() != 0)
                $inverso = Polinomio::Inverso($determinante);
            else {
                echo "El determinante de la matriz del sistema es 0, por tanto, no se puede calcular la inversa.";
                return;
            }

            echo "<br>La matriz de adjuntos obtenida es: " . $matrizAdjuntos->Dibujar() . "<br><br>
        El resultado es la multiplicación entre la matriz de adjuntos y 1 / " . $determinante->Mostrar(false) . " = " . $inverso->Mostrar(false);

            if ($inverso->Numero() || count($inverso->Monomios) == 1) {
                $resultado = $matrizAdjuntos->Multiplicar($inverso->Monomios[0]);
                echo "<br><h3>Resultado</h3>" . $resultado->Dibujar();
            }
        }
    }

    /**
     * Multiplica la matriz por un numero real
     * @param Monomio $numero
     * @return Matriz
     */
    function Multiplicar($numero) {
        $array = array();
        for ($fila = 0; $fila < $this->Filas; $fila++) {
            for ($columna = 0; $columna < $this->Columnas; $columna++) {
                $array[$fila][$columna] = Polinomio::Multiplicar($this->Array[$fila][$columna], $numero);
            }
        }
        return new Matriz($array, false);
    }


    /**
     * Obtiene el menor de la matriz correspondiente a la fila y la columna especificada
     *@return Matriz
     */
    function Menor($fila, $columna) {
        $menor = array();

        $filaArray = 0;
        for ($filaR = 0; $filaR < $this->Filas; $filaR++) {
            if ($filaR == $fila)
                continue;

            for ($columnaR = 0; $columnaR < $this->Columnas; $columnaR++) {
                if ($columnaR == $columna)
                    continue;
                else
                    $menor[$filaArray][] = $this->Array[$filaR][$columnaR];
            }
            $filaArray++;
        }

        return new Matriz($menor, false);
    }

    /**
     * Obtiene el adjunto de la matriz correspondiente a la fila y la columna especificada
     *@return Matriz
     */
    function Adjunto($fila, $columna) {
        $menor = $this->Menor($fila, $columna);
        return Polinomio::Multiplicar($menor->Determinante(),
        new Monomio(pow(-1, $fila + $columna)));
    }


    function MostrarNumero($fila, $columna = null) {
        if (isset($columna))
            $numero = $this->Array[$fila][$columna];
        else
            $numero = $fila;

        if ($this->ModoMultiplicacion)
            return $numero->Mostrar(false, true);
        else
            return $numero->Mostrar();
    }

    function Dibujar() {
        $link = "filas=$this->Filas&columnas=$this->Columnas";
        for ($fila = 0; $fila < $this->Filas; $fila++) {
            for ($columna = 0; $columna < $this->Columnas; $columna++) {
                if (!empty($this->Array[$fila][$columna])) {
                    $link .= "&$fila,$columna=" . $this->Array[$fila][$columna]->Mostrar(false, false);
                }
            }
        }
        $texto = "<a href='/matrices.php?$link'>";

        $texto .= "
        <table border='1' style='text-align:center;vertical-align:middle;'>";

        for ($fila = 0; $fila < $this->Filas; $fila++) {
            $texto .= "<tr style='height:30px;'>";
            for ($columna = 0; $columna < $this->Columnas; $columna++) {
                if (isset($this->Array[$fila][$columna]))
                    $valor = $this->Array[$fila][$columna];

                if (!empty($valor))
                    $texto .= "<td style='width:30px;'>" . $valor->Mostrar(false, false) . "</td>";
            }
            $texto .= '</tr>';
        }
        $texto .= '</table></a>';

        return $texto;
    }

    function Ajustar() {
        for ($fila = $this->Filas; $fila >= 0; $fila--) {
            $filaVacia = true;
            for ($columna = 0; $columna < $this->Columnas; $columna++) {
                if (isset($this->Array[$fila][$columna])) {
                    $filaVacia = false;
                    break;
                }
            }
            if ($filaVacia)
                unset($this->Array[$fila]);
            else
                break;
        }

        for ($columna = $this->Columnas - 1; $columna >= 0; $columna--) {
            $columnaVacia = true;
            for ($fila = 0; $fila < $this->Filas; $fila++) {
                if (isset($this->Array[$fila][$columna])) {
                    $columnaVacia = false;
                    break;
                }
            }
            if ($columnaVacia) {
                for ($fila = 0; $fila < $this->Filas; $fila++) {
                    unset($this->Array[$fila][$columna]);
                }
            }
            else
                break;
        }

        $this->Filas = count($this->Array);
        $keys = array_keys($this->Array);
        $this->Columnas = count($this->Array[$keys[0]]);


        //Rellenar valores vacios
        for ($fila = 0; $fila < $this->Filas; $fila++) {
            for ($columna = 0; $columna < $this->Columnas; $columna++) {
                if (!isset($this->Array[$fila][$columna])) {
                    $this->Array[$fila][$columna] = new Monomio(0);
                }
            }
        }
    }
}

class Monomio {
    function Monomio($num) {
        if (isset($num)) {
            if (is_numeric($num)) {
                $this->TerminoIndependiente = true;
                $this->Coeficiente = $num;
            }
            else {
                $this->TerminoIndependiente = false;

                //Cadena del tipo 2x^2
                $resultados = array();

                $expresionRegular = '/([a-zA-Z])(?:\^(\d+))?/';
                if (preg_match($expresionRegular, $num, $resultados) != 0) {
                    $this->Incognita = $resultados[1];

                    if (!isset($resultados[2]))
                        $this->Grado = 1;
                    else
                        $this->Grado = $resultados[2];

                    $num = preg_replace($expresionRegular, '', $num);
                }
                if (preg_match('/[+-]?[\d.,]*/', $num, $resultados) != 0) {
                    if ($resultados[0] == '+' || strlen($resultados[0]) == 0) {
                        $this->Coeficiente = 1;
                    }
                    elseif ($resultados[0] == '-') {
                        $this->Coeficiente = -1;
                    }
                    else {
                        $this->Coeficiente = (int) $resultados[0];
                    }
                }

                $this->TerminoIndependiente = !isset($this->Incognita);
            }
        }
    }

    /**
     * Indica si el polinomio actual esta formado por un solo numero real
     *
     * @var bool
     */
    var $TerminoIndependiente;
    var $Incognita;
    var $Coeficiente;
    var $Grado;

    /**
     * Representa el monomio en forma de cadena
     *
     */
    function Mostrar($mostrarMas = true, $mostrarMenosSeparados = false) {
        $texto = '';
        if (isset($this->Coeficiente)) {
            if (isset($this->Incognita) && ($this->Coeficiente == 1 || $this->Coeficiente == -1)) {
                if ($this->Coeficiente > 0 && $mostrarMas)
                    $texto .= '+';
                elseif ($this->Coeficiente < 0)
                    $texto .= '-';
            }
            else {
                if ($this->Coeficiente == 0)
                    $texto .= $this->Coeficiente;
                elseif ($this->Coeficiente >= 0) {
                    if ($mostrarMas)
                        $texto .= '+' . $this->Coeficiente;
                    else
                        $texto .= $this->Coeficiente;
                }
                else {
                    if ($mostrarMenosSeparados)
                        $texto .= "($this->Coeficiente)";
                    else
                        $texto .= $this->Coeficiente;
                }
            }
        }
        if (isset($this->Incognita))
            $texto .= $this->Incognita;

        if (isset($this->Grado) && $this->Grado != 1)
            $texto .= "<sup>$this->Grado</sup>";

        return $texto;
    }
}

class Polinomio {
    function Polinomio() {
        $this->Monomios = array();
    }

    var $Monomios;

    /**
     * Representa el polinomio en forma de cadena
     *
     */
    function Mostrar($mostrarMas = true, $mostrarMenosSeparados = false) {
        $texto = '';
        if (isset($this->Monomios)) {
            $primero = true;
            foreach ($this->Monomios as $monomio) {
                if ($mostrarMas == false && $primero == false)
                    $mostrarMas = true;

                $texto .= $monomio->Mostrar($mostrarMas, $mostrarMenosSeparados);

                $primero = false;
            }
        }
        return $texto;
    }

    /**
     * Devuelve un valor que indica si este polinomio es un unico numero
     *
     */
    function Numero() {
        $keys = array_keys($this->Monomios);
        return (count($this->Monomios) == 1 && $this->Monomios[$keys[0]]->TerminoIndependiente);
    }


    /**
     * Devuelve el valor numerico de este polinomio si es un Numero
     *
     */
    function Valor() {
        $keys = array_keys($this->Monomios);
        return $this->Monomios[$keys[0]]->Coeficiente;
    }

    /**
     * Reduce los terminos semejantes de un polinomio
     *
     * @param Polinomio $polinomio1
     * @param Polinomio $polinomio2
     *
     * @return Polinomio
     */
    function Simplificar() {
        foreach ($this->Monomios as $clave1 => $monomio1) {
            foreach ($this->Monomios as $clave2 => $monomio2) {
                if ($clave1 == $clave2)
                    continue;

                if ($monomio1->TerminoIndependiente && $monomio2->TerminoIndependiente ||
                        ($monomio1->Incognita == $monomio2->Incognita && $monomio1->Grado == $monomio2->Grado)) {
                    $suma = new Monomio(null);
                    $suma->TerminoIndependiente = $monomio1->TerminoIndependiente;
                    $suma->Coeficiente = $monomio1->Coeficiente + $monomio2->Coeficiente;
                    $suma->Incognita = $monomio1->Incognita;
                    $suma->Grado = $monomio1->Grado;

                    unset($this->Monomios[$clave1]);
                    unset($this->Monomios[$clave2]);

                    if ($suma->Coeficiente != 0) {
                        $this->Monomios[] = $suma;
                    }
                    elseif (count($this->Monomios) == 0) {
                        $this->Monomios[] = new Monomio(0);
                    }
                    $this->Simplificar();
                    return;
                }
            }
        }
    }

    /**
     * Suma dos polinomios
     *
     * @param Polinomio $polinomio1
     * @param Polinomio $polinomio2
     */
    static function Sumar($polinomio1, $polinomio2) {
        $resultado = new Polinomio();

        if ($polinomio1 instanceof Monomio) {
            $polinomioT = new Polinomio();
            $polinomioT->Monomios[] = $polinomio1;
            $polinomio1 = $polinomioT;
        }

        if ($polinomio2 instanceof Monomio) {
            $polinomioT = new Polinomio();
            $polinomioT->Monomios[] = $polinomio2;
            $polinomio2 = $polinomioT;
        }
        $resultado->Monomios = array_merge($polinomio1->Monomios, $polinomio2->Monomios);

        $resultado->Simplificar();

        return $resultado;
    }


    /**
     * Resta dos polinomios
     *
     * @param Polinomio $polinomio1
     * @param Polinomio $polinomio2
     */
    static function Restar($polinomio1, $polinomio2) {
        foreach (array_keys($polinomio2->Monomios) as $clave) {
            $polinomio2->Monomios[$clave]->Coeficiente *= -1;
        }

        return Polinomio::Sumar($polinomio1, $polinomio2);
    }

    /**
     * Obtiene el inverso de este determinante
     * @return Polinomio
     */
    static function Inverso($polinomio) {
        $resultado = new Polinomio();
        foreach ($polinomio->Monomios as $monomio1) {
            $inverso = new Monomio(null);
            $inverso->Coeficiente = 1 / $monomio1->Coeficiente;
            $inverso->Grado = $monomio1->Grado;
            $inverso->Incognita = $monomio1->Incognita;
            $inverso->TerminoIndependiente = $monomio1->TerminoIndependiente;

            $resultado->Monomios[] = $inverso;
        }
        return $resultado;
    }

    /**
     * Multiplica un polinomio por un monomio
     *
     * @param Polinomio $polinomio1
     * @param Monomio $polinomio2
     * @return Polinomio
     */
    static function Multiplicar($polinomio, $monomio) {
        if ($polinomio instanceof Monomio) {
            $polinomio2 = new Polinomio();
            $polinomio2->Monomios[] = $polinomio;

            $polinomio = $polinomio2;
        }
        if (is_array($polinomio->Monomios)) {
            foreach ($polinomio->Monomios as $clave1 => $monomio1) {
                if ($monomio1->Incognita == $monomio->Incognita || $monomio->TerminoIndependiente || $monomio1->TerminoIndependiente) {
                    $mult = new Monomio(null);
                    $mult->Coeficiente = $monomio1->Coeficiente * $monomio->Coeficiente;
                    $mult->Incognita = isset($monomio1->Incognita) ? $monomio1->Incognita : $monomio->Incognita;
                    if (isset($monomio1->Grado) || isset($monomio->Grado))
                        $mult->Grado = $monomio1->Grado + $monomio->Grado;
                    $mult->TerminoIndependiente = !isset($mult->Incognita);


                    unset($polinomio->Monomios[$clave1]);

                    if ($mult->Coeficiente != 0) {
                        $polinomio->Monomios[] = $mult;
                    }
                    elseif (count($polinomio->Monomios) == 0) {
                        $polinomio->Monomios[] = new Monomio(0);
                    }
                }
            }
        }

        return $polinomio;
    }
}