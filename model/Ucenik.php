<?php

class Ucenik
{

        public static function traziUcenike()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra, a.brojugovora , b.ime, b.prezime, 
        b.oib, b.email  from ucenik a
        inner join osoba b on a.osoba=b.sifra
        where concat(b.ime, \' \', b.prezime, \' \',
        ifnull(b.oib,\'\')) like :uvjet and a.sifra not in
        (select ucenik from sudionik where klasa=:klasa)
        order by b.prezime limit 10
        ');
       
        $izraz->execute([
            'uvjet'=>'%' . $_GET['uvjet'] . '%',
            'klasa'=>$_GET['klasa']
        ]);
        return $izraz->fetchAll();
    }

    public static function ucitajSve($stranica,$uvjet)
    {
        $rps=App::config('rezultataPoStranici');
        $od = $stranica * $rps - $rps;

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra, a.brojugovora , b.ime, b.prezime, 
        b.oib, b.email, count(c.klasa) as klasa from ucenik a
        inner join osoba b on a.osoba=b.sifra
        left join sudionik c on a.sifra=c.ucenik
        where concat(b.ime, \' \', b.prezime, \' \',
        ifnull(b.oib,\'\')) like :uvjet
        group by a.sifra, a.brojugovora, b.ime, b.prezime, 
        b.oib, b.email limit :od,:rps;

        ');
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->bindValue('od',$od,PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps,PDO::PARAM_INT);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ukupnoStranica($uvjet){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
            select count(a.sifra) from ucenik a 
            inner join osoba b on a.osoba=b.sifra 
            where concat(b.ime, \' \', b.prezime, \' \',
            ifnull(b.oib,\'\')) like :uvjet;
        ');
        $izraz->execute(['uvjet'=>$uvjet]);
        return $izraz->fetchColumn();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra, a.brojugovora , b.ime, b.prezime, 
        b.oib, b.email from ucenik a
        inner join osoba b on a.osoba=b.sifra
        where a.sifra=:sifra;

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
        $izraz = $veza->prepare('insert into ucenik 
        (osoba,brojugovora)
        values (:osoba,:brojugovora);');
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'brojugovora'=>$entitet['brojugovora']
        ]);
        $veza->commit();
    }


    public static function promjena($entitet){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select osoba from ucenik where sifra=:sifra ;

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
        
        $izraz = $veza->prepare('update ucenik set
        brojugovora=:brojugovora
                        where sifra=:sifra');
        $izraz->execute([
            'sifra'=>$entitet['sifra'],
            'brojugovora'=>$entitet['brojugovora']
        ]);
        $veza->commit();
    }


    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $veza->beginTransaction();

        $izraz = $veza->prepare('
        
        select osoba from ucenik where sifra=:sifra ;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraOsoba = $izraz->fetchColumn();

        $izraz = $veza->prepare('delete from ucenik
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


      public static function imanemaoib()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
            select count(a.sifra) as ukupno from ucenik a inner join osoba b 
            on a.osoba = b.sifra where b.oib is null or length(b.oib)=0
            union 
            select count(a.sifra) as ukupno from ucenik a inner join osoba b 
            on a.osoba = b.sifra where b.oib is not null and length(b.oib)>0
            ;

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

}