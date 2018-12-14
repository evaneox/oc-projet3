<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Jérémy Bouhour">
    <link rel="icon" href="{{ baseUrl }}/public/favicon.ico">
    <title>Billet simple pour l'Alaska</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.4.2/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ baseUrl }}/public/css/main.css?i=2"
</head>

<body>
    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand"><img src="{{ baseUrl }}/public/images/logo.png" /> J.Forteroche</span>
        <div class="navbar-collapse collapse" id="navbarsExampleDefault" aria-expanded="false">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ baseUrl }}"><span class="fa fa-home" aria-hidden="true"></span> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ baseUrl }}/admin"><span class="fa fa-wrench" aria-hidden="true"></span> Administration</a>
                </li>
            </ul>
            {% if auth %}
            <ul class="navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> {{ auth.username }}</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ baseUrl }}/admin/logout/"><i class="fa fa-fw fa-power-off"></i>Déconnexion</a>
                    </div>
                </li>
            </ul>
            {% endif %}
        </div>
    </nav>

    {% block content %}{% endblock content %}

    <hr>
    <footer>
        <p>&copy; 2017 Jean Forteroche | Réalisation par Jérémy Bouhour</p>
    </footer>

    <!-- JAVASCRIPT
    ================================================== -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.4.2/sweetalert2.min.js"></script>
    <script src="http://cloud.tinymce.com/stable/tinymce.min.js?apiKey=spehc333i8sea1cogvl7h92a4z29w5qifh71if3cw3dxrzb4"></script>
    <script src="{{ baseUrl }}/public/js/main.js"></script>
</body>
</html>

