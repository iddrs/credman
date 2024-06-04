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
