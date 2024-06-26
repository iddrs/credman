import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import $ from 'jquery';
window.$ = window.jQuery = $;


$('.enter-as-tab').keydown(function (e) {
    if (e.which === 13) {
        console.log(this.id);
        if (this.type == 'submit') return;
        e.preventDefault();
        let fields = $(this).closest('form').find('.enter-as-tab');
        fields.eq(fields.index(this) + 1).focus().select();
    }
});


function filterOrigemDoCredito() {
    let tipo = document.getElementById('tipo');
    if (!tipo) return;

    function getOption() {
        let opt = document.createElement('option');
        opt.value = 0;
        return opt;
    }

    function getOptionReducao() {
        let opt = document.createElement('option');
        opt.value = 1;
        opt.text = '1 Redução';
        return opt;
    }

    function getOptionSuperavit() {
        let opt = document.createElement('option');
        opt.value = 2;
        opt.text = '2 Superávit';
        return opt;
    }

    function getOptionExcesso() {
        let opt = document.createElement('option');
        opt.value = 3;
        opt.text = '3 Excesso';
        return opt;
    }

    function getOptionReabertura() {
        let opt = document.createElement('option');
        opt.value = 4;
        opt.text = '4 Reabertura';
        return opt;
    }

    function getOptionNenhuma() {
        let opt = document.createElement('option');
        opt.value = 5;
        opt.text = '5 Nenhuma';
        return opt;
    }

    tipo.addEventListener('change', function () {


        let origem = document.getElementById('origem');

        for (let i = origem.options.length - 1; i >= 0; i--) {
            origem.remove(i);
        }
        origem.appendChild(getOption());

        switch (tipo.value) {
            case '1'://suplementar
                console.log('suplementar');
                origem.appendChild(getOptionReducao());
                origem.appendChild(getOptionSuperavit());
                origem.appendChild(getOptionExcesso());
                break;
            case '2'://especial
                console.log('especial');
                origem.appendChild(getOptionReducao());
                origem.appendChild(getOptionSuperavit());
                origem.appendChild(getOptionExcesso());
                origem.appendChild(getOptionReabertura());
                break;
            case '3'://extraordinário
                console.log('extraordinário');
                origem.appendChild(getOptionReducao());
                origem.appendChild(getOptionSuperavit());
                origem.appendChild(getOptionExcesso());
                origem.appendChild(getOptionReabertura());
                origem.appendChild(getOptionNenhuma());
                break;
        }

    });
}

filterOrigemDoCredito();

// Desabilita o botão de submit formulário no submit

$('form').on('submit', function (e) {
    $(this).find('button[type="submit"]').prop('disabled', true);
})

// Hotkeys
import hotkeys from 'hotkeys-js';

// Necessário para habilitar hotkeys em campos de formulário.
hotkeys.filter = function (event) {
    var target = event.target || event.srcElement;
    var tagName = target, tagName;
    return !(target.isContentEditable || tagName === 'INPUT' || tagName === 'TEXTAREA' || tagName === 'SELECT');
};//end filter

$("[accesskey]").each(function () {
    let label = document.createElement('kbd');
    label.className = 'ui basic label';
    label.innerText = this.accessKey;
    this.appendChild(label);
    var element = this;
    if (this.tagName === 'A') {
        if (this.hasAttribute('href')) {
            hotkeys(this.accessKey, function (event, handler) {
                event.preventDefault()
                window.location.href = element.href;
            });
        }
    }
    if (this.tagName === 'BUTTON') {
        hotkeys(this.accessKey, function (event, handler) {
            event.preventDefault()
            $(element).closest('form').submit();
        });
    }
});
// end Hotkeys

// mask inputs
import 'jquery-mask-plugin';

var maskSpec = {
    'projativ': '0.000',
    'despesa': '0.0.00.00',
    'uniorcam': '00.00',
    'fonte': '000.00',
    'receita': '0.0.0.0.00.0.0.00.00.00'
};

Object.keys(maskSpec).forEach(function (elId) {
    let mask = maskSpec[elId];
    $('#' + elId).mask(mask);
    $('#' + elId).closest('form').on('submit', function (event) {
        $('#' + elId).unmask();
    });
})
// end mask imputs
