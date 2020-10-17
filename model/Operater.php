<?php

class Operater
{

    public static function ucitajSve($stranica,$uvjet)
    {
       

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select * from operater;

        ');
    
        $izraz->execute();
        return $izraz->fetchAll();
    }



    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select * from operater where sifra=:sifra;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }




    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into operater 
        (email,ime,prezime,lozinka,uloga)
        values (:email,:ime,:prezime,:lozinka,:uloga);');
        $izraz->execute([
            'email'=>$entitet['email'],
            'ime'=>$entitet['ime'],
            'prezime'=>$entitet['prezime'],
            'lozinka'=>$entitet['lozinka'],
            'uloga'=>$entitet['uloga']
        ]);
      
    }


    public static function promjena($entitet){
        $veza = DB::getInstanca();
    
        $izraz = $veza->prepare('update operater set
                    email=:email,
                    ime=:ime,
                    prezime=:prezime,
                    lozinka=:lozinka,
                    uloga=:uloga,
                    where sifra=:sifra');
        $izraz->execute([
            'email'=>$entitet['email'],
            'ime'=>$entitet['ime'],
            'prezime'=>$entitet['prezime'],
            'lozinka'=>$entitet['lozinka'],
            'uloga'=>$entitet['uloga'],
            'sifra'=>$entitet['sifra']
        ]);
        
    }


    public static function brisanje($sifra){
        $veza = DB::getInstanca();
       

        $izraz = $veza->prepare('delete from operater
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
      
    }


    public static function promjenaprofil($entitet){
        $veza = DB::getInstanca();
    
        $izraz = $veza->prepare('update operater set
                    lozinka=:lozinka
                    where sifra=:sifra');
        $izraz->execute([
            'lozinka'=>$entitet['lozinka'],
            'sifra'=>$entitet['sifra']
        ]);
        
    }

}