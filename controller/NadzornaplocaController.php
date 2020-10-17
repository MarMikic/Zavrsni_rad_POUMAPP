<?php

class NadzornaplocaController extends AutorizacijaController
{

    private $viewDir = 'privatno' . DIRECTORY_SEPARATOR;

    public function index()
    {
        

         $this->view->render($this->viewDir . 'nadzornaploca',[
            'javascript'=>'
            <script src="https://code.highcharts.com/highcharts.js"></script>
            <script src="https://code.highcharts.com/modules/exporting.js"></script>
            <script src="https://code.highcharts.com/modules/export-data.js"></script>
            <script src="https://code.highcharts.com/modules/accessibility.js"></script>
            <script>let podaci= JSON.parse(\'' . Klasa::brojUcenikaPoKlasiJSON() . '\');</script>
            <script src="' . APP::config('url') . 'public/nadzornaploca.js"></script>',
            'css'=> '<link rel="stylesheet" href="' . APP::config('url') . 'public/nadzornaploca.css">',
            'oibGraf' => Ucenik::imanemaoib()
        ]);
    }

    public function profil(){
        $this->view->render($this->viewDir . 'profil',[
         'entitet'=>$_SESSION['autoriziran'],
            'poruka'=>''
        ]);
    }


    public function profilpromjena(){

        if(!$_POST || !isset($_POST['lozinka']) || 
            !isset($_POST['lozinkaponovo'])){
            return;
            exit;
        }

        if($_POST['lozinka']=='' || $_POST['lozinkaponovo']==''){
            $this->view->render($this->viewDir . 'profil',[
                'entitet'=>$_SESSION['autoriziran'],
                'poruka'=>'Lozinka i lozinka ponovo moraju biti unesene'
            ]);
            exit;
        }

        if($_POST['lozinka']!=$_POST['lozinkaponovo']){
            $this->view->render($this->viewDir . 'profil',[
                'entitet'=>$_SESSION['autoriziran'],
                'poruka'=>'Lozinka i lozinka ponovo ne odgovaraju'
            ]);
            exit;
        }

        Operater::promjenaprofil([
            'lozinka'=>password_hash($_POST['lozinka'], PASSWORD_BCRYPT),
            'sifra'=>$_SESSION['autoriziran']->sifra
        ]);

        $this->index();

        
    }
    

}