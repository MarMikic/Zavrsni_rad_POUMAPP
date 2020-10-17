<?php

class Klasa
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra, b.naziv as program, a.naziv,  
        concat(d.ime, \' \', d.prezime) as profesor,
        a.datumpocetka, count(e.ucenik) as ucenika from klasa a
        inner join program b on a.program=b.sifra
        left join profesor c on a.profesor = c.sifra
        left join osoba d on c.osoba = d.sifra
        left join sudionik e on a.sifra=e.klasa 
        group by a.sifra, b.naziv, a.naziv,  
        concat(d.ime, \' \', d.prezime),
        a.datumpocetka

        ');
        $izraz->execute();
        return $izraz->fetchAll();
       
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select * from klasa where sifra=:sifra

        ');
        $izraz->execute(['sifra'=>$sifra]);
        $entitet=$izraz->fetch();

        $izraz = $veza->prepare('
        
                select b.sifra, c.ime, c.prezime 
                from sudionik a inner join ucenik b
                on a.ucenik =b.sifra 
                inner join osoba c 
                on b.osoba =c.sifra 
                where a.klasa=:sifra

        ');
        $izraz->execute(['sifra'=>$sifra]);
        $entitet->ucenici=$izraz->fetchAll();
        return $entitet;
    }

    public static function dodajNovi($entitet)
    {
        if($entitet['profesor']==0){
            $entitet['profesor']=null;
        }
        if($entitet['datumpocetka']==''){
            $entitet['datumpocetka']=null;
        }else{
            $entitet['datumpocetka']=str_replace('T',' ',$entitet['datumpocetka']);
        }
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into klasa (naziv,program,profesor,datumpocetka)
        values (:naziv,:program,:profesor,:datumpocetka);');
        $izraz->execute($entitet);
    }

    public static function promjena($program)
    {
        
    }

    public static function brisanje($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from klasa where sifra=:sifra;');
        $izraz->execute(['sifra'=>$sifra]);
    }


     public static function dodajUcenika()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into sudionik(klasa,ucenik)
        values (:klasa,:ucenik)');
        $izraz->execute($_POST);
    }

    public static function obrisiUcenika()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from sudionik where 
        klasa=:klasa and ucenik=:ucenik');
        $izraz->execute($_POST);
    }

     public static function brojUcenikaPoKlasiJSON()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.sifra,
         a.naziv as name, 
        count(b.ucenik) as y from klasa a
        inner join sudionik b on a.sifra=b.klasa
        inner join program c on a.program=c.sifra
        group by a.sifra, a.naziv order by 3 desc;
        
        ');
        $izraz->execute();
        return json_encode($izraz->fetchAll(),JSON_NUMERIC_CHECK );
    }
}