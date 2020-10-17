<?php

class KlasaController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'klasa'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Klasa::ucitajSve()
        ]);
    }


    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $entitet=new stdClass();
            $entitet->naziv='';
            $entitet->program=0;
            $entitet->profesor=0;
            $entitet->datumpocetka='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'novoView')){return;};
        if(!$this->kontrolaProgram($entitet,'novoView')){return;};
        Klasa::dodajNovi($_POST);
        $this->index();
    }



    public function promjena()
    {
        $entitet = Klasa::ucitaj($_GET['sifra']);
        $entitet->datumpocetka=str_replace(' ','T',$entitet->datumpocetka);

        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            $entitet);
            return;
        }
        $entitet=(object)$_POST;
        if(!$this->kontrolaNaziv($entitet,'novoView')){return;};
        if(!$this->kontrolaProgram($entitet,'novoView')){return;};
        Klasa::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
        Klasa::brisanje($_GET['sifra']);
        $this->index();
    }










    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'programi' => Program::ucitajSve(),
            'profesori' => Profesor::ucitajSve()
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'programi' => Program::ucitajSve(),
            'profesori' => Profesor::ucitajSve(),
             'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">',
            'javascript'=>'<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script>let klasa=' . $entitet->sifra . ';</script>
            <script src="' . APP::config('url') . 'public/klasa/promjena.js"></script>'
        ]);
    }

   
    private function kontrolaProgram($entitet, $view)
    {
        if($entitet->program==0){
            $this->$view('Obavezno odabir programa',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

    private function kontrolaNaziv($entitet, $view)
    {
        if(strlen(trim($entitet->naziv))===0){
            $this->$view('Obavezno unos naziva',$entitet);
            return false;
        }

        if(strlen(trim($entitet->naziv))>50){
            $this->$view('Dužina naziva prevelika',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

       public function dodajucenika()
    {
        Klasa::dodajUcenika();
        echo 'OK';
    }

    public function obrisiucenika()
    {
        Klasa::obrisiUcenika();
        echo 'OK';
    }


}