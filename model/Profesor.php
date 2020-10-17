<?php

class Profesor
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra, a.iban, b.ime, b.prezime, 
        b.oib, b.email, count(c.sifra) as klasa from profesor a
        inner join osoba b on a.osoba=b.sifra
        left join klasa c on a.sifra=c.profesor
        group by a.sifra, a.iban, b.ime, b.prezime, 
        b.oib, b.email;

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra, a.iban, b.ime, b.prezime, 
        b.oib, b.email from profesor a
        inner join osoba b on a.osoba=b.sifra
        where a.sifra=:sifra

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }




    public static function dodajNovi($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('insert into osoba 
        (ime,prezime,oib,email)
        values (:ime,:prezime,:oib,:email);');
        $izraz->execute([
            'ime'=>$entitet['ime'],
            'prezime'=>$entitet['prezime'],
            'oib'=>$entitet['oib'],
            'email'=>$entitet['email']
        ]);
        $zadnjaSifra=$veza->lastInsertId();
        $izraz = $veza->prepare('insert into profesor 
        (osoba,iban)
        values (:osoba,:iban);');
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'iban'=>$entitet['iban']
        ]);
        $veza->commit();
    }


    public static function promjena($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select osoba from profesor where sifra=:sifra ;

        ');
        $izraz->execute(['sifra'=>$entitet['sifra']]);
        $sifraOsoba = $izraz->fetchColumn();

        $izraz = $veza->prepare('update osoba set
                    ime=:ime,
                    prezime=:prezime,
                    oib=:oib,
                    email=:email
                    where sifra=:sifra');
        $izraz->execute([
            'ime'=>$entitet['ime'],
            'prezime'=>$entitet['prezime'],
            'oib'=>$entitet['oib'],
            'email'=>$entitet['email'],
            'sifra'=>$sifraOsoba
        ]);
        
        $izraz = $veza->prepare('update profesor set
                        iban=:iban
                        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$entitet['sifra'],
            'iban'=>$entitet['iban']
        ]);
        $veza->commit();


         if(isset($_FILES['slika'])){
            $putanja= BP . 'public'  . DIRECTORY_SEPARATOR
            . 'img' . DIRECTORY_SEPARATOR . 'profesor' 
            . DIRECTORY_SEPARATOR . $entitet['sifra'] . '.jpg';
            move_uploaded_file($_FILES['slika']['tmp_name'],$putanja);
        }

    }


    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select osoba from profesor where sifra=:sifra ;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraOsoba = $izraz->fetchColumn();

        $izraz = $veza->prepare('delete from profesor
        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        
        $izraz = $veza->prepare('delete from osoba
                        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$sifraOsoba
        ]);
        $veza->commit();
    }

}