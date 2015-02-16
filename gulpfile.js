var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.less('application.less', 'public/css')
        .version(['css/application.css'])
});
