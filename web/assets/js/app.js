$(document).ready(function() {

    var auth = $.param({
        'user': sessionStorage.email,
        'token': sessionStorage.token
    });
    var baseUrl = 'app_dev.php/';

    function getTemplate(name) {
        var source   = $(name).text();
        return Handlebars.compile(source);
    }

    function loadTemplate(content) {
        $('#main').html(content);
    }

    function loadAllUsers() {
        $.ajax(baseUrl + 'api/user?' + auth, {
            'dataType': 'json'
        }).done(function(resp) {
            loadTemplate(getTemplate("#list")({
                users: resp.collection
            }));
        });
    }

    $('#main').on('click', '.loginButton', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $.ajax(baseUrl + 'api/login', {
            'dataType': 'json',
            'type': 'POST',
            'data': {
                'email': $('#loginEmail').val(),
                'password': $('#loginPassword').val()
            }
        }).done(function(resp) {
            sessionStorage.email = resp.model.email;
            sessionStorage.token = resp.model.token;
            auth = $.param({
                'user': sessionStorage.email,
                'token': sessionStorage.token
            });
            loadAllUsers();
        });
    });

    $('#main').on('click', '.returnList', function() {
        loadAllUsers();
    });

    $('#main').on('click', '.edit', function() {
        loadTemplate(getTemplate("#edit")({
            id: $(this).data('id'),
            email: $(this).data('email')
        }));
    });

    $('#main').on('click', '.newItem', function() {
        loadTemplate(getTemplate("#edit")({
            id: null,
            email: $(this).data('email')
        }));
    });

    $('#main').on('click', '.save', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var isUpdate = $('#idInput').val().length > 0;
        var param = (isUpdate ? '/' + $('#idInput').val() : '') + '?';

        $.ajax(baseUrl + 'api/user' + param + auth, {
            'dataType': 'json',
            'type': isUpdate ? 'PUT': 'POST',
            'data': {
                'email': $('#emailInput').val(),
                'password': $('#passwordInput').val()
            }
        }).done(function(resp) {
            loadAllUsers();
        });

    });

    $('#main').on('click', '.close', function(e) {
        sessionStorage.removeItem('email');
        sessionStorage.removeItem('token');
        loadTemplate(getTemplate("#login")());
    });

    $('#main').on('click', '.delete', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var isDelete = $(this).data('id') > 0;
        if (isDelete) {
            var param =  '/' + $(this).data('id') + '?';
            $.ajax(baseUrl + 'api/user' + param + auth, {
                'dataType': 'json',
                'type': 'DELETE'
            }).done(function(resp) {
                loadAllUsers();
            });
        }
    });

    if (!sessionStorage.email || !sessionStorage.token) {
        loadTemplate(getTemplate("#login")());
        return;
    }

    loadAllUsers();

});