<?php 

class Program
{

    public static function ucitajSve($poCemu='naziv', $uzlaznoSilazno='asc')
    {
        $order = $poCemu . ' ' . $uzlaznoSilazno;
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select a.sifra, a.naziv, a.opis, a.cijena, 
                a.verificiran, count(b.sifra ) as klasa
                from program a left join klasa b on
                a.sifra=b.program group by a.sifra, a.naziv, 
                a.opis, a.cijena, a.verificiran order by :slozi;

        ');
        $izraz->bindParam('slozi',$order);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select * from program where sifra=:sifra;

        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }



    public static function dodajNovi($program){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into program (naziv,opis,cijena,verificiran)
        values (:naziv,:opis,:cijena,:verificiran);');
        $izraz->execute($program);
    }



    public static function promjena($program){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('update program set 
        naziv=:naziv,
        opis=:opis,
        cijena=:cijena,
        verificiran=:verificiran
        where sifra=:sifra;');
        $izraz->execute($program);
    }


    
    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from program where sifra=:sifra;');
        $izraz->execute(['sifra'=>$sifra]);
    }


    public static function klasenaprogramu()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select naziv from klasa where program=:program;

        ');
        $izraz->execute($_POST);
        $rezultati = $izraz->fetchAll();
        $klase=[];
        foreach($rezultati as $red){
            $klase[]=$red->naziv;
        }
        return implode(',',$klase);
    }
    
}