$(document).ready(() => {

    const form = $('form');

    $('form select#tipo').change(function(){
        switch (this.value){
            case "F":
                $('div#campi').empty();
                $('<label>').text('PIVA:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 11,
                    minlength: 11,
                    name: 'piva',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('CAP:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'number',
                    max: 99950,
                    min: 1,
                    name: 'cap',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Denominazione:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 100,
                    minlength: 0,
                    name: 'denominazione',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Via:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 100,
                    minlength: 0,
                    name: 'via',
                    required: true
                }).appendTo('div#campi');
                $('#repselect').hide();
                break;
            case "C":
                $('div#campi').empty();
                $('<label>').text('Data di nascita:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'date',
                    name: 'ddn',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Cognome:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    max: 50,
                    min: 0,
                    name: 'cognome',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Nome:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 50,
                    minlength: 0,
                    name: 'nome',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Codice Fiscale:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 16,
                    minlength: 16,
                    pattern: "^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$",
                    name: 'cf',
                    required: true
                }).appendTo('div#campi');
                $('#repselect').hide();
                break;
            case "I":
                $('div#campi').empty();
                $('<label>').text('Data di nascita:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'date',
                    name: 'ddn',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Cognome:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    max: 50,
                    min: 0,
                    name: 'cognome',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Nome:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 50,
                    minlength: 0,
                    name: 'nome',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Codice Fiscale:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'text',
                    maxlength: 16,
                    minlength: 16,
                    pattern: "^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$",
                    name: 'cf',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Data assunzione:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'date',
                    name: 'dassunzione',
                    required: true
                }).appendTo('div#campi');
                $('<label>').text('Data licenziamento:').appendTo('div#campi');
                $('<input>').attr({
                    type: 'date',
                    pattern: "^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$",
                    name: 'dlicenziamento'
                }).appendTo('div#campi');
                $('#repselect').show();
                break;
        }
    })

})

function changeToFornitore(){

}