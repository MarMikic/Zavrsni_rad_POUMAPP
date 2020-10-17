$("#uvjet").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: '/ucenik/traziucenik',
            data: {
                uvjet: request.term,
                klasa: klasa
            },
            success: function (odgovor) {
                response(odgovor);
            }
        });
    },
    minLength: 2,
    select: function (event, ui) {
        spremi(klasa, ui.item);

    }
}).autocomplete('instance')._renderItem = function (ul, item) {
    return $('<li>').append('<div>' + item.ime + ' ' + item.prezime + '</div>   ').appendTo(ul);
};


function spremi(klasa, ucenik) {
    //console.log('Moramo na server poslati šifru grupe: ' + grupa + 
    // ' i šifru polaznika: ' + polaznik.sifra);
    $.ajax({
        type: 'POST',
        url: '/klasa/dodajucenika',
        data: {
            klasa: klasa,
            ucenik: ucenik.sifra
        },
        success: function (odgovor) {
            // console.log(odgovor);

            $('#ucenici').append('<tr>' +
                '<td>' + ucenik.ime + ' ' + ucenik.prezime + '</td>' +
                '<td><a class="obrisi" href="#" id="p_' + ucenik.sifra + '">' +
                'Obriši' +
                '</a></td>' +
                '</tr>');
            definirajBrisanje();
            //location.reload(); //rješenje ali loše
        }
    });


}

function definirajBrisanje() {
    $('.obrisi').click(function () {
        let element = $(this);
        let sifra = element.attr('id').split('_')[1];
        // console.log('idem brisati ucenika: ' + sifra + ' na klasi: ' + klasa);


        $.ajax({
            type: 'POST',
            url: '/klasa/obrisiucenika',
            data: {
                klasa: klasa,
                ucenik: sifra
            },
            success: function (odgovor) {
                if (odgovor == 'OK') {
                    element.parent().parent().remove();
                } else {
                    alert(odgovor);
                }

            }
        });

        return false;
    });
}

definirajBrisanje();