<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    private $fpdf;

    public function generarPdf(Request $request)
    {
        $user = $request->user();

        $this->fpdf = new PDFTemplate('P', 'mm', 'Letter');
        // $this->fpdf->Open();
        // $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();
        $this->fpdf->SetFont('Arial', '', 10);
        $this->fpdf->SetMargins(20, 5, 5);
        $this->fpdf->SetWidths(array(10, 20, 70, 20, 20, 10, 10, 10, 10));
        $this->fpdf->SetFillColor(224, 224, 224);
        $this->fpdf->SetTextColor(0);

        $this->fpdf->SetX(20);
        $this->fpdf->Cell(30, 5, "No. de Control", 0, 1, 'L');
        $this->fpdf->Cell(30, 6, $user->login, 1, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial', 'B', 11);
        $this->fpdf->SetFillColor(20, 22, 25);
        $this->fpdf->Cell(180, 6, utf8_decode("Datos Personales"), 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(130, 5, utf8_decode("Nombre del alumno"), 0, 0, 'C');
        $this->fpdf->Cell(50, 5, utf8_decode("Fecha actual"), 0, 1, 'C');

        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(40, 7, utf8_decode($user->alumno->detalles->aluapp), 1, 0, 'C');
        $this->fpdf->SetX(65);
        $this->fpdf->Cell(40, 7, utf8_decode($user->alumno->detalles->aluapm), 1, 0, 'C');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(40, 7, utf8_decode($user->alumno->detalles->alunom), 1, 0, 'C');
        $this->fpdf->SetX(155);
        $this->fpdf->Cell(10, 7, utf8_decode(""), 1, 0, 'C');
        $this->fpdf->SetX(170);
        $this->fpdf->Cell(10, 7, utf8_decode(""), 1, 0, 'C');
        $this->fpdf->SetX(185);
        $this->fpdf->Cell(15, 7, utf8_decode(""), 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(40, 5, utf8_decode("A. Paterno"), 0, 0, 'C');
        $this->fpdf->SetX(65);
        $this->fpdf->Cell(40, 5, utf8_decode("A. Materno"), 0, 0, 'C');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(40, 5, utf8_decode("Nombre(s)"), 0, 0, 'C');
        $this->fpdf->SetX(155);
        $this->fpdf->Cell(10, 5, utf8_decode("dia"), 0, 0, 'C');
        $this->fpdf->SetX(170);
        $this->fpdf->Cell(10, 5, utf8_decode("mes"), 0, 0, 'C');
        $this->fpdf->SetX(185);
        $this->fpdf->Cell(15, 5, utf8_decode("año"), 0, 1, 'C');
        $this->fpdf->Ln(2);

        $this->fpdf->Cell(180, 5, utf8_decode("Domicilio"), 0, 1, 'L');
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(85, 7, utf8_decode($user->alumno->detalles->alucll), 1, 0, 'C');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(15, 7, utf8_decode($user->alumno->detalles->alunum), 1, 0, 'C');
        $this->fpdf->SetX(130);
        $this->fpdf->Cell(15, 7, utf8_decode(""), 1, 0, 'C');
        $this->fpdf->SetX(150);
        $this->fpdf->Cell(50, 7, utf8_decode($user->alumno->detalles->alucol), 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(85, 5, utf8_decode("Calle"), 0, 0, 'L');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(15, 5, utf8_decode("N° Ext."), 0, 0, 'C');
        $this->fpdf->SetX(130);
        $this->fpdf->Cell(15, 5, utf8_decode("N° Int."), 0, 0, 'C');
        $this->fpdf->SetX(170);
        $this->fpdf->Cell(15, 5, utf8_decode("Colonia/Localidad/Población"), 0, 1, 'C');


        $this->fpdf->Ln(2);
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(55, 7, utf8_decode($user->alumno->detalles->aluciu), 1, 0, 'C');
        $this->fpdf->SetX(80);
        $this->fpdf->Cell(55, 7, utf8_decode("VERACRUZ"), 1, 0, 'C');
        $this->fpdf->SetX(140);
        $this->fpdf->Cell(30, 7, utf8_decode($user->alumno->detalles->alute1), 1, 0, 'C');
        $this->fpdf->SetX(175);
        $this->fpdf->Cell(25, 7, utf8_decode($user->alumno->detalles->alucpo), 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(55, 5, utf8_decode("Municipio"), 0, 0, 'L');
        $this->fpdf->SetX(80);
        $this->fpdf->Cell(55, 5, utf8_decode("Entidad Federativa"), 0, 0, 'C');
        $this->fpdf->SetX(140);
        $this->fpdf->Cell(30, 5, utf8_decode("C.P."), 0, 0, 'C');
        $this->fpdf->SetX(175);
        $this->fpdf->Cell(25, 7, utf8_decode("Teléfono"), 0, 1, 'C');
        $this->fpdf->SetFont('Arial', 'B', 11);

        $this->fpdf->Ln(5);
        $this->fpdf->SetFillColor(20, 22, 25);
        $this->fpdf->Cell(180, 6, utf8_decode("Datos Académicos"), 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(180, 5, utf8_decode("Carrera"), 0, 1, 'L');
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(180, 7, utf8_decode($user->detalleCarrera->nombre), 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(90, 5, utf8_decode("Semestre Solicitado"), 0, 0, 'L');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(90, 5, utf8_decode("Turno"), 0, 1, 'L');
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(85, 7, utf8_decode($this->nombreSemestre($user->alumno->nvoper)), 1, 0, 'C');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(90, 7, utf8_decode(""), 1, 1, 'C');

        $this->fpdf->Ln(5);
        $this->fpdf->SetFont('Arial', 'B', 11);
        $this->fpdf->SetFillColor(20, 22, 25);
        $this->fpdf->Cell(180, 6, utf8_decode("Solicitud de Carga Académica"), 1, 1, 'C');

        $this->fpdf->Ln(5);
        $this->fpdf->SetX(20);
        $this->fpdf->SetWidths(array(10, 20, 70, 20, 20, 10, 10, 10, 10));
        $this->fpdf->SetFont('Arial', '', 10);
        $this->fpdf->Cell(10, 10, utf8_decode("N.P."), 1, 0, 'C');
        $this->fpdf->Cell(20, 10, null, 1, 0, 'C');
        $this->fpdf->Text(31, 167, "Clave de la");
        $this->fpdf->Text(31, 170, "Asignatura");
        $this->fpdf->Cell(70, 10, utf8_decode("Nombre de la Asignatura"), 1, 0, 'C');
        $this->fpdf->Cell(20, 10, utf8_decode("Créditos"), 1, 0, 'C');
        $this->fpdf->Cell(20, 10, utf8_decode("Grupo"), 1, 0, 'C');
        $this->fpdf->Cell(40, 5, utf8_decode("Curso"), 1, 1, 'C');
        $this->fpdf->SetX(160);
        $this->fpdf->Cell(10, 5, utf8_decode("N"), 1, 0, 'C');
        $this->fpdf->Cell(10, 5, utf8_decode("R"), 1, 0, 'C');
        $this->fpdf->Cell(10, 5, utf8_decode("G"), 1, 0, 'C');
        $this->fpdf->Cell(10, 5, utf8_decode("E"), 1, 0, 'C');


        $this->fpdf->Ln(5);

        $materias_precarga = $user->precarga->all();

        $this->fpdf->SetFont('Arial', '', 10);
        $this->fpdf->SetFillColor(255, 255, 255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetX(20);

        foreach ($materias_precarga as $key => $materia) {
            $this->fpdf->Row(
                array(
                    $key,
                    $materia['matcve'],
                    $materia->detalles->matnom,
                    $materia['matcre'],
                    $materia['grupo'],
                    ($materia['tipo'] == 'N' ? 'X' : ''),
                    ($materia['tipo'] == 'R' ? 'X' : ''),
                    ($materia['tipo'] == 'G' ? 'X' : ''),
                    ($materia['tipo'] == 'E' ? 'X' : ''),
                )
            );
        }

        $this->fpdf->Ln(3);
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Cell(180, 5, utf8_decode("N = Normal            R = Curso de Repetición        G = Asignatura Global        E = Asignatura en Especial"), 0, 1, 'L');


        $this->fpdf->Ln(3);
        $this->fpdf->Cell(180, 4, utf8_decode("Bajo protesta de decir la verdad, manifiesto que los datos proporcionados en el presente documento son verdaderos, y en caso"), 0, 1, 'C');
        $this->fpdf->Cell(180, 4, utf8_decode("contrario, me sujetaré a lo que marcan las disposiciones jurídicas internas de la institución."), 0, 1, 'C');
        $this->fpdf->Rect(30, 230, 60, 20);
        $this->fpdf->Rect(120, 230, 60, 20);
        $this->fpdf->Text(42, 248, $user->alumno->detalles->alunom . " " .  $user->alumno->detalles->aluapp . " " . $user->alumno->detalles->aluapm);
        $this->fpdf->Text(42, 253, "Nombre y Firma del Alumno");
        $this->fpdf->Text(127, 248, "");
        $this->fpdf->Text(128, 253, "Nombre y Firma de quien Autoriza");
        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->Text(180, 270, "JULIO 2017");
        $this->fpdf->Output();
        return null;
    }


    protected function nombreSemestre($semestre)
    {
        switch ($semestre) {
            case 1:
                return "PRIMERO";
            case 2:
                return "SEGUNDO";
            case 3:
                return "TERCERO";
            case 4:
                return "CUARTO";
            case 5:
                return "QUINTO";
            case 6:
                return "SEXTO";
            case 7:
                return "SÉPTIMO";
            case 8:
                return "OCTAVO";
            case 9:
                return "NOVENO";
            case 10:
                return "DÉCIMO";
            case 11:
                return "DÉCIMO PRIMERO";
            case 12:
                return "DÉCIMO SEGUNDO";
            case 13:
                return "DÉCIMO TERCEO";
            case 14:
                return "DÉCIMO CUARTO";
            case 15:
                return "DÉCIMO QUINTO";
        }
    }
}

class PDFTemplate extends Fpdf
{
    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';

            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border


            $this->MultiCell($w, 5, $data[$i], 0, $a, 'true');
            $this->Rect($x, $y, $w, $h);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        //echo $h.' '.$this->PageBreakTrigger.' ';
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->SetX(55);
        }
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function Header()
    {
        $this->SetY(10);
        // $this->Image('imgs/logo.jpg', 170, 8, 35);
        $this->SetFont('Arial', 'B', 11);
        $this->SetY(30);
        $this->Cell(180, 8, utf8_decode("Precarga FEBRERO - JULIO 2021"), 0, 1, 'C');
    }

    function Footer()
    {
        /*$this->SetFont('Arial','',7);
	$this->SetXY(4,260);
	$this->Cell(30,10,"ORIGINAL-ITSA",0,0,'L');
	$this->SetXY(4,263);
	$this->Cell(30,10,"COPIA-ASPIRANTE",0,0,'L');
	$this->SetXY(60,260);
	$this->Cell(30,10,"LA FICHA PROVISIONAL DEBE CANJEARSE POR FORMATO OFICIAL EXPEDIDO POR EL DEPTO. DE SERV. ESCOLARES",0,0,'L');*/
    }
}
