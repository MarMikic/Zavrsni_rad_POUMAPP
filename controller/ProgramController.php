<?php

class ProgramController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'program'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $programi= Program::ucitajSve();
        foreach($programi as $program){
            if(strlen($program->opis)>20){
               $program->opis=substr($program->opis,0,20) . '...';
            }
            if($program->cijena===null){
                $program->cijena='Nije definirana';
            }
            $program->verificiran= $program->verificiran ? 'DA' : 'NE';
        }
        $this->view->render($this->viewDir . 'index',[
            'programi'=>$programi,
            'javascript'=>'<script src="' . APP::config('url') . 'public/program/index.js"></script>'
        ]);
    }


    
    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $program=new stdClass();
            $program->naziv='';
            $program->opis='';
            $program->cijena='';
            $program->verificiran='';
            $this->novoView('Unesite tražene podatke',$program);
            return;
        }
        $program=(object)$_POST;
        if(!$this->kontrolaNaziv($program,'novoView')){return;};
        if(!$this->kontrolaVerificiran($program,'novoView')){return;};
        Program::dodajNovi($_POST);
        $this->index();
    }



    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            Program::ucitaj($_GET['sifra']));
            return;
        }
        $program=(object)$_POST;
        if(!$this->kontrolaNaziv($program,'promjenaView')){return;};
        if(!$this->kontrolaVerificiran($program,'promjenaView')){return;};
        Program::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Program::brisanje($_GET['sifra']);
        $this->index();
        
    }










    private function novoView($poruka,$program)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'program' => $program
        ]);
    }

    private function promjenaView($poruka,$program)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'program' => $program
        ]);
    }

    private function kontrolaNaziv($program, $view)
    {
        if(strlen(trim($program->naziv))===0){
            $this->$view('Obavezno unos naziva',$program);
            return false;
        }

        if(strlen(trim($program->naziv))>50){
            $this->$view('Dužina naziva prevelika',$program);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

    private function kontrolaVerificiran($program, $view)
    {
        if($program->verificiran=='-1'){
            $this->$view('Obavezno odabir indikacije verificiranosti programa',$program);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }





     public function jsonprimjer()
    {
        echo json_encode(Program::ucitajSve());
        }


    public function klasenaprogramu()
    {
        echo Program::klasenaprogramu();
    }

}