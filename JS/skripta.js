//https://github.com/tjakopec/CistiJS
//https://github.com/tjakopec/OSC3JS
//https://github.com/tjakopec/AngularJS-PHP-PDO-CRUD


console.log('Hello iz konzole');

// Komentar

/*
*
* komentar više linija
*/

/*

 komentar više linija

*/

var ime='Edunova';
console.log(typeof ime);

let godine=12;
console.log(typeof godine);

var placa=12000.45;
console.log(typeof placa);

var zaposlen=true;
console.log(typeof zaposlen);

var niz=['prvi','drugi']; //indeksni niz
console.log(typeof niz);

niz=2;
console.log(typeof niz);

niz=[];
niz['k1']=2;
niz['k2']=3; //asocijativni niz

console.log(typeof niz);

var objekt = {ime: 'Pero', stoji: false};
console.log(typeof objekt);


/// JSON - JavaScript Object Notation
var podaci = [
    {
        ime: 'Edunova',
        godine: 12
    },
    {
        ime: 'Škola',
        godine: 5
    }
];

console.log(objekt.ime);
console.log(podaci[0].ime);
console.log(10 % 2);

var x="2";

console.log(x==2);
console.log(x===2);

console.log('Prvi dio ' + 'drugi dio');


if(x==2){
    console.log("OK");
}else if(x>2){
    console.log("Nije");
}


switch(x){
    case 1:
        console.log("NIJE");
        break;
    case 2:
        console.log("OK");
        break;
    default:
        console.log("nije");
}

//https://stackoverflow.com/questions/9329446/for-each-over-an-array-in-javascript
console.log(niz);

var indeksniNiz=[1,2,3,'zadnji'];
console.log(indeksniNiz.length);
for(var i=0;i<indeksniNiz.length;i++){
    console.log(indeksniNiz[i]);
}

for(k in niz){
    console.log(k + ": " + niz[k]);
}

var x=0;
while(++x<=10){
    console.log(x);
}

x=0;
do{
    console.log(x+1);
}while(++x<10);

/*
x=1000000;
for(;;){
    console.log(x);
    if(x--<0){
        break;
    }
}
*/
/*
while(true){

}
*/

function izvedi(){
    console.log("1. vrsta");
}

function zbroj(a,b){
    console.log(a+b);
}

function sb(){
    return Math.random();
}

function imePrezime(o){
    return o.ime + ' ' + o.prezime;
}

izvedi();
zbroj(2,3);
console.log(sb());
console.log(imePrezime({ime: 'Pero', prezime: 'Perić'}));

function akcija(){
    console.log('Izveo akciju');
}




,akcija);
document.getElementById('akcija2').addEventListener('click',function(){
    console.log('Akcija iz inner funtion');
});


