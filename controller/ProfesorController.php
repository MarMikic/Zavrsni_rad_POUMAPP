<?php

class ProfesorController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'profesor'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Profesor::ucitajSve(),
             'css'=>'<link rel="stylesheet" href="' . APP::config('url') . 'public/profesor/index.css">',
            'javascript'=>'<script src="' . APP::config('url') . 'public/profesor/index.js"></script>'
        ]);
    }

    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $entitet=new stdClass();
            $entitet->ime='';
            $entitet->prezime='';
            $entitet->iban='';
            $entitet->oib='';
            $entitet->email='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'novoView')){return;};
        Profesor::dodajNovi($_POST);
        $this->index();
       
    }

    public function promjena()
    {
         sleep(1); //ovo je čekanje 10 sekundi kako bi testirali Molim pričekajte funkcionalnost
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            Profesor::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'promjenaView')){return;};
        if(!$this->kontrolaOIB($entitet,'promjenaView')){return;};
        Profesor::promjena($_POST);
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Profesor::brisanje($_GET['sifra']);
        $this->index();
        
    }









    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet
        ]);
    }


    private function kontrolaIme($entitet, $view)
    {
        if(strlen(trim($entitet->ime))===0){
            $this->$view('Obavezno unos imena',$entitet);
            return false;
        }

        if(strlen(trim($entitet->ime))>50){
            $this->$view('Dužina imena prevelika',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


    private function kontrolaOIB($entitet, $view)
    {
        $oib=$entitet->oib;
        if ( strlen($oib) != 11 ) {
            $this->$view('OIB mora imati 11 znamenaka',$entitet);
            return false;
        }
            if ( !is_numeric($oib) ) {
                $this->$view('OIB ne smije sadržavati druge znakove osim brojeva',$entitet);
                return false;
            }
                
                $a = 10;
                
                for ($i = 0; $i < 10; $i++) {
                    
                    $a = $a + intval(substr($oib, $i, 1), 10);
                    $a = $a % 10;
                    
                    if ( $a == 0 ) { $a = 10; }
                    
                    $a *= 2;
                    $a = $a % 11;
                    
                }
                
                $kontrolni = 11 - $a;
                
                if ( $kontrolni == 10 ) { $kontrolni = 0; }
                $rezultat = $kontrolni == intval(substr($oib, 10, 1), 10);
                if(!$rezultat){
                    $this->$view('OIB nije valjan',$entitet);
                
                }
                return $rezultat;
    }

}