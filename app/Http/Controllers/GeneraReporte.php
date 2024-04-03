<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GeneraReporte extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       return view('reporte.reporte1');
    }

    public function createPDF(Request $request){
        $rq=$request;

        // $code_rq="data:image/png;base64,".base64_encode(QrCode::format('png')->size(150)->generate($rq->qr)) ; //para png necesita imagick
        $code_rq="data:image/svg;base64,".base64_encode(QrCode::format('svg')->size(150)->generate($rq->qr)) ;
            
        $datar=array(
                            "titulo" =>$rq->titulo,
                            "subtitulo" =>$rq->subtitulo,
                            "fruto_color" =>$rq->fruto_color,
                            "fruto_color_traduccion" =>$rq->fruto_color_traduccion,
                            "productor" =>$rq->productor,
                            "procedencia" =>$rq->procedencia,
                            "fecha_proceso" =>$rq->fecha_proceso,
                            "numero_ventana" =>$rq->numero_ventana,
                            "peso_bruto" =>$rq->peso_bruto,
                            "tara_parihuela" =>$rq->tara_parihuela,
                            "tara_jabas" =>$rq->tara_jabas,
                            "peso_neto" =>$rq->peso_neto,
                            "numero_jabas" =>$rq->numero_jabas,
                            "hora" =>$rq->hora,
                            "qr" =>$code_rq,

        );



        // {
        //     "titulo" : "MERCADO LOCAL"
        //     "subtitulo" : "UVA - PACKING "
        //         "fruto_color": "Green Grapes",
        //         "fruto_color_traduccion": "(Uva Verde)",
        //     "productor" : "LOS OLIVOS" 
        //     "procedencia" : "DESMEDRO"
        //     "fecha_proceso" : "03/12/2022" (fecha pesado)
        //     "numero_ventana" : "337"
        //     "peso_bruto" : "198.00 Kg."
        //     "tara_parihuela" : "14.80 Kg."
        //     "tara_jabas" : "20.80 Kg."
        //     "peso_neto" : "162.40 Kg."
        //     "numero_jabas" : "16 Und."
        //     "hora" : "09:47 p.m."
        //     "qr" : "337"
        // }

        
        view()->share('datar', $datar);
        
        $pdf = PDF::loadView('reporte.reporte1', $datar);

        // return $pdf
                // ->setOption(['dpi' => 100,'isHtml5ParserEnabled' =>true])
                // ->setPaper('a4', 'landscape')
                // ->download('archivo-pdf.pdf');

        // return $pdf
        //         ->setPaper('a4', 'landscape')
        //         ->download('archivo-pdf.pdf');

        return $pdf
                ->setPaper(array(0, 0, 510,  350), 'portrait')//x inicio, y inicio, ancho final, alto final
                ->download('archivo-pdf.pdf');

    }

    public function ImprimeZebra(array $request){

        $response["responsecode"]= "2";
        $response["message"]= "";

        $rq=$request;//$request->all();

        // $zpl ="pepe <<titulo>> prueba <<subtitulo>>";
        
        $zpl="^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR6,6~SD15^JUS^LRN^CI0^XZ".
        "^XA".
        "^MMT".
        "^PW799".
        "^LL1199".
        "^LS0".
        "^FO58,47^GB674,1122,8^FS".
        "^FO299,54^GB0,560,3^FS".
        "^FO376,54^GB0,558,3^FS".
        "^FO622,55^GB0,1108,4^FS".
        "^FO531,54^GB0,1108,3^FS".
        "^FO446,54^GB0,1108,4^FS".
        "^FO231,52^GB0,1111,5^FS".
        "^FT415,1125^A0B,59,60^FH\^FD<<fruto_color_traduccion>>^FS".
        "^FT354,1125^A0B,59,60^FH\^FD<<fruto_color>>^FS".
        "^FT202,1111^A0B,59,60^FH\^FD<<subtitulo>>^FS".
        "^FT128,1111^A0B,59,60^FH\^FD<<titulo>>^FS".
        "^FT124,595^A0B,45,45^FH\^FDN\F8 VENTANA^FS".
        "^FT196,519^A0B,45,45^FH\^FD<<numero_ventana>>^FS".
        "^FT682,851^A0B,39,38^FH\^FD<<fecha_proceso>>^FS".
        "^FT683,1145^A0B,39,38^FH\^FDFECHA PESADO:^FS".
        "^FT593,853^A0B,39,38^FH\^FD<<procedencia>>^FS".
        "^FT593,1147^A0B,39,38^FH\^FDTIPO PRODUCTO:^FS".
        "^FT504,906^A0B,39,38^FH\^FD<<productor>>^FS".
        "^FT502,1149^A0B,39,38^FH\^FDPRODUCTOR:^FS".
        "^FT74,276^BQN,2,7".
        "^FH\^FDMA,<<qr>>^FS".
        "^FT597,282^A0B,39,38^FH\^FD<<numero_jabas>>^FS".
        "^FT599,457^A0B,39,38^FH\^FDN\F8 JABAS:^FS".
        "^FT689,280^A0B,39,38^FH\^FD<<hora>>^FS".
        "^FT689,393^A0B,39,38^FH\^FDHORA:^FS".
        "^FT513,285^A0B,51,50^FH\^FD<<peso_neto>>^FS".
        "^FT515,543^A0B,51,50^FH\^FDPESO NETO:^FS".
        "^FT425,283^A0B,39,38^FH\^FD<<tara_jabas>>^FS".
        "^FT355,286^A0B,39,38^FH\^FD<<tara_parihuela>>^FS".
        "^FT426,486^A0B,39,38^FH\^FDTARA JABA:^FS".
        "^FT355,589^A0B,39,38^FH\^FDTARA PARIHUELA:^FS".
        "^FT281,288^A0B,39,38^FH\^FD<<peso_bruto>>^FS".
        "^FT284,1129^A0B,39,38^FH\^FDCOLOR DE FRUTA:^FS".
        "^FT279,513^A0B,39,38^FH\^FDPESO BRUTO:^FS".
        "^FO65,318^GB167,0,2^FS".
        "^FO63,614^GB667,0,2^FS".
        "^PQ1,0,1,Y^XZ";
        
        dd($rq);
        try
        {
            foreach( $rq as $clave => $valor){
                $llave="<<$clave>>";
                $zpl=str_replace($llave,$valor, $zpl);
            }

            // dd($zpl);
            // str_replace()

            //abrimos el soket de red a la ip de la impresora y el puerto por defecto de zebra es el 9100
            $printerIp ="10.13.20.220";
            $fp=pfsockopen($printerIp, 9100, $errno, $errstr, 5);
            if (!$fp) {
                $response["responsecode"]= "2";
                $response["message"]= "$errstr ($errno)";
                
            }else{
                fputs($fp,$zpl);
                fclose($fp);
                $response["responsecode"]= "1";
                $response["message"]= "Successfully Printed";
            }
            
    
        }
        catch (\Exception $e) 
        {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";

            $response["responsecode"]= "2";
            $response["message"]= $e->getMessage();
        }

        // return redirect('/ingreso-pallets');
        return response()->json($response, 200, 
        ['Content-Type' => 'application/json;charset=UTF-8', 
        'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
