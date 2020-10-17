<?php

class UcenikController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'ucenik'
    . DIRECTORY_SEPARATOR;


        public function traziucenik()
    {
        header('Content-Type: application/json');
        echo json_encode(Ucenik::traziUcenike());
    }
    
    public function index()
    {

        if(isset($_GET['uvjet'])){
            $uvjet='%' . $_GET['uvjet'] . '%';
            $uvjetView=$_GET['uvjet'];
        }else{
            $uvjet='%';
            $uvjetView='';
        }

        if(isset($_GET['stranica'])){
            $stranica=$_GET['stranica'];
        }else{
            $stranica=1;
        }

        if($stranica==1){
            $prethodna=1;
        }else{
            $prethodna=$stranica-1;
        }

        $brojUcenika=Ucenik::ukupnoStranica($uvjet);
        $ukupnoStranica=ceil($brojUcenika/App::config('rezultataPoStranici'));

        if($stranica==$ukupnoStranica){
            $sljedeca=$ukupnoStranica;
        }else{
            $sljedeca=$stranica+1;
        }

        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Ucenik::ucitajSve($stranica,$uvjet),
            'trenutna'=>$stranica,
            'prethodna'=>$prethodna,
            'sljedeca'=>$sljedeca,
            'uvjet'=>$uvjetView,
            'ukupnoStranica'=>$ukupnoStranica,
             'css'=>'
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
            <link rel="stylesheet" href="' . APP::config('url') . 'public/assets/css/cropper.css">
            ',
            'javascript'=>'
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="' . APP::config('url') . 'public/assets/js/cropper.js"></script>
            <script src="' . APP::config('url') . 'public/ucenik/index.js"></script>
            '
        ]);
    }

    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $entitet=new stdClass();
            $entitet->ime='';
            $entitet->prezime='';
            $entitet->brojugovora='';
            $entitet->oib='';
            $entitet->email='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'novoView')){return;};
        Ucenik::dodajNovi($_POST);
        $_GET['uvjet']=$entitet->prezime;
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $_SESSION['stranicaUcenik']=$_GET['stranica'];
            $this->promjenaView('Promjenite željene podatke',
            Ucenik::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'promjenaView')){return;};
        if(!$this->kontrolaOIB($entitet,'promjenaView')){return;};
        Ucenik::promjena($_POST);
        $_GET['stranica']=$_SESSION['stranicaUcenik'];
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Ucenik::brisanje($_GET['sifra']);
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
            'entitet' => $entitet,
            'trenutna'=>$_SESSION['stranicaUcenik']
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



      public function spremisliku(){

        $slika = $_POST['slika'];
        $slika=str_replace('data:image/png;base64,','',$slika);
        $slika=str_replace(' ','+',$slika);
        $data=base64_decode($slika);

        file_put_contents(BP . 'public' . DIRECTORY_SEPARATOR
        . 'img' . DIRECTORY_SEPARATOR . 
        'ucenik' . DIRECTORY_SEPARATOR 
        . $_POST['id'] . '.png', $data);

        echo "OK";
    }
    
}