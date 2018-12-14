{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container">
    <br /><br />
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1>Identifiez-vous</h1>
            <p>Vous devez disposez d'un compte administrateur pour accéder à l'administration de ce site</p>
            <div class="well">
                {% if errors.credentials %}<div class="alert alert-danger" role="alert">problème d'authentification</div>{% endif %}
                {% if errors.permission %}<div class="alert alert-danger" role="alert">Vous ne disposez d'un compte administrateur</div>{% endif %}
                <br />
                <form role="form" method="post" action="{{ baseUrl}}/admin/login">
                    <div class="form-group">
                        <input type="text" class="form-control" name="identifier" placeholder="identifiant .." required="required" />
                        {% if errors.name %}<div class="alert alert-danger" role="alert">{{ errors.name }}</div>{% endif %}
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Mot de passe .." required="required" />
                        {% if errors.password %}<div class="alert alert-danger" role="alert">{{ errors.content }}</div>{% endif %}
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="remember"/> Se souvenir de moi<br /><br />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-info" value="Connexion" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br /><br /><br />
</div>
{% endblock %}
