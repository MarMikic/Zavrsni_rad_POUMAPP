$('.naziv').mouseover(function () {

    let element = $(this);

    $.ajax({
        type: 'POST',
        url: '/program/klasenaprogramu',
        data: {
            program: element.attr('id').split('_')[1]
        },
        success: function (odgovor) {
            element.attr('title', odgovor);
        }
    });



});