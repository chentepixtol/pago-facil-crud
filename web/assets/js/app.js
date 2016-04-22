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

    function loadTemplate(content, container) {
        if (!container) {
            container = 'main';
        }
        $('#' + container).html(content);
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

    function loadAllEmployees() {
        $.ajax(baseUrl + 'api/employee?' + auth, {
            'dataType': 'json'
        }).done(function(resp) {
            loadTemplate(getTemplate("#list-employee")({
                employees: resp.collection
            }), 'employee-container');
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
            loadAllEmployees();
        });
    });

    $('#main, #employee-container').on('click', '.returnList', function() {
        loadAllUsers();
        loadAllEmployees();
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

    $('#employee-container').on('click', '.newEmployee', function() {
        loadTemplate(getTemplate("#edit-employee")({
            id: null,
            email: $(this).data('email')
        }), 'employee-container');
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

    $('#employee-container').on('click', '.save', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var isUpdate = $('#idEmployeeInput').val().length > 0;
        var param = (isUpdate ? '/' + $('#idEmployeeInput').val() : '') + '?';

        $.ajax(baseUrl + 'api/employee' + param + auth, {
            'dataType': 'json',
            'type': isUpdate ? 'PUT': 'POST',
            'data': {
                'first_name': $('#first_name').val(),
                'last_name': $('#last_name').val(),
                'surname': $('#surname').val(),
                'birthdate': $('#birthdate').val(),
                'salary': $('#salary').val()
            }
        }).done(function(resp) {
            loadAllEmployees();
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
    loadAllEmployees();

});